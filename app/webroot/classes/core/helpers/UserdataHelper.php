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
use classes\support\repository\SupporterRepository;

class UserdataHelper
{
    private static $permissions = array(
        'is_admin' => 1,
        'is_head' => 2,
        'is_gm' => 3,
        'is_asst' => 4,
        'wiki_manager' => 5
    );

    private static $userPermissions = array();

    public static function CheckPermission($userId, $userPerms)
    {
        if(!is_array($userPerms)) {
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

            self::$userPermissions[$key] = (Database::GetInstance()->Query($sql)->Value($params) > 0);
        }
        return self::$userPermissions[$key];
    }

    public static function IsSt($userdata)
    {
        if(!isset(self::$userPermissions['IsSt'])) {
            self::$userPermissions['IsSt'] = self::CheckPermission($userdata['user_id'], array(
                self::$permissions['is_asst'],
                self::$permissions['is_gm'],
                self::$permissions['is_head'],
                self::$permissions['is_admin']
            ));
        }
        return self::$userPermissions['IsSt'];
    }

    public static function IsWikiManager($userdata)
    {
        if(!isset(self::$userPermissions['IsWikiManager'])) {
            self::$userPermissions['IsWikiManager'] = self::CheckPermission($userdata['user_id'], array(
                self::$permissions['wiki_manager']
            ));
        }
        return self::$userPermissions['IsWikiManager'];
    }

    public static function IsHead($userdata)
    {
        if(!isset(self::$userPermissions['IsHead'])) {
            self::$userPermissions['IsHead'] = self::CheckPermission($userdata['user_id'], array(
                self::$permissions['is_head'],
                self::$permissions['is_admin']
            ));
        }
        return self::$userPermissions['IsHead'];
    }

    public static function IsAdmin($userdata)
    {
        if(!isset(self::$userPermissions['IsAdmin'])) {
            self::$userPermissions['IsAdmin'] = self::CheckPermission($userdata['user_id'], array(
                self::$permissions['is_admin']
            ));
        }
        return self::$userPermissions['IsAdmin'];
    }

    public static function IsSupporter($userdata)
    {
        if(!isset(self::$userPermissions['IsSupporter'])) {
            $supporterRepository = new SupporterRepository();
            self::$userPermissions['IsSupporter'] =
                $supporterRepository->CheckIsCurrentSupporter($userdata['user_id']);;
        }
        return self::$userPermissions['IsSupporter'];
    }

    public static function IsLoggedIn($userdata)
    {
        return (($userdata != null) && ($userdata['user_id'] != 1));
    }

    public static function IsAsst($userdata)
    {
        if(!isset(self::$userPermissions['IsAsst'])) {
            self::$userPermissions['IsAsst'] = self::CheckPermission($userdata['user_id'], array(
                self::$permissions['is_asst']
            ));
        }
        return self::$userPermissions['IsAsst'];
    }

    public static function IsOnlySt($userdata)
    {
        if(!isset(self::$userPermissions['IsOnlySt'])) {
            self::$userPermissions['IsOnlySt'] = self::CheckPermission($userdata['user_id'], array(
                self::$permissions['is_gm']
            ));
        }
        return self::$userPermissions['IsOnlySt'];
    }
}