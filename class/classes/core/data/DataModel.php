<?php
namespace classes\core\data;

use classes\core\helpers\SlugHelper;
use classes\core\repository\DatabaseMapper;
use classes\core\repository\RepositoryManager;
use Exception;

/**
 * Class DataModel
 * @package classes\core\data
 */
abstract class DataModel
{
    /**
     * @var array
     */
    public $HasMany = array();
    /**
     * @var array
     */
    public $HasOne = array();
    /**
     * @var array
     */
    public $BelongsTo = array();
    /**
     * @var array
     */
    protected $Mapping = array();

    /**
     * Fields in the model that don't map to the database
     * @var array
     */
    protected $Unmapped = array();

    /**
     * Fields to create/set based on other fields in the model
     * @var array
     */
    protected $VirtualField = array();

    /**
     * @var string
     */
    protected $Database;
    /**
     * @var string
     */
    protected $FullClassName;
    /**
     * @var string
     */
    protected $ClassName;
    /**
     * @var string
     */
    protected $TableName;
    /**
     * @var string
     */
    protected $BaseTableName;
    /**
     * @var string
     */
    protected $IdProperty;
    /**
     * @var string
     */
    protected $IdColumn;
    /**
     * @var string
     */
    protected $NameProperty;
    /**
     * @var string
     */
    protected $NameColumn;
    /**
     * @var string
     */
    protected $SortColumn;
    /**
     * @var string
     */
    protected $RepositoryClass;

    /**
     * @var
     */
    protected $ForeignId;

    public function __construct($tablePrefix = '', $database = null)
    {
        if($database === null) {
            $params = DatabaseMapper::getPrimary();
            $database = $params['db'];
        }
        $this->Database = $database;
        $this->FullClassName = get_class($this);
        $this->ClassName = substr($this->FullClassName, strrpos($this->FullClassName, '\\') + 1);
        $this->TableName = $this->Database . '.' . $tablePrefix . SlugHelper::fromClassToTable($this->ClassName);

        $nameSpace = substr($this->FullClassName, 0, strrpos($this->FullClassName, '\\'));
        $commonSpace = substr($nameSpace, 0, strrpos($nameSpace, '\\'));
        $this->RepositoryClass = $commonSpace . '\repository\\' . $this->ClassName . 'Repository';

        $this->IdProperty = 'Id';
        $this->NameProperty = 'Name';

        $this->initializeDerivedValues();
    }

    public function __get($property)
    {
        if($this->findMappedObject($property, $this->HasMany)) {
            $targetModel = $this->getMappedObject($property, $this->HasMany);
            /* @var DataModel $targetModel */

            $targetRepository = RepositoryManager::getRepository($targetModel->getFullClassName());

            $method = 'ListBy' . $this->getForeignIdProperty();
            $selfIdColumn = $this->getIdProperty();
            $this->$property = $targetRepository->$method($this->$selfIdColumn);
            return $this->$property;
        }

        if($this->findMappedObject($property, $this->HasOne)) {
            $targetModel = $this->getMappedObject($property, $this->HasOne);
            /* @var DataModel $targetModel */

            $targetRepository = RepositoryManager::getRepository($targetModel->getFullClassName());
            $method = 'FindBy' . $this->getIdProperty();

            $selfIdColumn = $this->getIdProperty();
            $this->$property = $targetRepository->$method($this->$selfIdColumn);
            return $this->$property;
        }

        if($this->findMappedObject($property, $this->BelongsTo)) {
            if(!isset($this->$property)) {
                $targetModel = $this->getMappedObject($property, $this->BelongsTo);
                /* @var DataModel $targetModel */

                $targetRepository = RepositoryManager::getRepository($targetModel->getFullClassName());

                $method = 'GetById';
                $idColumn = $property . 'Id';

                $this->$property = $targetRepository->$method($this->$idColumn);
            }
            return $this->$property;
        }
        return null;
    }

    /**
     * Map from Object Property To Column Name
     * @param string $propertyName
     * @return string
     */
    public function getPropertyMapping($propertyName)
    {
        if(isset($this->Mapping[$propertyName]))
        {
            return $this->Mapping[$propertyName];
        }
        else
        {
            return SlugHelper::fromPropertyToName($propertyName);
        }
    }


    /**
     * Map from Column Name To Table Name
     * @param $columnName
     * @return mixed
     */
    public function getColumnMapping($columnName)
    {
        if(in_array($columnName, $this->Mapping))
        {
            return array_search($columnName, $this->Mapping);
        }
        else
        {
            return SlugHelper::fromNameToProperty($columnName);
        }
    }

    public function __set($sString, $vValue)
    {
        $this->$sString = $vValue;
    }

    /**
     * @param string $model
     * @return string
     */
    private function getClass($model)
    {
        $class = get_class($this);
        $nameSpace = substr($class, 0, strrpos($class, '\\') + 1);
        return $nameSpace . $model;
    }

    /**
     * @return string
     */
    public function getRepositoryClass()
    {
        return $this->RepositoryClass;
    }

    /**
     * @return string
     */
    public function getIdProperty()
    {
        return $this->IdProperty;
    }

    /**
     * @return string
     */
    public function getBaseTableName()
    {
        return $this->BaseTableName;
    }

    /**
     * @return string
     */
    public function getClassName()
    {
        return $this->ClassName;
    }

    /**
     * @return string
     */
    public function getDatabase()
    {
        return $this->Database;
    }

    /**
     * @return string
     */
    public function getFullClassName()
    {
        return $this->FullClassName;
    }

    /**
     * @return string
     */
    public function getIdColumn()
    {
        return $this->IdColumn;
    }

    /**
     * @return string
     */
    public function getNameColumn()
    {
        return $this->NameColumn;
    }

    /**
     * @return string
     */
    public function getSortColumn()
    {
        $sort = $this->SortColumn;
        if(is_array($this->SortColumn))
        {
            $sort = implode(',', $sort);
        }
        return $sort;
    }

    /**
     * @return string
     */
    public function getNameProperty()
    {
        return $this->NameProperty;
    }

    /**
     * @return string
     */
    public function getTableName()
    {
        return $this->TableName;
    }

    private function findMappedObject($object, $list)
    {
        return (isset($list[$object]) || array_search($object, $list) !== false);
    }

    private function getMappedObject($object, $list)
    {
        if (isset($list[$object]) || array_search($object, $list) !== false) {
            if (isset($list[$object])) {
                $modelClass = $list[$object];
            } else {
                $modelClass = $this->getClass($object);
            }

            $targetModel = new $modelClass();
            /* @var DataModel $targetModel */
            return $targetModel;
        }
        return null;
    }

    /**
     * @param $object
     * @throws Exception
     * @return DataModel
     */
    public function getManagedObject($object)
    {
        if($object == $this->ClassName)
        {
            $className = $this->getFullClassName();
            return new $className;
        }
        else
        {
            if($this->findMappedObject($object, $this->HasMany))
            {
                $mappedObject = $this->getMappedObject($object, $this->HasMany);
                /* @var DataModel $mappedObject */
                return $mappedObject;
            }
            if($this->findMappedObject($object, $this->HasOne))
            {
                $mappedObject = $this->getMappedObject($object, $this->HasOne);
                /* @var DataModel $mappedObject */
                return $mappedObject;
            }
            if($this->findMappedObject($object, $this->BelongsTo))
            {
                $mappedObject = $this->getMappedObject($object, $this->BelongsTo);
                /* @var DataModel $mappedObject */
                return $mappedObject;
            }
        }
        throw new Exception('Unable to map ' . $object .' in DataModel ' . $this->FullClassName);
    }

    public function getJoin($targetObject)
    {
        $targetManagedObject = $this->getManagedObject($targetObject);
        /* @var DataModel $targetManagedObject */
        $join = $targetManagedObject->getTableName();

        if($this->findMappedObject($targetObject, $this->HasMany) || $this->findMappedObject($targetObject, $this->HasOne)) {
            $joinColumn = $this->getIdColumn();
        }
        else {
            $joinColumn = $targetManagedObject->getIdColumn();
        }

        $join .= ' ON ' . $this->TableName . '.' . $joinColumn . ' = ' . $targetManagedObject->TableName . '.' . $joinColumn;

        return $join;
    }

    protected function initializeDerivedValues()
    {
        $this->BaseTableName = SlugHelper::fromPropertyToName($this->ClassName);
        $this->IdColumn = SlugHelper::fromPropertyToName($this->IdProperty);
        $this->ForeignId = $this->ClassName . 'Id';
        $this->NameColumn = SlugHelper::fromPropertyToName($this->NameProperty);
        $this->SortColumn = $this->NameColumn;
    }

    protected function getForeignIdProperty()
    {
        return $this->ForeignId;
    }

}
