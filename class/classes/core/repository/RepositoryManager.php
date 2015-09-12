<?php
/**
 * Created by JetBrains PhpStorm.
 * User: jvandenberg
 * Date: 8/21/13
 * Time: 1:55 PM
 * To change this template use File | Settings | File Templates.
 */

namespace classes\core\repository;

use classes\core\data\DataModel;

class RepositoryManager
{
    public static $repositories;
    public static $cache;

    /**
     * @param $className
     * @return AbstractRepository
     */
    public static function GetRepository($className)
    {
        $obj = new $className();
        /* @var DataModel $obj */
        $repositoryClass = $obj->getRepositoryClass();

        if(!isset(self::$repositories[$repositoryClass])) {
            try {
                $repository = new $repositoryClass();
            } catch (\Exception $e) {
                $repository = new BasicRepository($className);
            }
            self::$repositories[$repositoryClass] = $repository;
        }
        return self::$repositories[$repositoryClass];
    }

    public static function ClearCache()
    {
        self::$cache = null;
    }
}