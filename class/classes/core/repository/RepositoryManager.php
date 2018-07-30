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
    public static function getRepository($className): AbstractRepository
    {
        $obj = new $className();
        /* @var DataModel $obj */
        $repositoryClass = $obj->getRepositoryClass();

        if(!isset(self::$repositories[$repositoryClass])) {
            if(\defined('ROOT')) {
                $path = ROOT . '/class/' . str_replace('\\', DIRECTORY_SEPARATOR, $repositoryClass);
            } else {
                $path = ROOT_PATH . '../class/' . str_replace('\\', DIRECTORY_SEPARATOR, $repositoryClass);
            }
            if (file_exists($path . '.php')) {
                $repository = new $repositoryClass();
            } else {
                $repository = new BasicRepository($className);
            }
            self::$repositories[$repositoryClass] = $repository;
        }
        return self::$repositories[$repositoryClass];
    }

    public static function clearCache(): void
    {
        self::$cache = null;
    }
}
