<?php
namespace classes\core\helpers;

class SlugHelper
{
    public static function FromPropertyToName($sName)
    {
        return substr(preg_replace_callback("/[A-Z]/", create_function('$matches', 'return \'_\' . strtolower($matches[0]);'), $sName), 1);
    }

    public static function FromNameToProperty($sName)
    {
        $sName = "_$sName";
        return preg_replace_callback("/_[a-z]/", create_function('$matches', 'return substr(strtoupper($matches[0]), 1);'), $sName);
    }

    public static function FromClassToTable($className)
    {
        if(substr($className, -1) == 'y') {
            $className = substr($className, 0, strlen($className) -1) . 'ies';
        }
        else if(substr($className, -1) == 's') {
            $className = substr($className, 0, strlen($className) -1) . 'es';
        }
        else {
            $className .= 's';
        }

        return self::FromPropertyToName($className);
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

        return self::FromNameToProperty($tableName);
    }

    public static function FromNameToLabel($name)
    {
        $name = "_$name";
        return preg_replace_callback("/_[a-z]/", create_function('$matches', 'return " " . substr(strtoupper($matches[0]), 1);'), $name);
    }
}