<?php
/**
 * Created by PhpStorm.
 * User: JeffVandenberg
 * Date: 7/22/14
 * Time: 8:32 PM
 */

App::uses('AppController', 'Controller');

/**
 * Characters Controller
 *
 * @property PermissionsComponent Permissions
 */
class UsersController extends AppController
{
    public function beforeFilter() {
        parent::beforeFilter();
        $this->Auth->allow('login', 'receiveUser');
    }

    public function login() {
        $this->redirect('/forum/ucp.php?mode=login');
    }

    public function assignGroups($userId=0) {
        if($this->request->is('post')) {
            // lookup user or save
            App::uses('User', 'Model');
            App::uses('ForumGroup', 'Model');
            $user = new User();

            if(isset($this->request->data['action'])) {
                // trying to save user groups
                if($user->saveUserGroups($this->request->data)) {
                    $this->Session->setFlash('Updated User Groups');
                    $this->redirect('');
                }
                else {
                    $this->Session->setFlash('Error updating user Groups');
                }
            }
            if($this->request->data['user_id']) {
                $userData = $user->find('first', array(
                    'fields' => array(
                        'User.user_id',
                        'User.username',
                    ),
                    'conditions' => array(
                        'User.user_id' => $this->request->data['user_id']
                    )
                ));
                $userGroups = $user->listUserGroups($userData['User']['user_id']);
                $userGroupList = array();
                foreach($userGroups as $userGroup) {
                    $userGroupList[$userGroup['UserGroup']['group_id']] = $userGroup;
                }
                $forumGroup = new ForumGroup();
                $groups = $forumGroup->listGroups();

                $this->set(array(
                    'user' => $userData,
                    'groups' => $groups,
                    'userGroups' => $userGroupList
                ));
            }
        }
        $storytellerMenu = $this->Menu->createStorytellerMenu();
        $this->set('submenu', $storytellerMenu);
    }

    public function receiveUser() {
        // receive a user from the main site for migration. Whoo!
        $user = $this->request->data['user'];

        $result = array(
            'result' => false,
            'message' => 'Unknown Error'
        );
        $userName = $user['username'];

        header('content-type: application/json');
        echo json_encode($result);
        die();
    }

    public function isAuthorized($user)
    {
        switch($this->request->params['action'])
        {
            case 'assignGroups':
                return $this->Permissions->IsHead();
                break;
        }
        return false;
    }

}