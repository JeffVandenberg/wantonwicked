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

    public function CheckSitePermission($userId, $SitePermissionId)
    {
        App::uses('User', 'Model');
        $user = new User();
        return $user->CheckUserSitePermission($userId, $SitePermissionId);
    }

    public function IsST() {
        $userdata = AuthComponent::user();
        return ($userdata['is_asst'] || $userdata['is_gm'] || $userdata['is_head'] || $userdata['is_admin']);
        App::uses('SiteSitePermission', 'Model');
        return $this->CheckSitePermission($userdata['user_id'], array(
            SitePermission::$IsAsst,
            SitePermission::$IsST,
            SitePermission::$IsHead,
            SitePermission::$IsAdmin,
        ));
    }

    public function IsWikiManager()
    {
        $userdata = AuthComponent::user();
        return $userdata['wiki_manager'];
        App::uses('SitePermission', 'Model');
        return $this->CheckSitePermission($userdata['user_id'], array(
            SitePermission::$WikiManager
        ));
    }

    public function IsHead()
    {
        $userdata = AuthComponent::user();
        return ($userdata['is_head'] || $userdata['is_admin']);
        App::uses('SitePermission', 'Model');
        return $this->CheckSitePermission($userdata['user_id'], array(
            SitePermission::$IsHead,
            SitePermission::$IsAdmin,
        ));
    }

    public function IsAdmin()
    {
        $userdata = AuthComponent::user();
        return ($userdata['is_admin']);
        App::uses('SitePermission', 'Model');
        return $this->CheckSitePermission($userdata['user_id'], array(
            SitePermission::$IsAdmin,
        ));
    }

    public function IsSupporter($userId)
    {
        $userdata = AuthComponent::user();
        return ($userdata['is_supporter']);
        App::uses('User', 'Model');
        $user = new User();
        return $user->CheckUserSupporterStatus($userId);
    }

    public function MayEditCharacter($characterId)
    {
        App::uses('Character', 'Model');
        $characterRepo = new Character();

        $character = $characterRepo->find('first', array(
            'conditions' => array(
                'Character.id' => $characterId
            ),
            'fields' => array(
                'user_id'
            ),
            'contain' => false
        ));

         return $character['Character']['user_id'] == AuthComponent::user('user_id');
    }
}