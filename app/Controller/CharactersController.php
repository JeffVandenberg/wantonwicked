<?php
use classes\character\data\Character;
use \Character as CakeCharacter;
use classes\character\nwod2\SheetService;
use classes\character\repository\CharacterRepository;
use classes\log\CharacterLog;
use classes\log\data\ActionType;

App::uses('AppController', 'Controller');

/**
 * Characters Controller
 *
 * @property CakeCharacter $Character
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
    public $components = [
        'Flash'
    ];

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

    public function city($city = 'portland')
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
                'Character.city' => 'portland',
                'Character.is_deleted' => 'N'
            ),
            'order' => 'Character.character_name',
            'contain' => array(
                'Player'
            )
        );

        if (strtolower($type) !== 'all') {
            $this->Paginator->settings['conditions']['Character.character_type'] = $type;
        }
        $characterTypes = array(
            "All" => 'All', "Mortal" => 'Mortal', "Vampire" => 'Vampire', "Ghoul" => 'Ghoul',
            "Werewolf" => 'Werewolf', "Wolfblooded" => 'Wolfblooded', 'Changing Breed' => 'Changing Breed',
            "Mage" => 'Mage', "Sleepwalker" => 'Sleepwalker', "Changeling" => 'Changeling', "Geist" => 'Geist');
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
                'Character.city' => 'portland',
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

        if (strtolower($type) !== 'all') {
            $this->Paginator->settings['conditions']['Character.character_type'] = $type;
        }
        $characterTypes = array("All" => 'All', "Mortal" => 'Mortal', "Vampire" => 'Vampire', "Ghoul" => 'Ghoul',
            "Werewolf" => 'Werewolf', "Wolfblooded" => 'Wolfblooded', "Mage" => 'Mage',
            "Sleepwalker" => 'Sleepwalker', "Changeling" => 'Changeling', "Geist" => 'Geist');
        $this->set('characters', $this->Paginator->paginate());
        $this->set(compact('type', 'characterTypes'));
    }

    public function isAuthorized()
    {
        switch ($this->request->params['action']) {
            case 'admin_goals':
            case 'stView':
                return $this->Permissions->IsST();
            case 'add':
            case 'validateName':
            case 'viewOwn':
                return $this->Auth->user();
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

    public function viewOwn($slug)
    {
        $sheetService = new SheetService();
        $characterType = $this->request->query('character_type');
        $character = $sheetService->loadSheet($slug, $characterType);
        /* @var Character $character */
        if(!$character) {
            throw new NotFoundException(__('Invalid character'));
        }

        if(($character->UserId != $this->Auth->user('user_id')) && !($this->Permissions->IsAdmin())) {
            $this->Flash->set('Unauthorized Access');
            $this->redirect('/');
            return;
        }

        $options = [
            'show_admin' => false,
            'edit_mode' => 'limited', // other values "open", "none"
        ];

        if($character->IsSanctioned === '') {
            $options['edit_mode'] = 'open';
            $sheetService->addMinPowersForEdit($character);
        }

        if($this->request->is('post'))
        {
            // save update
            $updatedData = $this->request->data;
            $updatedData['slug'] = Inflector::slug($updatedData['city'] . ' ' . $updatedData['character_name']);

            $result = $sheetService->saveSheet($updatedData, $options, $this->Auth->user());

            if(is_string($result)) {
                $this->Flash->set($result);
                $this->set('data', $character);
            } else {
                if($options['edit_mode'] == 'open') {
                    $this->Flash->set('Updated '. $updatedData['character_name'] . '.');
                } else {
                    $this->Flash->set('Updated ' . $character->CharacterName . '.');
                }
                $this->redirect('/chat.php');
            }

        }

        $icons = $sheetService->listAvailableIcons();
        $this->set(compact('character', 'options', 'icons'));

    }

    public function stView($characterId = null)
    {
        $options = [
            'show_admin' => true,
            'edit_mode' => 'open', // other values "open", "none"
        ];
        $sheetService = new SheetService();

        if($this->request->is('post')) {
            if($this->request->data['character_id']) {
                // try to update the character
                $updatedData = $this->request->data;
                $updatedData['slug'] = Inflector::slug($updatedData['city'] . ' ' . $updatedData['character_name']);
                $result = $sheetService->saveData($updatedData, $options, $this->Auth->user());

                if(!is_string($result)) {
                    $this->Flash->set('Updated ' . $updatedData['character_name'] . '.');
                } else {
                    $this->Flash->set($result);
                }
            }
        }
        if($this->request->is('get')) {
            $characterType = $this->request->query('character_type');
            if($this->request->query('view_character_id')) {
                // attempt to load the character
                $character = $sheetService->loadSheet($this->request->query('view_character_id'), $characterType);
            }
            else {
                $character = $sheetService->loadSheet($characterId, $characterType);
            }

            if($character) {
                CharacterLog::LogAction($character->Id, ActionType::ViewCharacter, 'ST View', $this->Auth->user('user_id'));
                $sheetService->addMinPowersForEdit($character);
                $this->set(compact('character'));
            } else {
                $this->Flash->set('Unable to find character');
            }
        }

        $icons = $sheetService->listAvailableIcons();
        $cities = [
            "portland" => 'Portland',
            "Savannah" => 'Savannah',
            "San Diego" => 'San Diego',
            "The City" => 'The City',
            "Side Game" => 'Side Game'
        ];
        $this->set(compact('cities', 'options', 'icons'));
    }

    /**
     * add method
     *
     * @return void
     */
    public function add()
    {
        $options = [
            'show_admin' => false,
            'edit_mode' => 'open', // other values "open", "none"
        ];
        $sheetService = new SheetService();

        if ($this->request->is('post')) {
            $character = $this->request->data;
            $character['slug'] = Inflector::slug($character['city'] . ' ' . $character['character_name']);

            $result = $sheetService->saveSheet($character, $options, $this->Auth->user());

            if(is_string($result)) {
                $this->Flash->set($result);
                $this->set('data', $character);
            } else {
                $this->Flash->set('Created '. $character['character_name'] . '.');
                $this->redirect('/chat.php');
            }
        } else {
            $characterType = ($this->request->query('character_type'))
                ? $this->request->query('character_type')
                : 'mortal';
            $character = $sheetService->initializeSheet($characterType);
            $this->set(compact('character'));
        }
        $icons = $sheetService->listAvailableIcons();
        $this->set(compact('options', 'icons'));
    }

    public function validateName()
    {
        $id = $this->request->query['id'];
        $characterName = $this->request->query['name'];
        $city = $this->request->query['city'];

        $data = [
            'success' => false,
            'in_use' => true
        ];

        if($characterName && $city) {
            $data['in_use'] = $this->Character->findNameUsedInCity($id, $characterName, $city);
            $data['success'] = true;
        }

        $this->autoRender = false;
        return json_encode($data);
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
                $this->Flash->set(__('The character has been saved.'));

                $this->redirect(array('action' => 'index'));
            } else {
                $this->Flash->set(__('The character could not be saved. Please, try again.'));
            }
        } else {
            $options = array('conditions' => array('Character.' . $this->Character->primaryKey => $id));
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
        $this->request->allowMethod(['post', 'delete']);

        if ($this->Character->delete()) {
            $this->Flash->set(__('The character has been deleted.'));
        } else {
            $this->Flash->set(__('The character could not be deleted. Please, try again.'));
        }

        $this->redirect(array('action' => 'index'));
    }

    public function assignCondition()
    {
        // grant condition to character
    }
}
