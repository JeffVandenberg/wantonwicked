<?php
namespace classes\core\helpers;

class SlugHelper
{
    public static function fromPropertyToName($sName)
    {
        return substr(preg_replace_callback('/[A-Z]/',
            function($matches) {return '_' . strtolower($matches[0]);}, $sName), 1);
    }

    public static function fromNameToProperty($sName)
    {
        $sName = "_$sName";
        return preg_replace_callback('/_[a-z]/',
            function($matches) { return strtoupper(substr($matches[0], 1)); }, $sName);
    }

    public static function fromClassToTable($className)
    {
        if(substr($className, -1) === 'y') {
            $className = substr($className, 0, -1) . 'ies';
        }
        else if(substr($className, -1) === 's') {
            $className .= 'es';
        }
        else {
            $className .= 's';
        }

        return self::fromPropertyToName($className);
    }

    public static function fromTableToClass($tableName)
    {
        if(substr($tableName, -3) === 'ies') {
            $tableName =  substr($tableName, 0, -3) . 'y';
        }
        else if(substr($tableName, -2) === 'es') {
            $tableName =  substr($tableName, 0, -3) . 's';
        }
        else if(substr($tableName, -1) === 's') {
            $tableName = substr($tableName, 0, -1);
        }

        return self::fromNameToProperty($tableName);
    }

    public static function fromNameToLabel($name)
    {
        $name = "_$name";
        return preg_replace_callback('/_[a-z]/', function($matches) {return ' ' . strtoupper(substr($matches[0], 1));}, $name);
    }
}
