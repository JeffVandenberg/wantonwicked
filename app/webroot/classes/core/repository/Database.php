<?php
/**
 * Created by JetBrains PhpStorm.
 * User: jvandenberg
 * Date: 8/8/13
 * Time: 8:16 AM
 * To change this template use File | Settings | File Templates.
 */

namespace classes\core\repository;

use Exception;
use PDO;
use PDOException;
use PDOStatement;

/**
 * Class Database
 * Provides an abstraction wrapper and core defaults for accessing the auxiant websites
 * @package classes\core\data_access
 */
class Database
{
    private static $instance;
    /**
     * Maintains a count of transaction count level
     * @var int
     */
    private $TransactionCounter = 0;
    /**
     * Current Prepared Statement
     * @var PDOStatement
     */
    protected $Statement;
    /**
     * Current Connection
     * @var PDO
     */
    protected $Handler;

    /**
     * Build the initial connection to the database
     * @param array|null $connection Connection Information.
     * @throws PDOException
     */
    function __construct($connection = null)
    {
        $params = ($connection == null) ? DatabaseMapper::GetPrimary() : $connection;
        try {
            $this->Handler = new PDO(
                'mysql:host=' . $params['host'] . ';dbname=' . $params['db'],
                $params['user'],
                $params['pass'],
                array(PDO::ATTR_PERSISTENT => true)
            );
            $this->Handler->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $pdoException) {
            var_dump($pdoException);
            die();
            //ErrorHelper::HandleError($pdoException, debug_backtrace());
        }
    }

    /**
     * Switch the database connection to a new database
     * @param $connection
     * @return $this
     */
    public function SetConnection($connection)
    {
        try {
            $this->Handler = new PDO(
                'mysql:host=' . $connection['host'] . ';dbname=' . $connection['db'],
                $connection['user'],
                $connection['pass'],
                array(PDO::ATTR_PERSISTENT => true)
            );
            $this->Handler->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $pdoException) {
            var_dump($pdoException);
            die();
            //ErrorHelper::HandleError($pdoException, debug_backtrace());
        }
        return $this;
    }

    /**
     * Clean up the database connection and ensure that we are not
     * inside of any transactions
     */
    function __destruct()
    {
        if ($this->TransactionCounter > 0) {
            $this->Handler->rollBack();
            throw new Exception('Failed to commit a transaction that was explicitly started.');
        }
        $this->Handler = null;
        self::$instance = null;
    }

    /**
     * Prepares a query to be executed
     * @param $query
     * @return $this
     */
    public function Query($query)
    {
        try{
            $this->Statement = $this->Handler->prepare($query);
        }
        catch(PDOException $pdoException)
        {
            var_dump($pdoException);
            die();
            //ErrorHelper::HandleError($pdoException, debug_backtrace());
        }

        return $this;
    }

    /**
     * Bind Value to Query
     * @param $position
     * @param $value
     * @param null|int $type
     * @return $this
     */
    public function Bind($position, $value, $type = null)
    {
        if (is_null($type)) {
            switch (true) {
                case is_int($value):
                    $type = PDO::PARAM_INT;
                    break;
                case is_bool($value):
                    $type = PDO::PARAM_BOOL;
                    break;
                case is_null($value):
                    $type = PDO::PARAM_NULL;
                    break;
                default:
                    $type = PDO::PARAM_STR;
            }
        }

        try{
            $this->Statement->bindValue($position, $value, $type);
        }
        catch(PDOException $pdoException)
        {
            var_dump($pdoException);
            die();
            //ErrorHelper::HandleError($pdoException, debug_backtrace());
        }
        return $this;
    }

    /**
     * Execute Query. Exposed for Insert/Update/Delete queries
     * @param null|array $parameters
     * @return int
     */
    public function Execute($parameters = null)
    {
        try{
            if($this->Statement->execute($parameters)) {
                return $this->Statement->rowCount();
            }
        }
        catch(PDOException $pdoException)
        {
            var_dump($pdoException);
            die();
            //ErrorHelper::HandleError($pdoException, debug_backtrace());
        }
        return 0;
    }

    /**
     * Returns all records as an array
     * @param null $parameters
     * @param int $fetchMode
     * @return array
     */
    public function All($parameters = null, $fetchMode = PDO::FETCH_ASSOC)
    {
        try{
            $this->Statement->execute($parameters);
            return $this->Statement->fetchAll($fetchMode);
        }
        catch(PDOException $pdoException)
        {
            var_dump($pdoException);
            die();
            //ErrorHelper::HandleError($pdoException, debug_backtrace());
        }
        return null;
    }

    /**
     * Returns the first record as an array
     * @param null $parameters
     * @param int $fetchMode
     * @return mixed
     */
    public function Single($parameters = null, $fetchMode = PDO::FETCH_ASSOC)
    {
        try{
            $this->Statement->execute($parameters);
            return $this->Statement->fetch($fetchMode);
        }
        catch(PDOException $pdoException)
        {
            var_dump($pdoException);
            die();
            //ErrorHelper::HandleError($pdoException, debug_backtrace());
        }
        return false;
    }

    /**
     * Fetch a result as the specified object
     * @param string $class
     * @return mixed
     */
    public function Object($class)
    {
        try{
            $this->Statement->execute();
            return $this->Statement->fetchObject($class);
        }
        catch(PDOException $pdoException)
        {
            var_dump($pdoException);
            die();
            //ErrorHelper::HandleError($pdoException, debug_backtrace());
        }
        return null;
    }

    /**
     * Starts a new transaction or increase nesting level of transaction
     * @return $this
     */
    public function StartTransaction()
    {
        // start transaction and increment transaction count
        if ($this->Handler->beginTransaction()) {
            $this->TransactionCounter++;
        }
        return $this;
    }

    /**
     * Escapes out of all transaction levels. Rolling back all changes.
     * @return $this
     */
    public function RollBackTransaction()
    {
        // only rollback if we've started any transactions
        if ($this->TransactionCounter > 0) {
            if ($this->Handler->rollBack()) {
                $this->TransactionCounter = 0;
            }
        }
        return $this;
    }

    /**
     * Escape from one transaction level. Committing only if transaction level is 0.
     * @return $this
     */
    public function CommitTransaction()
    {
        // decrement transaction counter
        $this->TransactionCounter--;

        // commit if we're out of all transactions
        if ($this->TransactionCounter == 0) {
            $this->Handler->commit();
        }
        return $this;
    }

    public static function GetInstance() {
        if(self::$instance == null) {
            self::$instance = new Database();
        }
        return self::$instance;
    }

    public function Value($parameters)
    {
        $this->Statement->execute($parameters);
        return $this->Statement->fetchColumn();
    }

    public function GetInsertId() {
        return $this->Handler->lastInsertId();
    }
}