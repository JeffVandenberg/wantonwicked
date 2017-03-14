<?php
/**
 * Created by JetBrains PhpStorm.
 * User: JeffVandenberg
 * Date: 5/25/13
 * Time: 11:37 AM
 * To change this template use File | Settings | File Templates.
 */namespace app\Controller\Component;



use Cake\Controller\Component;

class PermissionsComponent extends Component {
    public $uses = array('User');

    public function CheckSitePermission($userId, $SitePermissionId)
    {
        use App\Model\User;
        $user = new User();
        return $user->CheckUserPermission($userId, $SitePermissionId);
    }

    public function IsST() {
        $userdata = AuthComponent::user();
        use App\Model\SitePermission;
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
        use App\Model\SitePermission;
        return $this->CheckSitePermission($userdata['user_id'], array(
            SitePermission::$WikiManager
        ));
    }

    public function IsHead()
    {
        $userdata = AuthComponent::user();
        use App\Model\SitePermission;
        return $this->CheckSitePermission($userdata['user_id'], array(
            SitePermission::$IsHead,
            SitePermission::$IsAdmin,
        ));
    }

    public function IsAdmin()
    {
        $userdata = AuthComponent::user();
        use App\Model\SitePermission;
        return $this->CheckSitePermission($userdata['user_id'], array(
            SitePermission::$IsAdmin,
        ));
    }

    public function IsSupporter($userId)
    {
        use App\Model\User;
        $user = new User();
        return $user->CheckUserSupporterStatus($userId);
    }

    public function MayEditCharacter($characterId)
    {
        use App\Model\Character;
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

         return
             (
                 $character['Character']['user_id'] == AuthComponent::user('user_id')
                 ||
                 $this->CheckSitePermission(AuthComponent::user('user_id'), SitePermission::$ManageCharacters)
             );
    }
}
