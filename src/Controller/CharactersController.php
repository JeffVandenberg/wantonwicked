<?php

namespace App\Controller;

use App\Controller\Component\MenuComponent;
use App\Controller\Component\PermissionsComponent;
use App\Model\Table\CharactersTable;
use Cake\Controller\Component\PaginatorComponent;
use Cake\Event\Event;
use Cake\Network\Exception\NotFoundException;
use Cake\Utility\Inflector;
use Cake\Utility\Text;
use classes\character\data\BeatStatus;
use classes\character\data\BeatType;
use classes\character\data\Character;
use classes\character\data\CharacterBeat;
use classes\character\data\CharacterStatus;
use classes\character\nwod2\BeatService;
use classes\character\nwod2\SheetService;
use classes\character\repository\CharacterNoteRepository;
use classes\core\repository\RepositoryManager;
use classes\log\CharacterLog;
use classes\log\data\ActionType;

/**
 * Characters Controller
 *
 * @property PaginatorComponent $Paginator
 * @property PermissionsComponent Permissions
 * @property MenuComponent Menu
 * @property CharactersTable Characters
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

    public function beforeFilter(Event $event)
    {
        parent::beforeFilter($event);
        $this->Auth->allow(
            array(
                'city',
                'cast',
                'activity'
            ));
    }

    public function activity()
    {
        $this->set('data', $this->Characters->listBarelyPlaying());
        $this->set('data2', $this->Characters->listAllLoginActivity());
    }

    public function cast($type = 'All')
    {
        $query = $this->Characters
            ->find()
            ->select([
                'Characters.id',
                'Characters.character_name',
                'Characters.character_type',
                'Characters.splat1',
                'Characters.splat2',
                'Characters.is_npc',
                'Characters.character_status_id',
            ])
            ->where([
                'Characters.character_status_id IN ' => CharacterStatus::Sanctioned,
                'Characters.city' => 'portland',
            ])
            ->contain([
                'Users' => [
                    'fields' => [
                        'user_id',
                        'username'
                    ]
                ]
            ]);

        if (strtolower($type) !== 'all') {
            $query->andWhere([
                'Characters.character_type' => $type
            ]);
        }
        $this->set('characters', $this->paginate($query, [
            'limit' => 20,
            'order' => [
                'character_name' => 'asc'
            ],
            'sortWhitelist' => [
                'Users.username',
                'character_name',
                'character_type',
                'splat1',
                'splat2',
            ]
        ]));
        $characterTypes = array(
            "All" => 'All', "Mortal" => 'Mortal', "Vampire" => 'Vampire', "Ghoul" => 'Ghoul',
            "Werewolf" => 'Werewolf', "Wolfblooded" => 'Wolfblooded', 'Changing Breed' => 'Changing Breed',
            "Mage" => 'Mage', "Sleepwalker" => 'Sleepwalker', "Changeling" => 'Changeling', "Geist" => 'Geist');
        $this->set(compact('type', 'characterTypes'));
    }

    public function stGoals($type = 'all')
    {
        $storytellerMenu = $this->Menu->createStorytellerMenu();
        $this->set('submenu', $storytellerMenu);

        $query = $this->Characters->CharacterPowers
            ->find()
            ->select([
                'Characters.character_name',
                'Characters.character_type',
                'Characters.splat1',
                'Characters.splat2',
                'Characters.is_npc',
                'CharacterPowers.power_name',
                'Users.username'
            ])
            ->contain([
                'Characters' => [
                    'Users'
                ]
            ])
            ->where([
                'Characters.character_status_id IN ' => CharacterStatus::Sanctioned,
                'Characters.city' => 'portland',
                'CharacterPowers.power_type' => 'aspiration'
            ]);

        if (strtolower($type) !== 'all') {
            $query = $query->andWhere([
                'Characters.character_type' => $type
            ]);
        }

        $characterTypes = array("All" => 'All', "Mortal" => 'Mortal', "Vampire" => 'Vampire', "Ghoul" => 'Ghoul',
            "Werewolf" => 'Werewolf', "Wolfblooded" => 'Wolfblooded', "Mage" => 'Mage',
            "Sleepwalker" => 'Sleepwalker', "Changeling" => 'Changeling', "Geist" => 'Geist');
        $this->set('characters', $this->Paginator->paginate($query, [
            'limit' => 30,
            'order' => [
                'Characters.character_name',
            ]
        ]));
        $this->set(compact('type', 'characterTypes'));
    }

    public function admin_xpEdit()
    {
        if ($this->request->is('post')) {
            $sheetService = new SheetService();
            $sheetService->grantXpToCharacter(
                $this->request->getData('character_id'),
                $this->request->getData('xp_amount'),
                'Admin XP Override. Amount: ' . $this->request->getData('xp_amount') .
                ' Note: ' . $this->request->getData('xp_note'),
                $this->Auth->user('user_id')
            );
            $this->Flash->set('Updated XP for Character');
        }
    }

    public function isAuthorized()
    {
        switch ($this->request->getParam('action')) {
            case 'admin_xpEdit':
                return $this->Permissions->IsAdmin();
            case 'stGoals':
            case 'stView':
            case 'stBeats':
                return $this->Permissions->IsST();
            case 'add':
            case 'validateName':
            case 'viewOwn':
            case 'beats':
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
        $this->set('characters', $this->Paginator->paginate($this->Characters));
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
        $this->set('character', $this->Characters->get($id));
    }

    public function viewOwn($slug)
    {
        $sheetService = new SheetService();
        $characterType = $this->request->getQuery('character_type');
        $character = $sheetService->loadSheet($slug, $characterType);
        /* @var Character $character */
        if (!$character->Id) {
            throw new NotFoundException(__('Invalid character'));
        }

        if (($character->UserId != $this->Auth->user('user_id')) && !($this->Permissions->IsAdmin())) {
            $this->Flash->set('Unauthorized Access');
            $this->redirect('/');
            return;
        }

        $options = [
            'show_admin' => false,
            'edit_mode' => 'limited', // other values "open", "none"
        ];

        if ($character->CharacterStatusId == CharacterStatus::New) {
            $options['edit_mode'] = 'open';
            $sheetService->addMinPowersForEdit($character);
        }

        if ($this->request->is('post')) {
            // save update
            $updatedData = $this->request->getData();
            $updatedData['slug'] = Text::slug($updatedData['city'] . ' ' . $updatedData['character_name']);

            $result = $sheetService->saveSheet($updatedData, $options, $this->Auth->user());

            if (is_string($result)) {
                $this->Flash->set($result);
                $this->set('data', $character);
            } else {
                if ($options['edit_mode'] == 'open') {
                    $this->Flash->set('Updated ' . $updatedData['character_name'] . '.');
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

        if ($this->request->is('post')) {
            if ($this->request->getData()['character_id']) {
                // try to update the character
                $updatedData = $this->request->getData();
                $updatedData['slug'] = Text::slug($updatedData['city'] . ' ' . $updatedData['character_name']);
                $result = $sheetService->saveSheet($updatedData, $options, $this->Auth->user());

                if (!is_string($result)) {
                    $this->Flash->set('Updated ' . $updatedData['character_name'] . '.');
                    $this->redirect(['action' => 'stView']);
                } else {
                    $this->Flash->set($result);
                }
            }
        }
        if ($this->request->is('get')) {
            $characterType = $this->request->getQuery('character_type');
            $character = null;
            if ($this->request->getQuery('view_character_id')) {
                // attempt to load the character
                $character = $sheetService->loadSheet($this->request->getQuery('view_character_id'), $characterType);
                if (!$character->Id) {
                    $this->Flash->set('Unable to find character');
                }
            } else if ($characterId) {
                $character = $sheetService->loadSheet($characterId, $characterType);
                if (!$character->Id) {
                    $this->Flash->set('Unable to find character');
                }
            }

            if ($character && $character->Id) {
                CharacterLog::LogAction($character->Id, ActionType::ViewCharacter, 'ST View', $this->Auth->user('user_id'));
                $repo = RepositoryManager::GetRepository('classes\character\data\CharacterNote');
                /* @var CharacterNoteRepository $repo */
                $characterNote = $repo->getMostRecentForCharacter($character->Id);
                if ($characterNote) {
                    $character->setLastStNote($characterNote);
                }
                $sheetService->addMinPowersForEdit($character);
                $this->set(compact('character'));
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
            $character = $this->request->getData();
            $character['slug'] = Text::slug($character['city'] . ' ' . $character['character_name']);

            $result = $sheetService->saveSheet($character, $options, $this->Auth->user());

            if (is_string($result)) {
                $this->Flash->set($result);
                $this->set('data', $character);
            } else {
                $this->Flash->set('Created ' . $character['character_name'] . '.');
                $this->redirect('/chat.php');
            }
        } else {
            $characterType = ($this->request->getQuery('character_type'))
                ? $this->request->getQuery('character_type')
                : 'mortal';
            $character = $sheetService->initializeSheet($characterType);
            $this->set(compact('character'));
        }
        $icons = $sheetService->listAvailableIcons();
        $this->set(compact('options', 'icons'));
    }


    public function validateName()
    {
        $id = $this->request->getQuery('id');
        $characterName = $this->request->getQuery('name');
        $city = $this->request->getQuery('city');

        $data = [
            'success' => false,
            'in_use' => true
        ];

        if ($characterName && $city) {
            $data['in_use'] = $this->Characters->findNameUsedInCity($id, $characterName, $city);
            $data['success'] = true;
        }

        $this->autoRender = false;
        echo json_encode($data);
        die();
    }

    public function assignCondition()
    {
        // grant condition to character
    }

    public function beats($characterId)
    {
        $sheetService = new SheetService();
        $beatService = new BeatService();
        $character = $sheetService->loadSheet($characterId);
        if (!$character || !$character->Id) {
            $this->Flash->set('Unable to find character');
            $this->redirect('/chat.php');
        }

        if (!$this->Permissions->MayEditCharacter($character->Id)) {
            CharacterLog::LogAction($character->Id, ActionType::InvalidAccess, 'Attempted Access to Beats', $this->Auth->user('user_id'));
            $this->Flash->set('Unable to view that character');
            $this->redirect('/');
        }

        $isSt = $this->Permissions->IsST();
        $currentBeatStatus = $beatService->getBeatStatusForCharacter($character->Id);

        if ($this->request->is('post')) {
            // attempt to save
            $beat = new CharacterBeat();
            $beat->BeatTypeId = $this->request->data['beat_type_id'];
            $beat->Note = $this->request->data['note'];

            $beat->CharacterId = $character->Id;
            $beat->BeatStatusId = ($isSt) ? BeatStatus::StaffAwarded : BeatStatus::NewBeat;
            $beat->CreatedById = $beat->UpdatedById = $this->Auth->user('user_id');
            $beat->Created = $beat->Updated = date('Y-m-d H:i:s');
            $beat->BeatsAwarded = 0;

            if (!$beatService->addNewBeat($beat)) {
                $this->Flash->set('Error Saving Beat!');
            } else {
                $this->redirect('/characters/beats/' . $character->Slug);
            }
        }

        $beatTypeRepo = RepositoryManager::GetRepository('classes\character\data\BeatType');
        if (!$isSt) {
            $beatTypes = $beatTypeRepo->ListByAdminOnly(false);
        } else {
            $beatTypes = $beatTypeRepo->listAll();
        }
        /* @var BeatType[] $beatTypes */
        $beatList = [];
        foreach ($beatTypes as $beatType) {
            $beatList[$beatType->Id] = $beatType->Name;
        }

        $pastBeats = $beatService->listPastBeatsForCharacter($character->Id);
        if ($isSt) {
            $submenu = $this->Menu->createStorytellerMenu();
        } else {
            $submenu = $this->Menu->createCharacterMenu($character->Id, $character->CharacterName);
        }
        $this->set(compact('character', 'beatList', 'currentBeatStatus', 'pastBeats', 'submenu'));
    }

    public function stBeats()
    {
        if ($this->request->is('post')) {
            $beatService = new BeatService();
            $beat = new CharacterBeat();
            $beat->CharacterId = $this->request->getData('character_id');
            $beat->BeatTypeId = $this->request->getData('beat_type_id');
            $beat->Note = $this->request->getData('note');

            $beat->CreatedById = $beat->UpdatedById = $this->Auth->user('user_id');
            $beat->Created = $beat->Updated = date('Y-m-d H:i:s');
            $beat->BeatStatusId = BeatStatus::StaffAwarded;
            $beat->BeatsAwarded = 0;

            if ($beatService->addNewBeat($beat)) {
                $this->Flash->set('Granted beat');
                $this->redirect('/characters/stBeats');
            } else {
                $this->Flash->set('Error saving beat. Please try again');
            }
        }

        $beatTypeRepo = RepositoryManager::GetRepository('classes\character\data\BeatType');
        $beatTypes = $beatTypeRepo->listAll();
        /* @var BeatType[] $beatTypes */
        $beatList = [];
        foreach ($beatTypes as $beatType) {
            $beatList[$beatType->Id] = $beatType->Name;
        }

        $cities = [
            "portland" => 'Portland',
            "Savannah" => 'Savannah',
            "San Diego" => 'San Diego',
            "The City" => 'The City',
            "Side Game" => 'Side Game'
        ];

        $this->set(compact('beatList', 'cities'));
    }
}
