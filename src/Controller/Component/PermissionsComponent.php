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

    public function checkSitePermission($userId, $SitePermissionId)
    {
        $userTable = TableRegistry::getTableLocator()->get('Users');
        /* @var UsersTable $userTable */
        return $userTable->checkUserPermission($userId, $SitePermissionId);
    }

    public function isST()
    {
        $userdata = $this->Auth->user();
        return $this->checkSitePermission($userdata['user_id'], array(
            Permission::$IsAsst,
            Permission::$IsST,
            Permission::$IsHead,
            Permission::$IsAdmin,
        ));
    }

    public function isWikiManager()
    {
        $userdata = $this->Auth->user();

        return $this->checkSitePermission($userdata['user_id'], array(
            Permission::$WikiManager
        ));
    }

    public function mayManageDatabase()
    {
        $userdata = $this->Auth->user();

        return $this->checkSitePermission($userdata['user_id'], array(
            Permission::$ManageDatabase
        ));
    }

    public function isHead()
    {
        $userdata = $this->Auth->user();

        return $this->checkSitePermission($userdata['user_id'], array(
            Permission::$IsHead,
            Permission::$IsAdmin,
        ));
    }

    public function isAdmin($userId = null)
    {
        $userId = $userId ?? $this->Auth->user('user_id');
        return $this->checkSitePermission($userId, array(
            Permission::$IsAdmin,
        ));
    }

    public function mayEditCharacter($characterId)
    {
        $character = TableRegistry::getTableLocator()->get('Characters')->get($characterId);
        return
            (
                $character->user_id == $this->Auth->user('user_id')
                ||
                $this->checkSitePermission($this->Auth->user('user_id'), Permission::$ManageCharacters)
            );
    }

    public function isPlotManager($userId = null)
    {
        if(!$userId) {
            $userId = $this->Auth->user('user_id');
        }
        return $this->checkSitePermission($userId, Permission::$PlotsManage);
    }

    public function isPlotViewer($userId = null)
    {
        return $this->checkSitePermission(
            $userId ?? $this->Auth->user('user_id'),
            Permission::$PlotsView
        );
    }

    public function isRequestManager($userId = null)
    {
        return $this->checkSitePermission(
            $userId ?? $this->Auth->user('user_id'),
            Permission::$ManageRequests
        );
    }

    public function mayViewCharacter(Character $character, $userId = null)
    {
        $userId = $userId ?? $this->Auth->user('user_id');
        return $character->user_id == $userId || $this->isAdmin($userId);
    }

    public function isMapAdmin($userId = null)
    {
        return $this->checkSitePermission(
            $userId ?? $this->Auth->user('user_id'),
            Permission::$MapAdmin
        );
    }
}
