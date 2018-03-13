<?php
/**
 * Created by JetBrains PhpStorm.
 * User: JeffVandenberg
 * Date: 5/25/13
 * Time: 11:37 AM
 * To change this template use File | Settings | File Templates.
 */

namespace App\Controller\Component;


use App\Model\Entity\Character;
use App\Model\Entity\Permission;
use App\Model\Table\UsersTable;
use Cake\Controller\Component;
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

    public function MayManageDatabase()
    {
        $userdata = $this->Auth->user();

        return $this->CheckSitePermission($userdata['user_id'], array(
            Permission::$ManageDatabase
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

    public function IsAdmin($userId = null)
    {
        $userId = $userId ?? $this->Auth->user('user_id');
        return $this->CheckSitePermission($userId, array(
            Permission::$IsAdmin,
        ));
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

    public function isPlotManager($userId)
    {
        return $this->CheckSitePermission($userId, Permission::$PlotsManage);
    }

    public function isPlotViewer($userId)
    {
        return $this->CheckSitePermission($userId, Permission::$PlotsView);
    }

    public function isRequestManager($userId = null)
    {
        $userId = $userId ?? $this->Auth->user('user_id');
        return $this->CheckSitePermission($userId, Permission::$ManageRequests);
    }

    public function mayViewCharacter(Character $character, $userId = null)
    {
        $userId = $userId ?? $this->Auth->user('user_id');
        return $character->user_id == $userId || $this->IsAdmin($userId);
    }
}
