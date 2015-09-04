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
        return $user->CheckUserPermission($userId, $SitePermissionId);
    }

    public function IsST() {
        $userdata = AuthComponent::user();
        App::uses('SitePermission', 'Model');
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
        App::uses('SitePermission', 'Model');
        return $this->CheckSitePermission($userdata['user_id'], array(
            SitePermission::$WikiManager
        ));
    }

    public function IsHead()
    {
        $userdata = AuthComponent::user();
        App::uses('SitePermission', 'Model');
        return $this->CheckSitePermission($userdata['user_id'], array(
            SitePermission::$IsHead,
            SitePermission::$IsAdmin,
        ));
    }

    public function IsAdmin()
    {
        $userdata = AuthComponent::user();
        App::uses('SitePermission', 'Model');
        return $this->CheckSitePermission($userdata['user_id'], array(
            SitePermission::$IsAdmin,
        ));
    }

    public function IsSupporter($userId)
    {
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