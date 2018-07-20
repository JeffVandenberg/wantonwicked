<?php

/**
 * Created by PhpStorm.
 * User: JeffVandenberg
 * Date: 7/26/14
 * Time: 9:33 AM
 * @property mixed Permissions
 */

namespace App\Controller;

use App\Controller\Component\PermissionsComponent;
use App\Controller\Component\RequestEmailComponent;
use App\Model\Entity\Character;
use App\Model\Entity\CharacterStatus;
use App\Model\Entity\Permission;
use App\Model\Entity\Request;
use App\Model\Entity\RequestStatus;
use App\Model\Entity\Scene;
use App\Model\Entity\User;
use App\Model\Table\BluebooksTable;
use App\Model\Table\RequestsTable;
use App\Model\Table\ScenesTable;
use Cake\Event\Event;
use Cake\ORM\Locator\TableLocator;
use Cake\ORM\TableRegistry;
use classes\log\CharacterLog;
use classes\log\data\ActionType;
use function compact;
use GuzzleHttp\Query;


/**
 * @property PermissionsComponent Permissions
 * @property RequestsTable Requests
 * @property RequestEmailComponent RequestEmail
 */
class RequestsController extends AppController
{
    public $components = array(
        'Permissions',
        'RequestEmail'
    );

    public function beforeFilter(Event $event)
    {
        parent::beforeFilter($event);
        $this->Auth->deny();
        $this->set('isRequestManager', $this->Permissions->isRequestManager());
    }

    public function admin()
    {
        $storytellerMenu = $this->Menu->createStorytellerMenu();
        $this->set('submenu', $storytellerMenu);
    }

    public function isAuthorized($user)
    {
        switch (strtolower($this->getRequest()->getParam('action'))) {
            case 'index':
            case 'character':
            case 'view':
            case 'history':
            case 'add':
            case 'edit':
            case 'addnote':
            case 'addcharacter':
            case 'attachrequest':
            case 'attachbluebook':
            case 'attachscene':
            case 'charactersearch':
            case 'forward':
            case 'submit':
            case 'close':
            case 'delete':
                return $user['user_id'] != 1;
                break;
            case 'stdashboard':
            case 'stview':
            case 'setstate':
            case 'assign':
                return $this->Permissions->isRequestManager();
            case 'admin';
            case 'activityreport':
            case 'timereport':
                return $this->Permissions->isAdmin();
                break;
        }
        return false;
    }

    public function index()
    {
        $userRequestsQuery = $this->Requests->buildUserRequestQuery($this->Auth->user('user_id'));
        $this->set('userRequests', $this->Paginator->paginate(
            $userRequestsQuery,
            [
                'limit' => 20,
                'order' => [
                    'Requests.updated_on' => 'DESC'
                ]
            ]
        ));
        $characterRequests =
            $this->Requests
                ->listRequestsLinkedByCharacterToUser($this->Auth->user('user_id'));

        $this->set(compact('characterRequests'));
    }

    public function character($characterId = null)
    {
        if (!$characterId) {
            $this->Flash->set('No character specified');
            return $this->redirect(['action' => 'index']);
        }

        $character = $this->Requests->Characters->get($characterId);
        if (!$this->Permissions->mayViewCharacter($character)) {
            return $this->redirect(['action' => 'index']);
        }

        $filter = [
            'request_status_id' => $this->getRequest()->getQuery('request_status_id', 0),
            'request_type_id' => $this->getRequest()->getQuery('request_type_id', 0),
            'title' => $this->getRequest()->getQuery('title', '')
        ];

        $requestSummary = $this->Requests->getSummaryForCharacter($character->id);
        $characterRequests = $this->Requests->listByCharacterId($character->id, $filter);
        $linkedRequests = $this->Requests->listLinkedRequestsForCharacter($character->id);

        $submenu = $this->Menu->createCharacterMenu(
            $character->id,
            $character->character_name,
            $character->slug
        );
        $submenu['Help'] = [
            'link' => '#',
            'submenu' => [
                'Request System Help' => [
                    'link' => '/wiki/GameRef/GameInterfaceHelp'
                ]
            ]
        ];
        $requestStatuses =
            $this->Requests->RequestStatuses->find('list')->cache('request_status_list');
        $requestTypes =
            $this->Requests->RequestTypes->find('list')->cache('request_type_list');

        $this->set(compact('character', 'requestSummary', 'submenu', 'filter', 'requestStatuses',
            'requestTypes', 'linkedRequests'));
        $this->set('characterRequests', $this->Paginator->paginate($characterRequests, [
            'order' => [
                'Requests.updated_on' => 'DESC'
            ],
            'limit' => 10
        ]));
    }

    public function add()
    {
        $request = $this->Requests->newEntity();

        $characterId = $this->getRequest()->getQuery('character_id');

        if ($this->getRequest()->is(['post', 'put'])) {
            if ($this->getRequest()->getData('action') === 'cancel') {
                if ($characterId) {
                    return $this->redirect(['action' => 'character', $characterId]);
                }

                return $this->redirect(['action' => 'index']);
            }
            $request = $this->Requests->patchEntity($request, $this->getRequest()->getData());
            $request->character_id = 0;
            $request->updated_by_id =
            $request->created_by_id =
                $this->Auth->user('user_id');
            $request->request_status_id = RequestStatus::NewRequest;

            if ($this->Requests->save($request)) {
                if ($characterId) {
                    $reqChar = $this->Requests->RequestCharacters->newEntity();
                    $reqChar->character_id = $characterId;
                    $reqChar->request_id = $request->id;
                    $reqChar->is_primary = true;
                    $reqChar->note = '';
                    $reqChar->is_approved = true;
                    $this->Requests->RequestCharacters->save($reqChar);
                }
                if ($this->getRequest()->getData('action') === 'submit') {
                    $request->request_status_id = RequestStatus::Submitted;
                    $this->Requests->save($request);

                    if (!$this->RequestEmail->newRequestSubmission($request)) {
                        $this->Flash->set('Error sending notification.');
                    }
                }
                return $this->redirect([
                    'action' => 'view',
                    $request->id
                ]);
            }

            $this->Flash->set('Error saving request. Please try again.');
        }

        if ($characterId) {
            $character = $this->Requests->Characters->get($characterId);
            $this->set(compact('character'));
            $group = $this->Requests->Groups->getDefaultGroupForCharacter($character->id);
            if ($group) {
                $request->group_id = $group->id;
            }
        } else {
            $request->group_id = 1;
        }
        $groups = $this->Requests->Groups->find('list', [
            'conditions' => [
                'is_deleted' => 0
            ],
            'order' => [
                'name'
            ]
        ]);

        $requestTypes = $this->Requests->RequestTypes->find('list')
            ->innerJoin(
                ['GRT' => 'groups_request_types'],
                'RequestTypes.id = GRT.request_type_id'
            )
            ->where([
                'GRT.group_id' => $request->group_id
            ])
            ->order([
                'RequestTypes.name'
            ]);
        $this->set(compact('request', 'groups', 'requestTypes'));
    }

    public function view($id)
    {
        $request = $this->Requests->getFullRequest($id);
        $this->validateRequestView($request);

        if ($request->request_status_id == RequestStatus::NewRequest) {
            $this->Flash->set('This request is not yet submitted to STs.');
        }

        $character = $this->Requests->Characters->findCharacterLinkedToRequest(
            $this->Auth->user('user_id'),
            $id
        );
        /* @var Character $character */

        $menu = [];
        if ($character) {
            $menu = $this->Menu->createCharacterMenu(
                $character->id, $character->character_name, $character->slug
            );
        }

        $backLink = '/requests';
        if ($this->Permissions->isRequestManager()) {
            $backLink = '/requests/stDashboard/';
        }
        if ($character && $character->id != 0) {
            $backLink = '/requests/character/' . $character->id;
        }

        $menu['Actions'] = array(
            'link' => '#',
            'submenu' => array(
                'Back' => array(
                    'link' => $backLink
                ),
                'View History' => array(
                    'link' => '/requests/history/' . $id
                )
            )
        );
        if ($request->request_status_id == RequestStatus::NewRequest) {
            $menu['Actions']['submenu']['Edit Request'] = [
                'link' => ['action' => 'edit', $id]
            ];
        }
        if ($request->request_status_id != RequestStatus::Closed) {
            $menu['Actions']['submenu']['Forward Request'] = [
                'link' => ['action' => 'forward', $id]
            ];
            $menu['Actions']['submenu']['Close Request'] = [
                'link' => ['action' => 'close', $id]
            ];
        }
        if (in_array($request->request_status_id, RequestStatus::$PlayerSubmit)) {
            $menu['Actions']['submenu']['Submit Request'] = [
                'link' => ['action' => 'submit', $id]
            ];
        }
        if ($request->request_status_id == RequestStatus::NewRequest) {
            $menu['Actions']['submenu']['Delete Request'] = [
                'link' => ['action' => 'delete', $id]
            ];
        }

        if (!in_array($request->request_status_id, RequestStatus::$Terminal)) {
            $menu['Attach'] = [
                'link' => '#',
                'submenu' => [
                    'New Note' => [
                        'link' => ['action' => 'add-note', $id]
                    ]
                ]
            ];
            if (in_array($request->request_status_id, RequestStatus::$PlayerEdit)) {
                $menu['Attach']['submenu']['Character'] = [
                    'link' => ['action' => 'add-character', $id]
                ];
                $menu['Attach']['submenu']['Request'] = [
                    'link' => ['action' => 'attach-request', $id]
                ];
                $menu['Attach']['submenu']['Bluebook Entry'] = [
                    'link' => ['action' => 'attach-bluebook', $id]
                ];
                if ($character) {
                    $menu['Attach']['submenu']['Dice Roll'] = [
                        'link' => '/dieroller.php?action=character&character_id=' . $character->id . '&request_id=' . $id
                    ];
                }
                $menu['Attach']['submenu']['Scene'] = [
                    'link' => ['action' => 'attach-scene', $id]
                ];
            }
        }
        $this->set('submenu', $menu);
        $this->set(compact('request', 'character', 'backLink'));
    }

    public function edit($requestId)
    {
        $request = $this->Requests->get($requestId);
        $this->mayEditRequest($request);

        if ($this->getRequest()->is(['post', 'put'])) {
            $request = $this->Requests->patchEntity($request, $this->getRequest()->getData());
            $request->updated_by_id = $this->Auth->user('user_id');

            if ($this->Requests->save($request)) {
                $this->Flash->set('Updated Request');
                return $this->redirect(['action' => 'view', $requestId]);
            }

            $this->Flash->set('Error updating request');
        }

        $groups = $this->Requests->Groups->find('list', [
            'conditions' => [
                'is_deleted' => 0
            ],
            'order' => [
                'name'
            ]
        ]);

        $requestTypes = $this->Requests->RequestTypes->find('list')
            ->innerJoin(
                ['GRT' => 'groups_request_types'],
                'RequestTypes.id = GRT.request_type_id'
            )
            ->where([
                'GRT.group_id' => $request->group_id
            ])
            ->order([
                'RequestTypes.name'
            ]);

        $this->set(compact('request', 'groups', 'requestTypes'));
        return null;
    }

    public function delete($requestId)
    {
        // map to request_delete
        $request = $this->Requests->get($requestId);
        $this->validateRequestEdit($request);

        if ($request->request_status_id == RequestStatus::NewRequest) {
            if ($this->Requests->delete($request)) {
                $this->Flash->set('Request ' . $request->title . ' has been deleted');
            } else {
                $this->Flash->set('Error deleting request');
            }
        } else {
            $this->Flash->set('Can not delete a request that has been submitted');
        }

        $character = $this->Requests->RequestCharacters->Characters->findPrimaryCharacterForRequest($requestId);
        /* @var Character $character */
        if ($character->user_id == $this->Auth->user('user_id')) {
            return $this->redirect(['action' => 'character', $character->id]);
        }

        return $this->redirect(['action' => 'index']);
    }

    public function addNote($requestId)
    {
        $requestNote = $this->Requests->RequestNotes->newEntity();
        $request = $this->Requests->get($requestId);
        $this->validateRequestView($request);
        if ($this->getRequest()->is(['post', 'put'])) {
            if (strtolower($this->getRequest()->getData('action')) === 'cancel') {
                return $this->redirectToView($requestId);
            }
            $requestNote = $this->Requests->RequestNotes->patchEntity(
                $requestNote,
                $this->getRequest()->getData()
            );

            $requestNote->created_by_id = $this->Auth->user('user_id');

            if ($this->Requests->RequestNotes->save($requestNote)) {
                $request->updated_by_id = $this->Auth->user('user_id');
                $this->Requests->save($request);
                return $this->redirectToView($requestId);
            }

            $this->Flash->set('Error adding note.');
        }
        $notes = $this->listNotesForRequest($requestId);

        $this->set(compact('request', 'notes', 'requestNote'));
        return null;
    }

    public function addCharacter($requestId)
    {
        $requestCharacter = $this->Requests->RequestCharacters->newEntity();
        $request = $this->Requests->get($requestId);
        $this->validateRequestView($request);
        $requestCharacter->request_id = $request->id;
        $requestCharacter->is_approved = false;
        $requestCharacter->is_primary = false;

        if ($this->getRequest()->is(['post', 'put'])) {
            if (strtolower($this->getRequest()->getData('action')) === 'cancel') {
                return $this->redirect(['action' => 'view', $requestId]);
            }
            $requestCharacter = $this->Requests->RequestCharacters->patchEntity(
                $requestCharacter,
                $this->getRequest()->getData(),
                [
                    'validate' => false
                ]
            );
            if ($this->Requests->RequestCharacters->save($requestCharacter)) {
                $request->updated_by_id = $this->Auth->user('user_id');
                $this->Requests->save($request);
                $this->Flash->set('Attached ' . $this->getRequest()->getData('character_name'));
                return $this->redirect(['action' => 'view', $requestId]);
            }

            $this->Flash->set('Error Attaching Character');
        }

        $hasPrimary = $this->Requests->RequestCharacters->requestHasPrimaryCharacter($requestId);
        $this->set(compact('hasPrimary', 'requestCharacter', 'request'));
    }

    public function characterSearch()
    {
        $onlySanctioned = $this->getRequest()->getQuery('only_sanctioned');
        $requestId = $this->getRequest()->getQuery('request_id');

        $query = $this->getRequest()->getQuery('query');

        $characterTable = TableRegistry::getTableLocator()->get('Characters');
        $characters = $characterTable->find('list')
            ->leftJoinWith('RequestCharacters', function(\Cake\ORM\Query $q) use ($requestId) {
                return $q->where([
                    'RequestCharacters.request_id' => $requestId
                ]);
            })
            ->where([
                'character_name like' => $query . '%',
                'RequestCharacters.request_id IS NULL',
                'character_status_id !=' => CharacterStatus::Deleted
            ]);

        if ($onlySanctioned) {
            $characters->andWhere([
                'character_status_id IN' => CharacterStatus::Sanctioned
            ]);
        }
        $suggestions = [];
        foreach ($characters as $key => $value) {
            $suggestions[] = [
                'value' => $value,
                'data' => $key
            ];
        }

        $this->set(compact('query', 'suggestions'));
        $this->set('_serialize', ['query', 'suggestions']);
    }

    public function attachRequest($requestId)
    {
        $requestRequest = $this->Requests->RequestRequests->newEntity();
        $request = $this->Requests->get($requestId);
        $this->validateRequestView($request);
        if ($this->getRequest()->is(['post', 'put'])) {
            if (strtolower($this->getRequest()->getData('action')) === 'cancel') {
                return $this->redirect(['action' => 'view', $requestId]);
            }

            $requestRequest = $this->Requests->RequestRequests->patchEntity(
                $requestRequest,
                $this->getRequest()->getData()
            );
            $requestRequest->to_request_id = $requestId;

            if ($this->Requests->RequestRequests->save($requestRequest)) {
                $request->updated_by_id = $this->Auth->user('user_id');
                $this->Requests->save($request);
                $this->Flash->set("Attached Request");
                return $this->redirect([
                    'action' => 'view',
                    $requestId
                ]);
            }

            $this->Flash->set('Unable to attach Request');
        }

        $unattachedRequests = $this->Requests->listUnattachedRequests(
            $requestId, $this->Auth->user('user_id')
        );

        $this->set(compact('request', 'requestRequest', 'unattachedRequests'));
        return null;
    }

    public function attachBluebook($requestId)
    {
        $requestBluebook = $this->Requests->RequestBluebooks->newEntity();
        $request = $this->Requests->get($requestId);
        $this->validateRequestView($request);
        if ($this->getRequest()->is(['post', 'put'])) {
            if (strtolower($this->getRequest()->getData('action')) === 'cancel') {
                return $this->redirect(['action' => 'view', $requestId]);
            }
            $requestBluebook = $this->Requests->RequestBluebooks->patchEntity(
                $requestBluebook,
                $this->getRequest()->getData()
            );

            if ($this->Requests->RequestBluebooks->save($requestBluebook)) {
                $request->updated_by_id = $this->Auth->user('user_id');
                $this->Requests->save($request);
                $this->Flash->set('Attached Bluebook');
                return $this->redirect(['action' => 'view', $requestId]);
            }
        }

        $blueBooksTable = TableRegistry::getTableLocator()->get('Bluebooks');
        /* @var BluebooksTable $blueBooksTable */
        $unattachedBluebooks = $blueBooksTable->listUnattachedBluebooks(
            $requestId, $this->Auth->user('user_id')
        );

        $this->set(compact('request', 'requestBluebook', 'unattachedBluebooks'));
        return null;
    }

    public function attachScene($requestId)
    {
        $sceneRequest = $this->Requests->SceneRequests->newEntity();
        $request = $this->Requests->get($requestId);
        $this->validateRequestView($request);
        if ($this->getRequest()->is(['post', 'put'])) {
            if (strtolower($this->getRequest()->getData('action')) === 'cancel') {
                return $this->redirect(['action' => 'view', $requestId]);
            }

            $sceneRequest = $this->Requests->SceneRequests->patchEntity(
                $sceneRequest, $this->getRequest()->getData(),
                ['validate' => false]
            );
            $sceneRequest->added_on = date('Y-m-d H:i:s');

            if ($this->Requests->SceneRequests->save($sceneRequest)) {
                $request->updated_by_id = $this->Auth->user('user_id');
                $this->Requests->save($request);
                $this->Flash->set('Attached scene');
                return $this->redirect(['action' => 'view', $requestId]);
            }

            $this->Flash->set('Unable to attach scene right now');
        }

        $scenesTable = TableRegistry::getTableLocator()->get('Scenes');
        /* @var ScenesTable $scenesTable */
        $items = $scenesTable->listUnattachedScenes(
            $requestId, $this->Auth->user('user_id')
        );

        // reformat for date inclusion
        $unattachedScenes = [];
        foreach ($items as $item) {
            /* @var Scene $item */
            $unattachedScenes[$item->id] = $item->name .
                ' (' . $item->run_on_date->toDateString() . ')';
        }

        $this->set(compact('request', 'sceneRequest', 'unattachedScenes'));
        return null;
    }

    public function stDashboard(): void
    {
        $groups = $this->Requests->Groups->listStGroupsForUser($this->Auth->user('user_id'));
        /* @var Query $groups */
        $requestStatuses = [-1 => 'All'] + $this->Requests->RequestStatuses->find('list', ['order' => 'name'])->toArray();
        $requestTypes = $this->Requests->RequestTypes->find('list', ['order' => 'name']);
        $requestsQuery = $this->Requests->findByGroups($groups->toArray());

        if ($this->getRequest()->getQuery('title')) {
            $requestsQuery->andWhere([
                'Requests.title LIKE' => $this->getRequest()->getQuery('title') . '%'
            ]);
        }

        if ($this->getRequest()->getQuery('username')) {
            $requestsQuery->andWhere([
                'CreatedBy.username_clean LIKE' => strtolower($this->getRequest()->getQuery('username')) . '%'
            ]);
        }

        if ($this->getRequest()->getQuery('request_type_id')) {
            $requestsQuery->andWhere([
                'Requests.request_type_id' => $this->getRequest()->getQuery('request_type_id')
            ]);
        }

        if ($this->getRequest()->getQuery('group_id')) {
            $requestsQuery->andWhere([
                'Requests.group_id' => $this->getRequest()->getQuery('group_id')
            ]);
        }

        if ($this->getRequest()->getQuery('request_status_id')) {
            if ((int) $this->getRequest()->getQuery('request_status_id') !== -1) {
                $requestsQuery->andWhere([
                    'Requests.request_status_id' => $this->getRequest()->getQuery('request_status_id')
                ]);
            }
        } else {
            $requestsQuery->andWhere([
                'Requests.request_status_id IN' => RequestStatus::$Storyteller
            ]);
        }

        $requests = $this->Paginator->paginate(
            $requestsQuery,
            [
                'limit' => 20,
                'order' => [
                    'Requests.updated_on' => 'DESC'
                ],
                'sortWhitelist' => [
                    'Requests.title',
                    'Requests.created_on',
                    'Requests.updated_on',
                    'CreatedBy.username_clean',
                    'UpdatedBy.username_clean',
                    'AssignedUser.username_clean',
                    'Groups.name',
                    'RequestTypes.name',
                    'RequestStatuses.name',
                ]
            ]
        );

        $submenu = $this->Menu->createStorytellerMenu();
        $this->set(compact('groups', 'requests', 'requestTypes', 'requestStatuses', 'submenu'));
    }

    public function stView($requestId): void
    {
        $request = $this->Requests->getFullRequest($requestId);

        CharacterLog::LogAction($request['character_id'], ActionType::VIEW_REQUEST, 'View Request', $this->Auth->user('user_id'), $requestId);
        if ($request->request_status_id === RequestStatus::Submitted) {
            $request->request_status_id = RequestStatus::InProgress;
            $request->updated_by_id = $this->Auth->user('user_id');
            $this->Requests->save($request);
        }

        $submenu = $this->Menu->createStorytellerMenu();
        $isAdmin = $this->Permissions->isAdmin();
        $this->set(compact('request', 'submenu', 'isAdmin'));
    }

    /**
     * @param $requestId
     * @return \Cake\Network\Response|null
     * @throws \OAuth\Common\Exception\Exception
     */
    public function setState($requestId)
    {
        $request = $this->Requests->get($requestId);
        $state = $this->getRequest()->getQuery('state');
        if (!\in_array($state, ['return', 'approve', 'deny', 'close'])) {
            $this->Flash->set('Unknown state to assign');
            return $this->redirect(['action' => 'st-view', $requestId]);
        }

        if ($this->getRequest()->is(['post', 'put'])) {
            if (strtolower($this->getRequest()->getData('action')) === 'cancel') {
                return $this->redirect(['action' => 'st-view', $requestId]);
            }

            $note = $this->getRequest()->getData('note');
            if (!$note) {
                $this->Flash->set('Please include a note');
            } else {
                $request->request_status_id = RequestStatus::getIdForState($state);
                $request->updated_by_id = $this->Auth->user('user_id');

                if ($this->Requests->save($request)) {
                    // save note
                    $requestNote = $this->Requests->RequestNotes->newEntity();
                    $requestNote->created_by_id = $this->Auth->user('user_id');
                    $requestNote->request_id = $requestId;
                    $requestNote->note = $note;
                    $this->Requests->RequestNotes->save($requestNote);

                    // send email
                    $this->RequestEmail->notificationToPlayer(
                        $this->Auth->user('user_email'),
                        $this->Auth->user('username'),
                        $state,
                        $note,
                        $request
                    );
                    $this->Flash->set('Updated request');
                    return $this->redirect(['action' => 'st-view', $requestId]);
                }

                $this->Flash->set('Error updating state');
            }
        }

        $notes = $this->listNotesForRequest($requestId);
        $this->set(compact('request', 'state', 'notes'));
    }

    public function submit($requestId)
    {
        $request = $this->Requests->get($requestId);
        $this->validateRequestEdit($request);

        $request->request_status_id = RequestStatus::Submitted;
        $request->updated_by_id = $this->Auth->user('user_id');
        if ($this->Requests->save($request)) {
            $this->Flash->set('Request has been submitted.');
            if (!$this->RequestEmail->newRequestSubmission($request)) {
                $this->Flash->set('Error sending notification.');
            }
        } else {
            $this->Flash->set('Unable to submit the request');
        }

        $character = $this->Requests->RequestCharacters->Characters->findPrimaryCharacterForRequest($requestId);
        /* @var Character $character */
        if ($character->user_id == $this->Auth->user('user_id')) {
            return $this->redirect(['action' => 'character', $character->id]);
        }

        return $this->redirect(['action' => 'index']);
    }

    public function close($requestId)
    {
        $request = $this->Requests->get($requestId);
        $this->validateRequestEdit($request);

        $request->request_status_id = RequestStatus::Closed;
        $request->updated_by_id = $this->Auth->user('user_id');
        if ($this->Requests->save($request)) {
            $this->Flash->set('Closed request: ' . $request->title);
        } else {
            $this->Flash->set('Error closing request.');
        }
        return $this->redirect(['action' => 'view', $requestId]);
    }

    /**
     * @param $requestId
     * @return \Cake\Network\Response|null
     */
    public function forward($requestId)
    {
        $request = $this->Requests->get($requestId);
        $this->validateRequestEdit($request);

        if ($this->getRequest()->is(['post', 'put'])) {
            if (strtolower($this->getRequest()->getData('action')) === 'cancel') {
                return $this->redirectToView($requestId);
            }

            $oldGroupId = $request->group_id;
            $request = $this->Requests->patchEntity($request, $this->getRequest()->getData());
            $newGroupId = $request->group_id;

            if ($oldGroupId != $newGroupId) {
                $oldGroup = $this->Requests->Groups->get($oldGroupId);
                $newGroup = $this->Requests->Groups->get($newGroupId);

                $requestNote = $this->Requests->RequestNotes->newEntity();
                $requestNote->created_by_id = $this->Auth->user('user_id');
                $requestNote->note = '<p>Forwarded from group: ' . $oldGroup->name . ' to group: ' . $newGroup->name . '</p>';
                $requestNote->request_id = $requestId;

                $this->Requests->RequestNotes->save($requestNote);
                $this->Requests->save($request);
                return $this->redirectToView($requestId);
            }

            $this->Flash->set('You selected the same group for your request');
        }

        $groups = $this->Requests->Groups->find('list', [
            'conditions' => [
                'is_deleted' => 0
            ],
            'order' => [
                'name'
            ]
        ]);
        $this->set(compact('request', 'groups'));
    }

    public function history($id)
    {
        $request = $this->Requests->get($id);
        $this->validateRequestView($request);

        $character = $this->Requests->Characters->findCharacterLinkedToRequest(
            $this->Auth->user('user_id'),
            $id
        );

        if ($character) {
            /* @var Character $character */
            $submenu = $this->Menu->createCharacterMenu(
                $character->id, $character->character_name, $character->slug
            );
            $this->set('submenu', $submenu);
        }

        $history = $this->Requests->RequestStatusHistories->find('all', [
            'conditions' => [
                'RequestStatusHistories.request_id' => $request->id
            ],
            'contain' => [
                'RequestStatuses',
                'CreatedBy' => [
                    'fields' => ['username']
                ]
            ],
            'order' => [
                'RequestStatusHistories.created_on'
            ]
        ]);
        /* @var RequestStatus[] $history */

        $this->set(compact('request', 'history'));
    }

    public function activityReport()
    {
        $startDate = $this->getRequest()->getQuery('start_date', date('Y-m-d', strtotime('-7 days')));
        $endDate = $this->getRequest()->getQuery('end_date', date('Y-m-d'));

        $data = $this->Requests->RequestStatusHistories->getActivityReport($startDate, $endDate);
        $this->set(compact('startDate', 'endDate', 'data'));
    }

    public function timeReport()
    {
        $data = $this->Requests->RequestStatusHistories->getTimeReport();
        $this->set(compact('data'));
    }

    public function assign($requestId)
    {
        $request = $this->Requests->get($requestId);
        $this->validateRequestEdit($request);

        if ($this->request->is(['put', 'post'])) {
            if (strtolower($this->getRequest()->getData('action')) === 'cancel') {
                return $this->redirect(['action' => 'st-view', $requestId]);
            }

            $note = $this->getRequest()->getData('note');
            if (!$note) {
                $this->Flash->set('Please include a note');
            } else {
                $request->assigned_user_id = $this->getRequest()->getData('assigned_user_id');
                $request->updated_by = $this->Auth->user('user_id');

                if ($this->Requests->save($request)) {
                    $requestNote = $this->Requests->RequestNotes->newEntity();
                    $requestNote->created_by_id = $this->Auth->user('user_id');
                    $requestNote->request_id = $requestId;
                    $requestNote->note = $note;
                    $this->Requests->RequestNotes->save($requestNote);

                    $this->Flash->set(
                        $request->assigned_user_id ? 'Assigned Request' : 'Unassigned Request'
                    );

                    if($request->assigned_user_id && $this->getRequest()->getData('send_notice', 0)) {
                        $user = TableRegistry::get('Users')->get($request->assigned_user_id);
                        /* @var User $user */
                        $this->RequestEmail->assignedRequest($user->user_email,
                            $this->Auth->user('username'), $note, $request);
                    }
                    return $this->redirect(['action' => 'st-view', $requestId]);
                }
                $this->Flash->set('Unable to assign request. Please try again.');
            }
        }

        $staff = TableRegistry::get('Users')->listUsersWithPermission([
            Permission::$ManageRequests
        ]);
        $this->set(compact('request', 'staff'));
    }

    private function mayViewRequest(Request $request): bool
    {
        return $this->Requests->isUserAttachedToRequest($request->id, $this->Auth->user('user_id'))
            || $this->Permissions->isRequestManager();
    }

    private function mayEditRequest(Request $request): bool
    {
        return $this->Requests->isRequestCreatedByUser($request->id, $this->Auth->user('user_id'))
            || $this->Permissions->isRequestManager();
    }

    /**
     * @param Request $request
     */
    private function validateRequestView(Request $request): void
    {
        if (!$this->mayViewRequest($request)) {
            $this->Flash->set('Unable to view that request');
            $this->redirect(['action' => 'index']);
        }
    }

    private function validateRequestEdit(Request $request): void
    {
        if (!$this->mayEditRequest($request)) {
            $this->Flash->set('Unable to edit that request');
            $this->redirect(['action' => 'index']);
        }
    }

    /**
     * @param $requestId
     * @return \Cake\Network\Response|null
     */
    private function redirectToView($requestId)
    {

        if ($this->getRequest()->getQuery('st')) {
            $action = 'st-view';
        } else {
            $action = 'view';
        }
        return $this->redirect(['action' => $action, $requestId]);
    }

    /**
     * @param $requestId
     * @return \Cake\ORM\Query
     */
    private function listNotesForRequest($requestId): \Cake\ORM\Query
    {
        $notes = $this->Requests->RequestNotes->find('all', [
            'conditions' => [
                'RequestNotes.request_id' => $requestId
            ],
            'order' => [
                'created_on' => 'ASC'
            ],
            'contain' => [
                'CreatedBy' => [
                    'fields' => ['username']
                ]
            ]
        ]);
        return $notes;
    }
}
