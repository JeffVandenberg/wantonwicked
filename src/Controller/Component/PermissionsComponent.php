<?php
/**
 * Created by JetBrains PhpStorm.
 * User: JeffVandenberg
 * Date: 5/25/13
 * Time: 11:37 AM
 * To change this template use File | Settings | File Templates.
 */
namespace App\Controller\Component;


use app\Model\Character;
use App\Model\Entity\Permission;
use App\Model\Table\UsersTable;
use Cake\Controller\Component;
use App\Model\User;
use Cake\ORM\TableRegistry;

/**
 * @property Component\AuthComponent Auth
 */
class PermissionsComponent extends Component
{
    public $components = [
        'Auth'
    ];

    public function CheckSitePermission($userId, $SitePermissionId)
    {
        $userTable = TableRegistry::get('Users');
        /* @var UsersTable $userTable */
        return $userTable->checkUserPermission($userId, $SitePermissionId);
    }

    public function IsST()
    {
        $userdata = $this->Auth->user();
        return $this->CheckSitePermission($userdata['user_id'], array(
            Permission::$IsAsst,
            Permission::$IsST,
            Permission::$IsHead,
            Permission::$IsAdmin,
        ));
    }

    public function IsWikiManager()
    {
        $userdata = $this->Auth->user();

        return $this->CheckSitePermission($userdata['user_id'], array(
            Permission::$WikiManager
        ));
    }

    public function IsHead()
    {
        $userdata = $this->Auth->user();

        return $this->CheckSitePermission($userdata['user_id'], array(
            Permission::$IsHead,
            Permission::$IsAdmin,
        ));
    }

    public function IsAdmin()
    {
        $userdata = $this->Auth->user();

        return $this->CheckSitePermission($userdata['user_id'], array(
            Permission::$IsAdmin,
        ));
    }

    public function IsSupporter($userId)
    {
        $user = new User();
        return $user->CheckUserSupporterStatus($userId);
    }

    public function MayEditCharacter($characterId)
    {
        $character = TableRegistry::get('Characters')->get($characterId);
        return
            (
                $character->user_id == $this->Auth->user('user_id')
                ||
                $this->CheckSitePermission($this->Auth->user('user_id'), Permission::$ManageCharacters)
            );
    }
}
