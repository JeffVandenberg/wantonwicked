<?php
App::uses('AppController', 'Controller');

/**
 * Characters Controller
 *
 * @property Character $Character
 * @property PaginatorComponent $Paginator
 * @property PermissionsComponent Permissions
 * @property MenuComponent Menu
 */
class CharactersController extends AppController
{

    /**
     * Components
     *
     * @var array
     */
    public $components = array(
    );

    public function beforeFilter()
    {
        parent::beforeFilter();
        $this->Auth->allow(
                   array(
                       'city',
                       'cast',
                       'activity'
                   ));
    }

    public function city($city = 'Savannah')
    {
        $this->set('characters', $this->Character->ListByCity($city));
    }

    public function activity()
    {
        $this->set('data', $this->Character->ListBarelyPlaying());
        $this->set('data2', $this->Character->ListAllLoginActivity());
    }

    public function cast($type = 'All')
    {
        $this->Character->recursive = 0;
        $this->Paginator->settings = array(
            'limit' => 30,
            'conditions' => array(
                'Character.is_sanctioned' => 'Y',
                'Character.city' => 'Savannah',
                'Character.is_deleted' => 'N'
            ),
            'order' => 'Character.character_name',
            'contain' => array(
                'Player'
            )
        );

        if(strtolower($type) !== 'all') {
            $this->Paginator->settings['conditions']['Character.character_type'] = $type;
        }
        $characterTypes = array("All" => 'All', "Mortal" => 'Mortal', "Vampire" => 'Vampire', "Ghoul" => 'Ghoul',
                                "Werewolf" => 'Werewolf', "Wolfblooded" => 'Wolfblooded', "Mage" => 'Mage',
                                "Sleepwalker" => 'Sleepwalker', "Changeling" => 'Changeling', "Geist" => 'Geist');
        $this->set('characters', $this->Paginator->paginate());
        $this->set(compact('type', 'characterTypes'));
    }

    public function admin_goals($type = 'all')
    {
        $storytellerMenu = $this->Menu->createStorytellerMenu();
        $this->set('submenu', $storytellerMenu);
        $this->Character->recursive = 0;
        $this->Paginator->settings = array(
            'limit' => 30,
            'conditions' => array(
                'Character.is_sanctioned' => 'Y',
                'Character.city' => 'Savannah',
                'Character.is_deleted' => 'N'
            ),
            'order' => 'Character.character_name',
            'field' => array(
                'character_name',
                'goals'
            ),
            'contain' => array(
                'Player'
            )
        );

        if(strtolower($type) !== 'all') {
            $this->Paginator->settings['conditions']['Character.character_type'] = $type;
        }
        $characterTypes = array("All" => 'All', "Mortal" => 'Mortal', "Vampire" => 'Vampire', "Ghoul" => 'Ghoul',
                                "Werewolf" => 'Werewolf', "Wolfblooded" => 'Wolfblooded', "Mage" => 'Mage',
                                "Sleepwalker" => 'Sleepwalker', "Changeling" => 'Changeling', "Geist" => 'Geist');
        $this->set('characters', $this->Paginator->paginate());
        $this->set(compact('type', 'characterTypes'));
    }

    public function isAuthorized($user)
    {
        switch($this->request->params['action'])
        {
            case 'admin_goals':
                return $this->Permissions->IsST();
                break;
        }
        return false;
    }

    /**
     * index method
     *
     * @return void
     */
    public function index()
    {
        $this->Character->recursive = 0;
        $this->set('characters', $this->Paginator->paginate());
    }

    /**
     * view method
     *
     * @throws NotFoundException
     * @param string $id
     * @return void
     */
    public function view($id = null)
    {
        if (!$this->Character->exists($id)) {
            throw new NotFoundException(__('Invalid character'));
        }
        $options = array('conditions' => array('Character.' . $this->Character->primaryKey => $id));
        $this->set('character', $this->Character->find('first', $options));
    }

    /**
     * add method
     *
     * @return void
     */
    public function add()
    {
        if ($this->request->is('post')) {
            $this->Character->create();
            if ($this->Character->save($this->request->data)) {
                $this->Session->setFlash(__('The character has been saved.'));

                return $this->redirect(array('action' => 'index'));
            }
            else {
                $this->Session->setFlash(__('The character could not be saved. Please, try again.'));
            }
        }
    }

    /**
     * edit method
     *
     * @throws NotFoundException
     * @param string $id
     * @return void
     */
    public function edit($id = null)
    {
        if (!$this->Character->exists($id)) {
            throw new NotFoundException(__('Invalid character'));
        }
        if ($this->request->is(array('post', 'put'))) {
            if ($this->Character->save($this->request->data)) {
                $this->Session->setFlash(__('The character has been saved.'));

                return $this->redirect(array('action' => 'index'));
            }
            else {
                $this->Session->setFlash(__('The character could not be saved. Please, try again.'));
            }
        }
        else {
            $options             = array('conditions' => array('Character.' . $this->Character->primaryKey => $id));
            $this->request->data = $this->Character->find('first', $options);
        }
    }

    /**
     * delete method
     *
     * @throws NotFoundException
     * @param string $id
     * @return void
     */
    public function delete($id = null)
    {
        $this->Character->id = $id;
        if (!$this->Character->exists()) {
            throw new NotFoundException(__('Invalid character'));
        }
        $this->request->onlyAllow('post', 'delete');
        if ($this->Character->delete()) {
            $this->Session->setFlash(__('The character has been deleted.'));
        }
        else {
            $this->Session->setFlash(__('The character could not be deleted. Please, try again.'));
        }

        return $this->redirect(array('action' => 'index'));
    }
}
