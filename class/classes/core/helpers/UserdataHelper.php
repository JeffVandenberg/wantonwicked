<?php
/**
 * Created by JetBrains PhpStorm.
 * User: JeffVandenberg
 * Date: 8/18/13
 * Time: 6:01 PM
 * To change this template use File | Settings | File Templates.
 */

namespace classes\core\helpers;


use classes\core\repository\Database;

class UserdataHelper
{
    private static $permissions = array(
        'is_admin' => 1,
        'is_head' => 2,
        'is_gm' => 3,
        'is_asst' => 4,
        'wiki_manager' => 5,
        'manage_requests' => 6,
        'manage_characters' => 7,
        'manage_scenes' => 8
    );

    private static $userPermissions = array();

    public static function checkPermission($userId, $userPerms)
    {
        if(!\is_array($userPerms)) {
            $userPerms = array($userPerms);
        }

        $placeholders = implode(',', array_fill(0, count($userPerms), '?'));
        sort($userPerms);
        $key = implode('-', $userPerms);

        if(!isset(self::$userPermissions[$key])) {
            $sql = <<<EOQ
SELECT
    count(*)
FROM
    permissions_users
WHERE
    user_id = ?
    AND permission_id IN ($placeholders)
EOQ;
            $params = array_merge(array($userId), $userPerms);

            self::$userPermissions[$key] = (Database::getInstance()->query($sql)->value($params) > 0);
        }
        return self::$userPermissions[$key];
    }

    public static function isSt($userdata)
    {
        if(!isset(self::$userPermissions['IsSt'])) {
            self::$userPermissions['IsSt'] = self::checkPermission($userdata['user_id'], array(
                self::$permissions['is_asst'],
                self::$permissions['is_gm'],
                self::$permissions['is_head'],
                self::$permissions['is_admin']
            ));
        }
        return self::$userPermissions['IsSt'];
    }

    public static function isWikiManager($userdata)
    {
        if(!isset(self::$userPermissions['IsWikiManager'])) {
            self::$userPermissions['IsWikiManager'] = self::checkPermission($userdata['user_id'], array(
                self::$permissions['wiki_manager']
            ));
        }
        return self::$userPermissions['IsWikiManager'];
    }

    public static function isHead($userdata)
    {
        if(!isset(self::$userPermissions['IsHead'])) {
            self::$userPermissions['IsHead'] = self::checkPermission($userdata['user_id'], array(
                self::$permissions['is_head'],
                self::$permissions['is_admin']
            ));
        }
        return self::$userPermissions['IsHead'];
    }

    public static function isAdmin($userdata)
    {
        if(!isset(self::$userPermissions['IsAdmin'])) {
            self::$userPermissions['IsAdmin'] = self::checkPermission($userdata['user_id'], array(
                self::$permissions['is_admin']
            ));
        }
        return self::$userPermissions['IsAdmin'];
    }

    public static function isLoggedIn($userdata): bool
    {
        return (($userdata !== null) && ((int)$userdata['user_id'] !== 1));
    }

    public static function isAsst($userdata)
    {
        if(!isset(self::$userPermissions['IsAsst'])) {
            self::$userPermissions['IsAsst'] = self::checkPermission($userdata['user_id'], array(
                self::$permissions['is_asst']
            ));
        }
        return self::$userPermissions['IsAsst'];
    }

    public static function isOnlySt($userdata)
    {
        if(!isset(self::$userPermissions['IsOnlySt'])) {
            self::$userPermissions['IsOnlySt'] = self::checkPermission($userdata['user_id'], array(
                self::$permissions['is_gm']
            ));
        }
        return self::$userPermissions['IsOnlySt'];
    }

    public static function mayManageRequests($userdata)
    {
        if(!isset(self::$userPermissions['ManageRequests'])) {
            self::$userPermissions['ManageRequests'] = self::checkPermission($userdata['user_id'], array(
                self::$permissions['manage_requests']
            ));
        }
        return self::$userPermissions['ManageRequests'];
    }
}
