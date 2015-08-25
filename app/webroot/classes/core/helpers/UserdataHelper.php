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

    public static function CheckPermission($userId, $userPerms)
    {
        if(!is_array($userPerms)) {
            $userPerms = array($userPerms);
        }

        $placeholders = implode(',', array_fill(0, count($userPerms), '?'));

        $sql = <<<EOQ
SELECT
    count(*)
FROM
    from permissions_users
WHERE
    user_id = ?
    AND permission_id IN ($placeholders)
EOQ;
        $params = array_merge(array($userId), $userPerms);

        return (Database::GetInstance()->Query($sql)->Value($params) > 0);
    }

    public static function IsSt($userdata)
    {
        return ($userdata['is_asst'] || $userdata['is_gm'] || $userdata['is_head'] || $userdata['is_admin']);
        return self::CheckPermission($userdata['user_id'], array(
            self::$permissions['is_asst'],
            self::$permissions['is_gm'],
            self::$permissions['is_head'],
            self::$permissions['is_admin']
        ));
    }

    public static function IsWikiManager($userdata)
    {
        return $userdata['wiki_manager'];
        return self::CheckPermission($userdata['user_id'], array(
            self::$permissions['wiki_manager']
        ));
    }

    public static function IsHead($userdata)
    {
        return ($userdata['is_head'] || $userdata['is_admin']);
        return self::CheckPermission($userdata['user_id'], array(
            self::$permissions['is_head'],
            self::$permissions['is_admin']
        ));
    }

    public static function IsAdmin($userdata)
    {
        return ($userdata['is_admin']);
        return self::CheckPermission($userdata['user_id'], array(
            self::$permissions['is_admin']
        ));
    }

    public static function IsSupporter($userdata)
    {
        return ($userdata['is_supporter']);
        $supporterRepository = new SupporterRepository();
        return $supporterRepository->CheckIsCurrentSupporter($userdata['user_id']);
    }
}