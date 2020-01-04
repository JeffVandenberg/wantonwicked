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
use function is_array;

/**
 * Class UserdataHelper
 * @package classes\core\helpers
 */
class UserdataHelper
{
    /**
     * @var array
     */
    private static $permissions = [
        'is_admin' => 1,
        'is_head' => 2,
        'is_gm' => 3,
        'is_asst' => 4,
        'wiki_manager' => 5,
        'manage_requests' => 6,
        'manage_characters' => 7,
        'manage_scenes' => 8,
    ];

    /**
     * @var array
     */
    private static $userPermissions = [];

    /**
     * @param int $userId User ID to check
     * @param string|array $userPerms string or array of permissions to check for
     * @return mixed
     */
    public static function checkPermission($userId, $userPerms)
    {
        if (!is_array($userPerms)) {
            $userPerms = [$userPerms];
        }

        $placeholders = implode(',', array_fill(0, count($userPerms), '?'));
        sort($userPerms);
        $key = implode('-', $userPerms);

        if (!isset(self::$userPermissions[$key])) {
            $sql = <<<EOQ
SELECT
    count(*)
FROM
    permissions_users
WHERE
    user_id = ?
    AND permission_id IN ($placeholders)
EOQ;
            $params = array_merge([$userId], $userPerms);

            self::$userPermissions[$key] = (Database::getInstance()->query($sql)->value($params) > 0);
        }

        return self::$userPermissions[$key];
    }

    /**
     * @param array $userdata  PHPBB User Data
     * @return mixed
     */
    public static function isSt($userdata)
    {
        if (!isset(self::$userPermissions['IsSt'])) {
            self::$userPermissions['IsSt'] = self::checkPermission($userdata['user_id'], [
                self::$permissions['is_asst'],
                self::$permissions['is_gm'],
                self::$permissions['is_head'],
                self::$permissions['is_admin'],
            ]);
        }

        return self::$userPermissions['IsSt'];
    }

    /**
     * @param array $userdata  PHPBB User Data
     * @return mixed
     */
    public static function isWikiManager($userdata)
    {
        if (!isset(self::$userPermissions['IsWikiManager'])) {
            self::$userPermissions['IsWikiManager'] = self::checkPermission($userdata['user_id'], [
                self::$permissions['wiki_manager'],
            ]);
        }

        return self::$userPermissions['IsWikiManager'];
    }

    /**
     * @param array $userdata  PHPBB User Data
     * @return mixed
     */
    public static function isHead($userdata)
    {
        if (!isset(self::$userPermissions['IsHead'])) {
            self::$userPermissions['IsHead'] = self::checkPermission($userdata['user_id'], [
                self::$permissions['is_head'],
                self::$permissions['is_admin'],
            ]);
        }

        return self::$userPermissions['IsHead'];
    }

    /**
     * @param array $userdata  PHPBB User Data
     * @return mixed
     */
    public static function isAdmin($userdata)
    {
        if (!isset(self::$userPermissions['IsAdmin'])) {
            self::$userPermissions['IsAdmin'] = self::checkPermission($userdata['user_id'], [
                self::$permissions['is_admin'],
            ]);
        }

        return self::$userPermissions['IsAdmin'];
    }

    /**
     * @param array $userdata PHPBB User Data
     * @return bool
     */
    public static function isLoggedIn($userdata): bool
    {
        return (($userdata !== null) && ((int)$userdata['user_id'] !== 1));
    }

    /**
     * @param array $userdata  PHPBB User Data
     * @return mixed
     */
    public static function isAsst($userdata)
    {
        if (!isset(self::$userPermissions['IsAsst'])) {
            self::$userPermissions['IsAsst'] = self::checkPermission($userdata['user_id'], [
                self::$permissions['is_asst'],
            ]);
        }

        return self::$userPermissions['IsAsst'];
    }

    /**
     * @param array $userdata  PHPBB User Data
     * @return mixed
     */
    public static function isOnlySt($userdata)
    {
        if (!isset(self::$userPermissions['IsOnlySt'])) {
            self::$userPermissions['IsOnlySt'] = self::checkPermission($userdata['user_id'], [
                self::$permissions['is_gm'],
            ]);
        }

        return self::$userPermissions['IsOnlySt'];
    }

    /**
     * @param array $userdata  PHPBB User Data
     * @return mixed
     */
    public static function mayManageRequests($userdata)
    {
        if (!isset(self::$userPermissions['ManageRequests'])) {
            self::$userPermissions['ManageRequests'] = self::checkPermission($userdata['user_id'], [
                self::$permissions['manage_requests'],
            ]);
        }

        return self::$userPermissions['ManageRequests'];
    }
}
