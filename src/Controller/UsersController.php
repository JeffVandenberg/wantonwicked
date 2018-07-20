<?php

namespace App\Controller;

/**
 * Created by PhpStorm.
 * User: JeffVandenberg
 * Date: 7/22/14
 * Time: 8:32 PM
 */

use App\Model\Entity\User;
use App\Model\Table\PhpbbGroupsTable;
use App\Controller\Component\PermissionsComponent;
use App\Model\Table\UsersTable;
use Cake\Event\Event;
use Cake\ORM\TableRegistry;
use Exception;


/**
 * Characters Controller
 *
 * @property PermissionsComponent Permissions
 * @property UsersTable Users
 */
class UsersController extends AppController
{
    public function beforeFilter(Event $event)
    {
        parent::beforeFilter($event);
        $this->Auth->allow([
            'login', 'receiveUser'
        ]);
    }

    public function login()
    {
        $this->redirect('/forum/ucp.php?mode=login');
    }

    public function assignGroups($userId = 0)
    {
        if ($this->getRequest()->is('post')) {
            // lookup user or save

            if ($this->getRequest()->getData('action')) {
                // trying to save user groups
                if ($this->Users->saveUserGroups($this->getRequest()->getData())) {
                    $this->Flash->set('Updated User Groups');
                    $this->redirect('');
                } else {
                    $this->Flash->set('Error updating user Groups');
                }
            }

            if ($this->getRequest()->getData('user_id')) {
                $user = $this->Users->get($this->getRequest()->getData('user_id'));
                /* @var User $user */

                $userGroups = $this->Users->listUserGroups($user->user_id);
                $userGroupList = [];
                foreach ($userGroups as $userGroup) {
                    $userGroupList[$userGroup['group_id']] = $userGroup;
                }

                $phpbbGroups = TableRegistry::getTableLocator()->get('PhpbbGroups');
                /* @var PhpbbGroupsTable $phpbbGroups */

                $groups = $phpbbGroups->find('list')->order(['PhpbbGroups.group_name'])->toArray();

                $this->set(array(
                    'user' => $user,
                    'groups' => $groups,
                    'userGroups' => $userGroupList
                ));
            }
        }
        $storytellerMenu = $this->Menu->createStorytellerMenu();
        $this->set('submenu', $storytellerMenu);
    }

    public function receiveUser()
    {
        // receive a user from the main site for migration. Whoo!
        $user = $this->getRequest()->getData('user');

        $repo = new User();

        $response = [
            'success' => false,
            'message' => ''
        ];

        try {
            $result = $repo->addUserToSite($user);
            $response['success'] = $result;
        } catch (Exception $e) {
            $response['message'] = $e->getMessage();
        }

        $this->set(compact('response'));
        $this->set('_serialize', ['response']);
    }

    public function isAuthorized($user)
    {
        switch ($this->getRequest()->getParam('action')) {
            case 'assignGroups':
                return $this->Permissions->isHead();
                break;
        }
        return false;
    }

}
