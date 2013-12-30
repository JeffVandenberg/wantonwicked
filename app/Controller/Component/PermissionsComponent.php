<?php
/**
 * Created by JetBrains PhpStorm.
 * User: JeffVandenberg
 * Date: 5/25/13
 * Time: 11:37 AM
 * To change this template use File | Settings | File Templates.
 */

App::uses('Component', 'Controller');

class PermissionsComponent extends Component {
    public $uses = array('User');

    public function CheckPermission($userId, $permissionId)
    {
        App::uses('User', 'Model');
        $user = new User();
        return $user->CheckUserPermission($userId, $permissionId);
    }

    public function IsST() {
        $userdata = AuthComponent::user();
        return ($userdata['is_asst'] || $userdata['is_gm'] || $userdata['is_head'] || $userdata['is_admin']);
    }

    public static function IsWikiManager()
    {
        $userdata = AuthComponent::user();
        return $userdata['wiki_manager'];
    }

    public static function IsHead()
    {
        $userdata = AuthComponent::user();
        return ($userdata['is_head'] || $userdata['is_admin']);
    }

    public static function IsAdmin()
    {
        $userdata = AuthComponent::user();
        return ($userdata['is_admin']);
    }
}