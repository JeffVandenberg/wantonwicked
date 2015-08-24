<?php
/**
 * Created by JetBrains PhpStorm.
 * User: JeffVandenberg
 * Date: 8/18/13
 * Time: 6:01 PM
 * To change this template use File | Settings | File Templates.
 */

namespace classes\core\helpers;


class UserdataHelper
{
    public static function IsSt($userdata)
    {
        return ($userdata['is_asst'] || $userdata['is_gm'] || $userdata['is_head'] || $userdata['is_admin']);
    }

    public static function IsWikiManager($userdata)
    {
        return $userdata['wiki_manager'];
    }

    public static function IsHead($userdata)
    {
        return ($userdata['is_head'] || $userdata['is_admin']);
    }

    public static function IsAdmin($userdata)
    {
        return ($userdata['is_admin']);
    }

    public static function IsSupporter($userdata)
    {
        return ($userdata['is_supporter']);
    }

    public static function IsLoggedIn($userdata)
    {
        return ($userdata != null) && ($userdata['user_id'] != 1);
    }
}