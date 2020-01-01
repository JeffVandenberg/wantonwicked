<?php

namespace App\Controller;

use App\Controller\Component\ConfigComponent;
use App\Controller\Component\MenuComponent;
use App\Controller\Component\PermissionsComponent;
use App\Model\Entity\Permission;
use App\Model\Table\CharactersTable;
use Cake\Controller\Component\PaginatorComponent;
use Cake\Core\Configure;
use Cake\Event\Event;
use Cake\Http\Response;
use Cake\Network\Exception\NotFoundException;
use Cake\ORM\TableRegistry;
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
use Exception;

/**
 * Characters Controller
 *
 * @property PaginatorComponent $Paginator
 * @property PermissionsComponent Permissions
 * @property MenuComponent Menu
 * @property CharactersTable $Characters
 * @property ConfigComponent Config
 */
class CharactersController extends AppController
{
    /**
     * @param Event $event Event to handle
     * @return Response|void|null
     */
    public function beforeFilter(Event $event)
    {
        parent::beforeFilter($event);
        $this->Auth->allow(
            [
                'index',
                'city',
                'cast',
                'activity',
            ]
        );
    }

    /**
     * @return void
     */
    public function activity(): void
    {
        $this->set('data', $this->Characters->listBarelyPlaying());
        $this->set('data2', $this->Characters->listAllLoginActivity());
    }

    /**
     * @param string $type character type to list
     * @return void
     * @throws Exception
     */
    public function cast($type = 'All'): void
    {
        $query = $this->Characters
            ->find()
            ->select([
                'Characters.id',
                'Characters.character_name',
                'Characters.slug',
                'Characters.character_type',
                'Characters.splat1',
                'Characters.splat2',
                'Characters.is_npc',
                'Characters.character_status_id',
                'CharacterStatuses.name',
            ])
            ->where([
                'Characters.character_status_id IN ' => [CharacterStatus::ACTIVE, CharacterStatus::IDLE],
                'Characters.city' => $this->Config->readGlobal('city'),
            ])
            ->contain([
                'Users' => [
                    'fields' => [
                        'user_id',
                        'username',
                    ],
                ],
                'CharacterStatuses',
            ]);

        if (strtolower($type) !== 'all') {
            $query->andWhere([
                'Characters.character_type' => $type,
            ]);
        }
        $this->set('characters', $this->paginate($query, [
            'limit' => 20,
            'order' => [
                'character_name' => 'asc',
            ],
            'sortWhitelist' => [
                'Users.username',
                'Characters.character_name',
                'character_name',
                'Characters.character_type',
                'character_type',
                'Characters.is_npc',
                'is_npc',
                'Characters.splat1',
                'Characters.splat2',
                'splat1',
                'splat2',
            ],
        ]));
        $characterTypes = [
            'All' => 'All', 'Mortal' => 'Mortal', 'Vampire' => 'Vampire', 'Ghoul' => 'Ghoul',
            'Werewolf' => 'Werewolf', 'Wolfblooded' => 'Wolfblooded', 'Changing Breed' => 'Changing Breed',
            'Changeling' => 'Changeling', 'Fae-Touched' => 'Fae-Touched', 'Geist' => 'Geist', 'Beast' => 'Beast',
            'Herald' => 'Herald',
        ];
        $mayManageCharacters = $this->Permissions->checkSitePermission(
            $this->Auth->user('user_id'),
            Permission::$ManageCharacters
        );
        $this->set(compact('type', 'characterTypes', 'mayManageCharacters'));
    }

    /**
     * @param string $type Character Type
     * @return void
     * @throws Exception
     */
    public function stGoals($type = 'all'): void
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
                'Users.username',
            ])
            ->contain([
                'Characters' => [
                    'Users',
                ],
            ])
            ->where([
                'Characters.character_status_id IN ' => CharacterStatus::SANCTIONED,
                'Characters.city' => $this->Config->readGlobal('city'),
                'CharacterPowers.power_type' => 'aspiration',
            ]);

        if (strtolower($type) !== 'all') {
            $query = $query->andWhere([
                'Characters.character_type' => $type,
            ]);
        }

        $characterTypes = ['All' => 'All', 'Mortal' => 'Mortal', 'Vampire' => 'Vampire', 'Ghoul' => 'Ghoul',
            'Werewolf' => 'Werewolf', 'Wolfblooded' => 'Wolfblooded', 'Mage' => 'Mage',
            'Sleepwalker' => 'Sleepwalker', 'Changeling' => 'Changeling', 'Geist' => 'Geist'];
        $this->set('characters', $this->Paginator->paginate($query, [
            'limit' => 30,
            'order' => [
                'Characters.character_name',
            ],
        ]));
        $this->set(compact('type', 'characterTypes'));
    }

    /**
     * @return bool|mixed|null
     */
    public function isAuthorized()
    {
        switch ($this->getRequest()->getParam('action')) {
            case 'stGoals':
            case 'stView':
            case 'stBeats':
            case 'notes':
                return $this->Permissions->isST();
            case 'add':
            case 'validateName':
            case 'viewOwn':
            case 'viewOther':
            case 'beats':
            case 'all':
                return $this->Auth->user();
        }

        return false;
    }

    /**
     * index method
     *
     * @return void
     * @throws Exception
     */
    public function index()
    {
        $query = $this->Characters
            ->find()
            ->contain([
                'CharacterStatuses',
            ])
            ->where([
                'Characters.user_id' => $this->Auth->user('user_id'),
                'Characters.city' => $this->Config->readGlobal('city'),
                'Characters.character_status_id !=' => CharacterStatus::DELETED
            ]);
        $this->set('characters', $this->Paginator->paginate($query, [
            'order' => [
                'Characters.character_name'
            ],
            'limit' => '20'
        ]));
    }

    /**
     * index method
     *
     * @return void
     * @throws Exception
     */
    public function all()
    {
        $query = $this->Characters
            ->find()
            ->contain([
                'CharacterStatuses',
            ])
            ->where([
                'Characters.user_id' => $this->Auth->user('user_id'),
                'Characters.character_status_id !=' => CharacterStatus::DELETED
            ]);
        $this->set('characters', $this->Paginator->paginate($query, [
            'order' => [
                'Characters.character_name'
            ],
            'limit' => '20'
        ]));
    }

    /**
     * view method
     *
     * @param string $id Character ID to view
     * @return void
     * @throws NotFoundException
     */
    public function view($id = null)
    {
        $this->set('character', $this->Characters->get($id));
    }

    /**
     * @param string $slug Slug to view
     * @return void
     * @throws Exception
     */
    public function viewOwn($slug)
    {
        $sheetService = new SheetService();
        $characterType = $this->getRequest()->getQuery('character_type');
        $character = $sheetService->loadSheet($slug, $characterType);
        /* @var Character $character */
        if (!$character->Id) {
            throw new NotFoundException(__('Invalid character'));
        }

        if (($character->UserId != $this->Auth->user('user_id')) && !($this->Permissions->isAdmin())) {
            $this->Flash->set('Unauthorized Access');
            $this->redirect('/');

            return;
        }

        $options = [
            'show_admin' => false,
            'owner' => true,
            'edit_mode' => 'limited', // other values "open", "none"
        ];

        if ($character->CharacterStatusId == CharacterStatus::NEW_CHARACTER) {
            $options['edit_mode'] = 'open';
            $sheetService->addMinPowers($character);
        } else {
            $sheetService->addMinPowers($character, ['aspiration', 'equipment']);
        }

        if ($this->getRequest()->is('post')) {
            // save update
            $updatedData = $this->getRequest()->getData();
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
                $this->redirect('/characters');
            }

        }

        $icons = TableRegistry::getTableLocator()->get('Icons')->listAvailableIcons();
        $submenu = $this->Menu->createCharacterMenu($character->Id, $character->CharacterName, $character->Slug);
        $this->set(compact('character', 'options', 'icons', 'submenu'));
    }

    /**
     * @param string $slug Slug of character to view
     * @return void
     */
    public function viewOther($slug = null)
    {
        $sheetService = new SheetService();

        if ($this->getRequest()->is('post')) {
            $characterId = $this->getRequest()->getData('view_character_id');
            $password = $this->getRequest()->getData('password');

            $character = $sheetService->loadSheet($characterId);
            /* @var Character $character */
            if (!$character->Id) {
                $this->Flash->set('Please select a character');

                return;
            }

            $this->set('viewCharacterName', $character->CharacterName);
            $this->set('viewCharacterId', $character->Id);

            if (!$character->ViewPassword) {
                CharacterLog::logAction($character->Id, ActionType::VIEW_CHARACTER, 'Attempted to view sheet with no password set.', $this->Auth->user('user_id'));
                $this->Flash->set('Character has no view password set');

                return;
            }

            if ($character->ViewPassword !== $password) {
                CharacterLog::logAction($character->Id, ActionType::VIEW_CHARACTER, 'Attempted to view with incorrect password.', $this->Auth->user('user_id'));
                $this->Flash->set('Password does not match');

                return;
            }
            $options = [
                'show_admin' => false,
                'owner' => false,
                'edit_mode' => 'none', // other values "open", "none"
            ];

            $this->set(compact('character', 'options'));
            CharacterLog::logAction($character->Id, ActionType::VIEW_CHARACTER, 'Player View', $this->Auth->user('user_id'));
        }

        if ($this->getRequest()->is('get') && $slug) {
            $character = $sheetService->loadSheet($slug);
            $this->set('viewCharacterName', $character->CharacterName);
            $this->set('viewCharacterId', $character->Id);
        }
    }

    /**
     * @param int $characterId Character ID to view
     * @return void
     */
    public function stView($characterId = null)
    {
        $options = [
            'show_admin' => true,
            'owner' => false,
            'edit_mode' => 'open', // other values "open", "none"
        ];
        $sheetService = new SheetService();

        if ($this->getRequest()->is('post')) {
            if ($this->getRequest()->getData()['character_id']) {
                // try to update the character
                $updatedData = $this->getRequest()->getData();
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
        if ($this->getRequest()->is('get')) {
            $characterType = $this->getRequest()->getQuery('character_type');
            $character = null;
            if ($this->getRequest()->getQuery('view_character_id')) {
                // attempt to load the character
                $character = $sheetService->loadSheet($this->getRequest()->getQuery('view_character_id'), $characterType);
                if (!$character->Id) {
                    $this->Flash->set('Unable to find character');
                }
            } elseif ($characterId) {
                $character = $sheetService->loadSheet($characterId, $characterType);
                if (!$character->Id) {
                    $this->Flash->set('Unable to find character');
                }
            }

            if ($character && $character->Id) {
                CharacterLog::logAction($character->Id, ActionType::VIEW_CHARACTER, 'ST View', $this->Auth->user('user_id'));
                $repo = RepositoryManager::getRepository('classes\character\data\CharacterNote');
                /* @var CharacterNoteRepository $repo */
                $characterNote = $repo->getMostRecentForCharacter($character->Id);
                if ($characterNote) {
                    $character->setLastStNote($characterNote);
                }
                $sheetService->addMinPowers($character);
                $this->set(compact('character'));
            }
        }

        $icons = $sheetService->listAvailableIcons();
        $cities = Configure::read('City.list');
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
            'owner' => true,
            'show_admin' => false,
            'edit_mode' => 'open', // other values "open", "none"
        ];
        $sheetService = new SheetService();

        if ($this->getRequest()->is('post')) {
            $character = $this->getRequest()->getData();
            $character['slug'] = Text::slug($character['city'] . ' ' . $character['character_name']);

            $result = $sheetService->saveSheet($character, $options, $this->Auth->user());

            if (is_string($result)) {
                $this->Flash->set($result);
                $this->set('data', $character);
            } else {
                $this->Flash->set('Created ' . $character['character_name'] . '.');
                $this->redirect('/characters');
            }
        } else {
            $characterType = ($this->getRequest()->getQuery('character_type'))
                ? $this->getRequest()->getQuery('character_type')
                : 'mortal';
            $character = $sheetService->initializeSheet($characterType);
            $this->set(compact('character'));
        }
        $icons = $sheetService->listAvailableIcons();
        $this->set(compact('options', 'icons'));
    }


    /**
     * @return void
     */
    public function validateName()
    {
        $id = $this->getRequest()->getQuery('id');
        $characterName = $this->getRequest()->getQuery('name');
        $city = $this->getRequest()->getQuery('city');

        $success = false;
        $in_use = true;

        if ($characterName && $city) {
            $in_use = $this->Characters->findNameUsedInCity($id, $characterName, $city);
            $success = true;
        }

        $this->set(compact('in_use', 'success'));
        $this->set('_serialize', ['in_use', 'success']);
    }

    /**
     * @param int $characterId
     * @return void
     */
    public function beats($characterId)
    {
        $sheetService = new SheetService();
        $beatService = new BeatService();
        $character = $sheetService->loadSheet($characterId);
        if (!$character || !$character->Id) {
            $this->Flash->set('Unable to find character');
            $this->redirect('/chat.php');
        }

        if (!$this->Permissions->mayEditCharacter($character->Id)) {
            CharacterLog::logAction($character->Id, ActionType::INVALID_ACCESS, 'Attempted Access to Beats', $this->Auth->user('user_id'));
            $this->Flash->set('Unable to view that character');
            $this->redirect('/');
        }

        $isSt = $this->Permissions->isST();
        $currentBeatStatus = $beatService->getBeatStatusForCharacter($character->Id);

        if ($this->getRequest()->is('post')) {
            // attempt to save
            $beat = new CharacterBeat();
            $beat->BeatTypeId = $this->getRequest()->getData(['beat_type_id']);
            $beat->Note = $this->getRequest()->getData(['note']);

            $beat->CharacterId = $character->Id;
            $beat->BeatStatusId = ($isSt) ? BeatStatus::STAFF_AWARDED : BeatStatus::NEW_BEAT;
            $beat->CreatedById = $beat->UpdatedById = $this->Auth->user('user_id');
            $beat->Created = $beat->Updated = date('Y-m-d H:i:s');
            $beat->BeatsAwarded = 0;

            if (!$beatService->addNewBeat($beat)) {
                $this->Flash->set('Error Saving Beat!');
            } else {
                $this->redirect('/characters/beats/' . $character->Slug);
            }
        }

        $beatTypeRepo = RepositoryManager::getRepository('classes\character\data\BeatType');
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

        $characterBeats = TableRegistry::getTableLocator()->get('CharacterBeats');
        $query = $characterBeats
            ->find('all')
            ->select([
                'CharacterBeats.id',
                'CharacterBeats.note',
                'CharacterBeats.created',
                'CharacterBeats.updated',
                'CharacterBeats.applied_on',
                'CharacterBeats.beats_awarded',
                'BeatTypes.name',
                'BeatStatuses.name',
                'CreatedBy.username',
                'UpdatedBy.username'
            ])
            ->where([
                'CharacterBeats.character_id' => $character->Id
            ])
            ->contain([
                'Characters',
                'BeatTypes',
                'BeatStatuses',
                'CreatedBy',
                'UpdatedBy'
            ]);

        $pastBeats = $this->paginate($query, [
            'limit' => 20,
            'order' => [
                'CharacterBeats.created DESC',
            ]
        ]);
        if ($isSt) {
            $submenu = $this->Menu->createStorytellerMenu();
        } else {
            $submenu = $this->Menu->createCharacterMenu($character->Id, $character->CharacterName, $character->Slug);
        }
        $this->set(compact('character', 'beatList', 'currentBeatStatus', 'pastBeats', 'submenu'));
    }

    /**
     * @param string $slug slug of character
     * @return void
     */
    public function notes($slug = null)
    {
        $service = new SheetService();
        $character = $service->loadSheet($slug);

        if (!$character->Id) {
            $this->Flash->set('Unable to find: ' . $slug);
            $this->redirect('/');
        }
        $this->set('character', $character);

        $characterNotes = TableRegistry::getTableLocator()->get('CharacterNotes');
        $query = $characterNotes->find()
            ->select([
                'CharacterNotes.note',
                'CharacterNotes.created',
                'Users.username'
            ])
            ->contain([
                'Users'
            ])
            ->where([
                'CharacterNotes.character_id' => $character->Id
            ]);
        $this->set('rows', $this->paginate($query, [
            'limit' => 20,
            'order' => [
                'CharacterNotes.created DESC',
            ]
        ]));
    }

    /**
     * @return void
     */
    public function stBeats()
    {
        if ($this->getRequest()->is('post')) {
            $beatService = new BeatService();
            $beat = new CharacterBeat();
            $beat->CharacterId = $this->getRequest()->getData('character_id');
            $beat->BeatTypeId = $this->getRequest()->getData('beat_type_id');
            $beat->Note = $this->getRequest()->getData('note');

            $beat->CreatedById = $beat->UpdatedById = $this->Auth->user('user_id');
            $beat->Created = $beat->Updated = date('Y-m-d H:i:s');
            $beat->BeatStatusId = BeatStatus::STAFF_AWARDED;
            $beat->BeatsAwarded = 0;

            if ($beatService->addNewBeat($beat)) {
                $this->Flash->set('Granted beat');
                $this->redirect('/characters/stBeats');
            } else {
                $this->Flash->set('Error saving beat. Please try again');
            }
        }

        $beatTypeRepo = RepositoryManager::getRepository('classes\character\data\BeatType');
        $beatTypes = $beatTypeRepo->listAll();
        /* @var BeatType[] $beatTypes */
        $beatList = [];
        foreach ($beatTypes as $beatType) {
            $beatList[$beatType->Id] = $beatType->Name;
        }

        $cities = Configure::read('City.list');

        $this->set(compact('beatList', 'cities'));
    }
}
