<?php
namespace classes\core\helpers;

class SlugHelper
{
    public static function fromPropertyToName($sName)
    {
        return substr(preg_replace_callback("/[A-Z]/",
            function($matches) {return '_' . strtolower($matches[0]);}, $sName), 1);
    }

    public static function fromNameToProperty($sName)
    {
        $sName = "_$sName";
        return preg_replace_callback("/_[a-z]/",
            function($matches) { return substr(strtoupper($matches[0]), 1); }, $sName);
    }

    public static function FromClassToTable($className)
    {
        if(substr($className, -1) == 'y') {
            $className = substr($className, 0, strlen($className) -1) . 'ies';
        }
        else if(substr($className, -1) == 's') {
            $className .= 'es';
        }
        else {
            $className .= 's';
        }

        return self::fromPropertyToName($className);
    }

    public static function FromTableToClass($tableName)
    {
        if(substr($tableName, -3) == 'ies') {
            $tableName =  substr($tableName, 0, strlen($tableName) -3) . 'y';
        }
        else if(substr($tableName, -2) == 'es') {
            $tableName =  substr($tableName, 0, strlen($tableName) -3) . 's';
        }
        else if(substr($tableName, -1) == 's') {
            $tableName = substr($tableName, 0, strlen($tableName) -1);
        }

        return self::fromNameToProperty($tableName);
    }

    public static function FromNameToLabel($name)
    {
        $name = "_$name";
        return preg_replace_callback("/_[a-z]/", function($matches) {return " " . substr(strtoupper($matches[0]), 1);}, $name);
    }
}
