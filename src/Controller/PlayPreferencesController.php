<?php

namespace App\Controller;

use App\Controller\Component\MenuComponent;
use App\Controller\Component\PermissionsComponent;
use App\Model\Entity\PlayPreference;
use App\Model\Table\CharactersTable;
use App\Model\Table\PlayPreferenceResponsesTable;
use App\Model\Table\PlayPreferencesTable;
use Cake\Controller\Component\PaginatorComponent;
use Cake\Event\Event;
use Cake\Network\Exception\NotFoundException;
use Cake\ORM\TableRegistry;
use Cake\Utility\Text;


/**
 * PlayPreferences Controller
 *
 * @property PaginatorComponent $Paginator
 * @property PermissionsComponent Permissions
 * @property MenuComponent Menu
 * @property PlayPreferencesTable PlayPreferences
 */
class PlayPreferencesController extends AppController
{
    public function beforeFilter(Event $event)
    {
        parent::beforeFilter($event);
    }

    public function beforeRender(Event $event)
    {
        parent::beforeRender($event);
        $this->set('isHead', $this->Permissions->IsHead());
    }

    /**
     * Components
     *
     * @var array
     */
    public $components = array('Paginator');

    /**
     * index method
     *
     * @return void
     */
    public function index()
    {
        $playerPrefs = TableRegistry::get('PlayPreferenceResponses');
        /* @var PlayPreferenceResponsesTable $playerPrefs */
        $preferences = $playerPrefs->listByUserId($this->Auth->user('user_id'));
        $this->set(compact('preferences'));
        $this->set('isSt', $this->Permissions->IsST());
    }

    public function respond()
    {
        $playerPrefs = TableRegistry::get('PlayPreferenceResponses');
        /* @var PlayPreferenceResponsesTable $playerPrefs */

        if ($this->request->is('post')) {

            if ($playerPrefs->updateUserResponse(
                $this->Auth->user('user_id'),
                $this->request->getData())
            ) {
                $this->Flash->set('Updated Your Play Preferences');
                $this->redirect(['action' => 'index']);
            } else {
                $this->Flash->set('Error Updating Play Preferences');
            }
        }
        $userPreferences = $playerPrefs->listByUserId($this->Auth->user('user_id'));
        $userPrefs = [];
        foreach ($userPreferences as $userPreference) {
            $userPrefs[$userPreference->play_preference_id] =
                $userPreference->rating;
        }

        $playPreferences = TableRegistry::get('PlayPreferences');
        /* @var PlayPreferencesTable $playPreferences */
        $preferences = $playPreferences
            ->find()
            ->order([
                'PlayPreferences.name'
            ])
            ->toArray();
        $this->set(compact('preferences', 'userPrefs'));
    }

    public function manage()
    {
        $this->set('playPreferences', $this->paginate($this->PlayPreferences, [
            'order' => [
                'PlayPreferences.name'
            ],
        ]));
        $this->set('isSt', $this->Permissions->IsST());
    }

    public function report_aggregate()
    {
        $this->set(
            'report',
            $this->PlayPreference->getAggregateReport()
        );
        $this->set(
            'submenu',
            $this->Menu->createStorytellerMenu()
        );
    }

    public function reportVenue($venue = 'All', $playPreferenceId = 'All')
    {
        $playerPrefs = TableRegistry::get('PlayPreferenceResponses');
        /* @var PlayPreferenceResponsesTable $playerPrefs */

        $this->set(
            'report',
            $playerPrefs->getVenueReport($venue, $playPreferenceId)
        );

        $this->set(
            'submenu',
            $this->Menu->createStorytellerMenu()
        );

        $characterTable = TableRegistry::get('Characters');
        /* @var CharactersTable $characterTable */
        $types = ['all' => 'All'];
        $types += $characterTable->listCharacterTypes(true);

        $this->set('characterTypes', $types);
        $this->set('venue', $venue);
        $playPreferences = [
            'all' => 'All'
        ];
        $preferences = $this->PlayPreferences->find('list')->order(['PlayPreferences.name'])->toArray();
        $playPreferences += $preferences;
        $this->set(compact('playPreferences', 'playPreferenceId'));
    }

    public function reportVenuePlayers($venue, $playPreferenceSlug)
    {
        $playerPrefs = TableRegistry::get('PlayPreferenceResponses');
        /* @var PlayPreferenceResponsesTable $playerPrefs */

        $this->set(
            'report',
            $playerPrefs->getVenuePlayerReport($venue, $playPreferenceSlug)
        );
        $this->set(
            'submenu',
            $this->Menu->createStorytellerMenu()
        );

        $playPreference = $this->PlayPreferences
            ->find()
            ->select([
                'PlayPreferences.name'
            ])
            ->where([
                'PlayPreferences.slug' => $playPreferenceSlug
            ])
            ->first();

        $this->set('playPreferenceName', $playPreference->name);
        $this->set(compact('venue'));
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
        $this->set('playPreference', $this->PlayPreferences->get($id, [
            'contain' => [
                'CreatedBy' => [
                    'fields' => [
                        'username'
                    ]
                ],
                'UpdatedBy' => [
                    'fields' => [
                        'username'
                    ]
                ]
            ]
        ]));
    }

    /**
     * add method
     *
     * @return void
     */
    public function add()
    {
        $playPreference = $this->PlayPreferences->newEntity();
        if ($this->request->is('post')) {
            /* @var PlayPreference $playPreference */

            $playPreference = $this->PlayPreferences->patchEntity($playPreference, $this->request->getData());
            $playPreference->created_by_id = $this->Auth->user('user_id');
            $playPreference->updated_by_id = $this->Auth->user('user_id');
            $playPreference->created_on = $playPreference->updated_on = date('Y-m-d H:i:s');

            if ($this->PlayPreferences->save($playPreference)) {
                $this->Flash->set(__('The play preference has been saved.'));
                $this->redirect(array('action' => 'manage'));
            } else {
                $this->Flash->set(__('The play preference could not be saved. Please, try again.'));
            }
        }
        $this->set(compact('playPreference'));
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
        $playPreference = $this->PlayPreferences->get($id, [
            'contain' => []
        ]);
        if ($this->request->is(array('post', 'put', 'patch'))) {
            $playPreference = $this->PlayPreferences->patchEntity($playPreference, $this->request->getData());
            $playPreference->updated_by_id = $this->Auth->user('user_id');
            $playPreference->updated_on = date('Y-m-d H:i:s');

            if ($this->PlayPreferences->save($playPreference)) {
                $this->Flash->set(__('The play preference has been saved.'));
                $this->redirect(array('action' => 'manage'));
            } else {
                $this->Flash->set(__('The play preference could not be saved. Please, try again.'));
            }
        }
        $this->set(compact('playPreference'));
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
        $this->PlayPreference->id = $id;
        if (!$this->PlayPreference->exists()) {
            throw new NotFoundException(__('Invalid play preference'));
        }
        $this->request->onlyAllow('post', 'delete');
        if ($this->PlayPreference->delete()) {
            $this->Session->setFlash(__('The play preference has been deleted.'));
        } else {
            $this->Session->setFlash(__('The play preference could not be deleted. Please, try again.'));
        }
        $this->redirect(array('action' => 'index'));
    }

    public function isAuthorized($user)
    {
        switch ($this->request->getParam('action')) {
            case 'report_aggregate':
                return true;
                break;
            case 'index':
            case 'respond':
                return ($this->Auth->user('user_id') > 1);
                break;
            default:
                return $this->Permissions->IsST();
        }

    }
}
