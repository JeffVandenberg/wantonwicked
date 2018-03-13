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
     * @throws Exception
     */
    public function __construct($className = null, $connection = null)
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
            $this->addToCache($oItem);
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
                $sPropertyName = $this->ManagedObject->getColumnMapping($sKey);
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
        $tableName = $this->ManagedObject->getTableName();
        $idColumn = $this->ManagedObject->getIdColumn();
        $idProperty = $this->ManagedObject->getIdProperty();

        if ($item->{$this->ManagedObject->getIdProperty()} > 0) {
            if($this->equalToCurrent($item))
            {
                return true;
            }
            $sql = "UPDATE $tableName SET ";

            $fields = [];
            $params = [];
            foreach ($item as $property => $lineValue) {
                if (!(is_object($lineValue) || is_array($lineValue))) {
                    if ($item->$property === null) {
                        $fields[] = $this->ManagedObject->getPropertyMapping($property) . "= null";
                    } else {
                        $fields[] = $this->ManagedObject->getPropertyMapping($property) . " = ? ";
                        $params[] = $item->$property;
                    }
                }
            }
            $sql .= implode(',', $fields) . " WHERE $idColumn = ? ";
            $params[] = $item->$idProperty;
        } else {
            $columns = [];
            $params = [];
            foreach ($item as $property => $lineValue) {
                if (!(is_object($item->$property)
                    || is_array($item->$property)
                    || $item->$property === null)
                ) {
                    $columns[] = $this->ManagedObject->getPropertyMapping($property);
                    $params[] = $item->$property;

                }
            }
            $fields = implode(',', $columns);
            $valuePlaceholders = implode(',', array_fill(0, count($params), '?'));

            $sql = "INSERT INTO $tableName ($fields) VALUES ($valuePlaceholders);";
        }

        $affectedRows = $this->query($sql)->execute($params);

        if($affectedRows)
        {
            if ($item->{$this->ManagedObject->getIdProperty()} == 0) {
                $item->{$this->ManagedObject->getIdProperty()} = $this->Handler->lastInsertId();
            }

            $this->addToCache($item);
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
            $columns = [];
            $values = [];
            foreach ($fields as $i => $field) {
                $columns[] = ' ' . $this->ManagedObject->getPropertyMapping($field) . ' = ? ';
                $values[] = $arguments[$i];
            }

            $sql .= implode(' AND ', $columns);
            $sql .= <<<EOQ
ORDER BY
    $sortColumn
EOQ;

            $items = array();

            foreach($this->query($sql)->all($values) as $row) {
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

            $columns = [];
            $values = [];
            foreach ($fields as $i => $field) {
                $columns[] = ' ' . SlugHelper::fromPropertyToName($field) . ' = ? ';
                $values[] = $arguments[$i];
            }

            $sql .= implode(' AND ', $columns);

            $oItem = new $fullClassName();

            $result = $this->query($sql)->single($values);

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
     * @return string
     */
    public function processSelect($table, $select)
    {
        $sql = "";
        if ($table != '') {
            $managedObject = $this->ManagedObject->getManagedObject($table);
        }
        foreach ($select as $key => $linealue) {
            if (is_array($linealue)) {
                $sql .= $this->processSelect($key, $linealue);
            } else {
                // table name in field
                if ($table == '') {
                    $managedObject = $this->ManagedObject->getManagedObject($key);
                    /* @var DataModel $managedObject */
                    $sql .= ' ' . $managedObject->getTableName() . ".*,\n";
                } else {
                    /* @var DataModel $managedObject */
                    $columnName = $managedObject->getPropertyMapping($linealue);
                    $sql .= ' ' . $managedObject->getTableName() . '.' . $columnName . ",\n";
                }
            }
        }
        return $sql;
    }

    /**
     * @param DataModel $item
     * @return string
     */
    public function removeFromCache(DataModel $item)
    {
        $idProperty = $item->getIdProperty();
        if (isset(RepositoryManager::$cache[$this->ManagedObject->getRepositoryClass()][$item->$idProperty])) {
            unset(RepositoryManager::$cache[$this->ManagedObject->getRepositoryClass()][$item->$idProperty]);
        }
        return $idProperty;
    }

    /**
     * @param $oItem
     */
    public function addToCache($oItem)
    {
        $idProp = $this->ManagedObject->getIdProperty();
        RepositoryManager::$cache[$this->ManagedObject->getRepositoryClass()][$oItem->$idProp] = clone $oItem;
    }

    /**
     * Returns a comma separated list of ? placeholders for queries for a list of array values
     *
     * @param $values
     * @return string
     */
    protected function buildPlaceholdersForValues($values)
    {
        if (!is_array($values)) {
            return '?';
        }
        return implode(',', array_fill(0, count($values), '?'));
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
                $rootObject = $this->ManagedObject->getManagedObject($key);
                /* @var DataModel $rootObject */
                $sql .= ' ' . $rootObject->getTableName() . " \n";
                $sql .= $this->processFrom($key, $table);
            } else {
                if ($table != '') {
                    $sourceObject = $this->ManagedObject->getManagedObject($source);
                    $sql .= ' LEFT JOIN ' . $sourceObject->getJoin($table) . " \n";
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
