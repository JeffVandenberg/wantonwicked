<?php
/**
 * Created by PhpStorm.
 * User: jvandenberg
 * Date: 9/10/2015
 * Time: 12:24 AM
 */
namespace classes\integration;

class WikiInformation
{
    private static $upName;
    private static $loginOut;
    private static $ucp;

    public static function setUpName($upName)
    {
        self::$upName = $upName;
    }

    public static function getUpName()
    {
        return self::$upName;
    }

    public static function setLoginOut($loginOut)
    {
        self::$loginOut = $loginOut;
    }

    public static function getLoginOut()
    {
        return self::$loginOut;
    }

    public static function setUcp($ucp)
    {
        self::$ucp = $ucp;
    }

    public static function getUcp()
    {
        return self::$ucp;
    }
}