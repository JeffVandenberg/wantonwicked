<?php
/**
 * Created by JetBrains PhpStorm.
 * User: jvandenberg
 * Date: 4/26/13
 * Time: 1:32 PM
 * To change this template use File | Settings | File Templates.
 */

namespace classes\core\repository;

use classes\core\data\DataModel;
use classes\core\helpers\SlugHelper;
use Exception;
use PDO;
use ReflectionClass;

/**
 * Class AbstractRepository
 * @package classes\core\data_access
 */
abstract class AbstractRepository extends Database
{
    /**
     * List of Classes to eager load
     * @var array
     */
    protected $EagerLoad;

    /**
     * Debug Queries
     * @var bool
     */
    public $Debug = false;
    /**
     * @var DataModel
     */
    protected $ManagedObject;

    public static $cache;

    /**
     * @param string|null $className
     * @param null $connection
     * @return \classes\core\repository\AbstractRepository
     */
    function __construct($className = null, $connection = null)
    {
        parent::__construct($connection);
        if($className !== null)
        {
            $this->ManagedObject = new $className();
        }
    }

    /**
     * Get a specific item by the managed objects id
     * @param $id
     * @return DataModel
     */
    public function getById($id)
    {
        if(!isset(RepositoryManager::$cache[$this->ManagedObject->getRepositoryClass()][$id]))
        {
            $tableName = $this->ManagedObject->getTableName();
            $idColumn = $this->ManagedObject->getIdColumn();

            $sSql = <<<EOQ
SELECT
    *
FROM
    $tableName
WHERE
    $idColumn = ?
EOQ;

            $class = $this->ManagedObject->getFullClassName();
            $oItem = new $class();
            $params = array(
                $id
            );
            $row = $this->query($sSql)->single($params);
            if($row !== false)
            {
                $oItem = $this->populateObject($row);
            }
            RepositoryManager::$cache[$this->ManagedObject->getRepositoryClass()][$id] = $oItem;
        }
        return clone RepositoryManager::$cache[$this->ManagedObject->getRepositoryClass()][$id];
    }

    /**
     * Performs a query. Returning an array if a select or an integer if a non-select query
     * @param $sql
     * @return array|int
     */
    public function doQuery($sql)
    {
        if ($this->Debug) {
            echo "SQL: $sql\n\n";
        }

        $this->query($sql)->execute();
        if($this->Statement->columnCount() > 0)
        {
            return $this->Statement->fetchAll(PDO::FETCH_ASSOC);
        }
        else
        {
            return $this->Statement->rowCount();
        }
    }

    /**
     * @param array|bool $row
     * @return mixed
     */
    protected function populateObject($row)
    {
        $class = $this->ManagedObject->getFullClassName();
        $oItem = new $class();
        if(is_array($row))
        {
            foreach ($row as $sKey => $lineValue) {
                $sPropertyName = $this->ManagedObject->GetColumnMapping($sKey);
                if(property_exists($class, $sPropertyName)) {
                    $oItem->$sPropertyName = $lineValue;
                }
            }
        }
        return $oItem;
    }

    /**
     * Deletes an instance of the object managed by the repository
     * @param int $id
     * @return int
     */
    public function delete($id)
    {
        $tableName = $this->ManagedObject->getTableName();
        $idColumn = $this->ManagedObject->getIdColumn();

        $sql = <<<EOQ
DELETE FROM
    $tableName
WHERE
    $idColumn = :id
LIMIT 1
EOQ;
        $this->query($sql)->bind(':id', $id)->execute();

        return $this->Statement->rowCount();
    }

    /**
     * Lists all instances of the managed object in the table
     * @return array
     */
    public function listAll()
    {
        $tableName = $this->ManagedObject->getTableName();
        $sortColumn = $this->ManagedObject->getSortColumn();

        $sql = <<<EOQ
SELECT
    *
FROM
    $tableName
ORDER BY
    $sortColumn
EOQ;

        $items = array();
        $rows = $this->query($sql)->all();
        foreach($rows as $row) {
            $items[] = $this->populateObject($row);
        }
        $rows = null;
        return $items;
    }

    /**
     * Returns an array of all Items with ID and Value based on the configured ID
     * and Name fields for the managed object
     * @return array
     */
    public function simpleListAll()
    {
        $tableName = $this->ManagedObject->getTableName();
        $idColumn = $this->ManagedObject->getIdColumn();
        $nameColumn = $this->ManagedObject->getNameColumn();
        $sortColumn = $this->ManagedObject->getSortColumn();

        $sql = <<<sortColumn
SELECT
    $idColumn,
    $nameColumn
FROM
    $tableName
ORDER BY
    $sortColumn
sortColumn;

        $items = array();
        foreach($this->query($sql)->all() as $row)
        {
            $items[$row[$this->ManagedObject->getIdColumn()]] = $row[$this->ManagedObject->getNameColumn()];
        }

        return $items;
    }

    /**
     * Create or Update an instance of the Managed Object
     * @param \classes\core\data\DataModel $item
     * @return bool
     */
    public function save(DataModel $item)
    {
        $this->beforeSave($item);
        $idProperty = $item->getIdProperty();
        if(isset(RepositoryManager::$cache[$this->ManagedObject->getRepositoryClass()][$item->$idProperty])) {
            unset(RepositoryManager::$cache[$this->ManagedObject->getRepositoryClass()][$item->$idProperty]);
        }
        $tableName = $this->ManagedObject->getTableName();
        $idColumn = $this->ManagedObject->getIdColumn();
        $idProperty = $this->ManagedObject->getIdProperty();

        if ($item->{$this->ManagedObject->getIdProperty()} > 0) {
            if($this->equalToCurrent($item))
            {
                return true;
            }
            $sql = "UPDATE $tableName SET ";
            $first = true;

            foreach ($item as $property => $lineValue) {
                if (!(is_object($lineValue) || is_array($lineValue))) {
                    if (!$first) {
                        $sql .= ",";
                    }

                    if ($item->$property === null) {
                        $sql .= $this->ManagedObject->GetPropertyMapping($property) . "= null";
                    } else {
                        $sql .= $this->ManagedObject->GetPropertyMapping($property) . "='" . mysql_real_escape_string($item->$property) . "'";
                    }

                    $first = false;
                }
            }
            $sql .= " WHERE $idColumn = {$item->{$idProperty}} ";
        } else {
            $sFields = "(";
            $sValues = "(";

            $first = true;
            foreach ($item as $property => $lineValue) {
                if (!(is_object($item->$property)
                    || is_array($item->$property)
                    || $item->$property === null)
                ) {
                    if (!$first) {
                        $sFields .= ",";
                        $sValues .= ",";
                    }

                    $sFields .= $this->ManagedObject->GetPropertyMapping($property);
                    $sValues .= "'" . mysql_real_escape_string($item->$property) . "'";

                    $first = false;
                }
            }
            $sFields .= ")";
            $sValues .= ")";

            $sql = "INSERT INTO $tableName $sFields VALUES $sValues;";
        }

        $affectedRows = $this->query($sql)->execute();

        if($affectedRows)
        {
            if ($item->{$this->ManagedObject->getIdProperty()} == 0) {
                $item->{$this->ManagedObject->getIdProperty()} = $this->Handler->lastInsertId();
            }

            $this->afterSave($item);
        }

        return $affectedRows;
    }

    /**
     * @param $name
     * @param $arguments
     * @return array
     * @throws \Exception
     */
    function __call($name, $arguments)
    {
        $tableName = $this->ManagedObject->getTableName();
        $sortColumn = $this->ManagedObject->getSortColumn();
        $fullClassName = $this->ManagedObject->getFullClassName();
        if (strpos($name, 'ListBy') === 0) {
            $fields = explode('And', substr($name, 6));
            $sql = <<<EOQ
SELECT
    *
FROM
    $tableName
WHERE
EOQ;
            $first = true;
            foreach ($fields as $i => $field) {
                if (!$first) {
                    $sql .= ' AND ';
                }
                $sql .= ' ' . $this->ManagedObject->GetPropertyMapping($field) . ' = \'' . mysql_real_escape_string($arguments[$i]) . '\' ';
                $first = false;
            }

            $sql .= <<<EOQ
ORDER BY
    $sortColumn
EOQ;

            $items = array();

            foreach($this->query($sql)->all() as $row) {
                $items[] = $this->populateObject($row);
            }

            return $items;

        } else if (strpos($name, 'FindBy') === 0) {
            $fieldNames = substr($name, 6);
            $fields = explode('And', $fieldNames);

            $sql = <<<EOQ
SELECT
    *
FROM
    $tableName
WHERE
EOQ;
            $first = true;
            foreach ($fields as $i => $field) {
                if (!$first) {
                    $sql .= ' AND ';
                }
                $sql .= ' ' . SlugHelper::FromPropertyToName($field) . ' = \'' . mysql_real_escape_string($arguments[$i]) . '\' ';
                $first = false;
            }

            $oItem = new $fullClassName();

            $result = $this->query($sql)->single();

            if($result !== false)
            {
                $oItem = $this->populateObject($result);
            }

            return $oItem;
        } else {
            throw new Exception('Unknown Method: ' . $name);
        }
    }

    /**
     * @param $table
     * @param $select
     * @return array
     */
    public function processSelect($table, $select)
    {
        $sql = "";
        if ($table != '') {
            $managedObject = $this->ManagedObject->GetManagedObject($table);
        }
        foreach ($select as $key => $linealue) {
            if (is_array($linealue)) {
                $sql .= $this->processSelect($key, $linealue);
            } else {
                // table name in field
                if ($table == '') {
                    $managedObject = $this->ManagedObject->GetManagedObject($key);
                    /* @var DataModel $managedObject */
                    $sql .= ' ' . $managedObject->getTableName() . ".*,\n";
                } else {
                    /* @var DataModel $managedObject */
                    $columnName = $managedObject->GetPropertyMapping($linealue);
                    $sql .= ' ' . $managedObject->getTableName() . '.' . $columnName . ",\n";
                }
            }
        }
        return $sql;
    }

    /**
     * @param $source
     * @param $target
     * @return string
     */
    private function processFrom($source, $target)
    {
        $sql = "";
        foreach ($target as $key => $table) {
            if (is_array($table)) {
                $rootObject = $this->ManagedObject->GetManagedObject($key);
                /* @var DataModel $rootObject */
                $sql .= ' ' . $rootObject->getTableName() . " \n";
                $sql .= $this->processFrom($key, $table);
            } else {
                if ($table != '') {
                    $sourceObject = $this->ManagedObject->GetManagedObject($source);
                    $sql .= ' LEFT JOIN ' . $sourceObject->GetJoin($table) . " \n";
                }
            }
        }
        return $sql;
    }

    /**
     * @param $queries
     * @return void
     */
    public function runQueries($queries)
    {
        foreach ($queries as $sql) {
            $this->doQuery($sql);
        }
    }

    /**
     * Event Handler called before the Save Method
     * @param $item
     */
    protected function beforeSave($item)
    {
        // event handler, do nothing by default
    }

    /**
     * Event Handler called after the Save Method
     * @param $item
     */
    protected function afterSave($item)
    {
        // event handler, do nothing by default
    }

    /**
     * Touches a managed object.
     * @param $id
     */
    public function touch($id)
    {
        $item = $this->getById($id);
        $this->save($item);
    }

    private function equalToCurrent(DataModel $newItem)
    {
        $idProperty = $this->ManagedObject->getIdProperty();
        $currentItem = $this->getById($newItem->$idProperty);

        $type = get_class($newItem);

        $class = new ReflectionClass($type);
        $properties = $class->getProperties();

        $itemsMatch = true;
        foreach($properties as $property)
        {
            $propertyName = $property->getName();
            if($newItem->$propertyName != $currentItem->$propertyName)
            {
                $itemsMatch = false;
                break;
            }
        }

        return $itemsMatch;
    }
}