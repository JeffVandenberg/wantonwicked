<?php
/**
 * Created by PhpStorm.
 * User: JeffVandenberg
 * Date: 7/22/14
 * Time: 8:32 PM
 */



use App\Controller\AppController;

/**
 * Characters Controller
 *
 * @property PermissionsComponent Permissions
 */
class UsersController extends AppController
{
    public function beforeFilter(Event $event) {
        parent::beforeFilter($event);
        $this->Auth->allow('login', 'receiveUser');
    }

    public function login() {
        $this->redirect('/forum/ucp.php?mode=login');
    }

    public function assignGroups($userId=0) {
        if($this->request->is('post')) {
            // lookup user or save
            use App\Model\User;
            use App\Model\ForumGroup;
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

        use App\Model\User;
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
        switch($this->request->params['action'])
        {
            case 'assignGroups':
                return $this->Permissions->IsHead();
                break;
        }
        return false;
    }

}