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
use App\Model\Entity\Request;
use App\Model\Entity\RequestStatus;
use App\Model\Entity\Scene;
use App\Model\Table\BluebooksTable;
use App\Model\Table\RequestsTable;
use App\Model\Table\ScenesTable;
use Cake\Event\Event;
use Cake\ORM\TableRegistry;
use function compact;


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
        $this->set('isRequestManager', $this->Permissions->isRequestmanager());
    }

    public function admin()
    {
        $storytellerMenu = $this->Menu->createStorytellerMenu();
        $this->set('submenu', $storytellerMenu);
    }

    public function isAuthorized($user)
    {
        switch (strtolower($this->request->getParam('action'))) {
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
            case 'admin';
                return $this->Permissions->isAdmin();
                break;
        }
        return false;
    }

    public function index()
    {
        // show the user dashboard of requests
        // map to: request_dashboard.php
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
        // map to request_list.php
        if (!$characterId) {
            $this->Flash->set('No character specified');
            $this->redirect(['action' => 'index']);
        }

        $character = $this->Requests->Characters->get($characterId);
        if (!$this->Permissions->mayViewCharacter($character)) {
            $this->redirect(['action' => 'index']);
        }

        $filter = [
            'request_status_id' => $this->request->getQuery('request_status_id', 0),
            'request_type_id' => $this->request->getQuery('request_type_id', 0),
            'title' => $this->request->getQuery('title', '')
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
            $this->Requests->RequestTypes->find('list')->cache('request_stype_list');

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
        // map to request_create.php
        $request = $this->Requests->newEntity();

        $characterId = $this->request->getQuery('character_id');

        if ($this->request->is(['post', 'put'])) {
            if ($this->request->getData('action') == 'cancel') {
                if ($characterId) {
                    $this->redirect(['action' => 'character', $characterId]);
                } else {
                    $this->redirect(['action' => 'index']);
                }
            }
            $request = $this->Requests->patchEntity($request, $this->request->getData());
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
                if ($this->request->getData('action') == 'submit') {
                    $request->request_status_id = RequestStatus::Submitted;
                    $this->Requests->save($request);

                    if (!$this->RequestEmail->newRequestSubmission($request)) {
                        $this->Flash->set('Error sending notification.');
                    }
                }
                $this->redirect([
                    'action' => 'view',
                    $request->id
                ]);
            } else {
                $this->Flash->set('Error saving request. Please try again.');
            }
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
        // map to request_view.php
        $request = $this->Requests->get($id, [
            'contain' => [
                'Groups',
                'RequestNotes' => [
                    'CreatedBy' => [
                        'fields' => ['username']
                    ]
                ],
                'RequestCharacters' => [
                    'Characters' => [
                        'fields' => [
                            'character_name',
                            'slug'
                        ]
                    ],
                    'sort' => [
                        'Characters.character_name'
                    ]
                ],
                'RequestRolls' => [
                    'Rolls'
                ],
                'RequestRequests' => [
                    'FromRequest',
                    'sort' => [
                        'FromRequest.title'
                    ]
                ],
                'RequestBluebooks' => [
                    'Bluebooks',
                    'sort' => [
                        'Bluebooks.title'
                    ]
                ],
                'SceneRequests' => [
                    'Scenes',
                    'sort' => [
                        'Scenes.name'
                    ]
                ],
                'RequestStatuses',
                'RequestTypes',
                'UpdatedBy' => [
                    'fields' => [
                        'username'
                    ]
                ]
            ]
        ]);
        /* @var Request $request */

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
        if ($character->id != 0) {
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
        $this->set(compact('request', 'isRequestManager'));
    }

    public function edit($requestId)
    {
        // map to request_edit
        $request = $this->Requests->get($requestId);
        $this->mayEditRequest($request);

        if ($this->request->is(['post', 'put'])) {
            $request = $this->Requests->patchEntity($request, $this->request->getData());
            $request->updated_by_id = $this->Auth->user('user_id');

            if ($this->Requests->save($request)) {
                $this->Flash->set('Updated Request');
                return $this->redirect(['action' => 'view', $requestId]);
            } else {
                $this->Flash->set('Error updating request');
            }
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

    public function delete($requestId)
    {
        // map to request_delete
        $request = $this->Requests->get($requestId);
        $this->validateRequestEdit($request);

        if($request->request_status_id == RequestStatus::NewRequest) {
            if($this->Requests->delete($request)) {
                $this->Flash->set('Request ' . $request->title . ' has been deleted');
            } else {
                $this->Flash->set('Error deleting request');
            }
        } else {
            $this->Flash->set('Can not delete a request that has been submitted');
        }

        $character = $this->Requests->RequestCharacters->Characters->findPrimaryCharacterForRequest($requestId);
        /* @var Character $character */
        if($character->user_id == $this->Auth->user('user_id')) {
            return $this->redirect(['action' => 'character', $character->id]);
        } else {
            return $this->redirect(['action' => 'index']);
        }
    }

    public function addNote($requestId)
    {
        $requestNote = $this->Requests->RequestNotes->newEntity();
        $request = $this->Requests->get($requestId);
        $this->validateRequestView($request);
        if ($this->request->is(['post', 'put'])) {
            if (strtolower($this->request->getData('action')) == 'cancel') {
                $this->redirect(['action' => 'view', $requestId]);
            }
            $requestNote = $this->Requests->RequestNotes->patchEntity(
                $requestNote,
                $this->request->getData()
            );

            $requestNote->created_by_id = $this->Auth->user('user_id');

            if ($this->Requests->RequestNotes->save($requestNote)) {
                $request->updated_by_id = $this->Auth->user('user_id');
                $this->Requests->save($request);
                $this->redirect(['action' => 'view', $requestId]);
            } else {
                $this->Flash->set('Error adding note.');
            }
        }
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

        $this->set(compact('request', 'notes', 'requestNote'));
    }

    public function addCharacter($requestId)
    {
        $requestCharacter = $this->Requests->RequestCharacters->newEntity();
        $request = $this->Requests->get($requestId);
        $this->validateRequestView($request);
        $requestCharacter->request_id = $request->id;
        $requestCharacter->is_approved = false;
        $requestCharacter->is_primary = false;

        if ($this->request->is(['post', 'put'])) {
            if (strtolower($this->request->getData('action')) == 'cancel') {
                $this->redirect(['action' => 'view', $requestId]);
                return;
            }
            $requestCharacter = $this->Requests->RequestCharacters->patchEntity(
                $requestCharacter,
                $this->request->getData(),
                [
                    'validate' => false
                ]
            );
            if ($this->Requests->RequestCharacters->save($requestCharacter)) {
                $request->updated_by_id = $this->Auth->user('user_id');
                $this->Requests->save($request);
                $this->Flash->set('Attached ' . $this->request->getData('character_name'));
                $this->redirect(['action' => 'view', $requestId]);
            } else {
                $this->Flash->set('Error Attaching Character');
            }
        }

        $hasPrimary = $this->Requests->RequestCharacters->requestHasPrimaryCharacter($requestId);
        $this->set(compact('hasPrimary', 'requestCharacter', 'request'));
    }

    public function characterSearch()
    {
        $requestId = $this->request->getQuery('request_id');
        $onlySanctioned = $this->request->getQuery('only_sanctioned');
        $query = $this->request->getQuery('query');

        $characterTable = TableRegistry::get('Characters');
        $characters = $characterTable->find('list')
            ->where([
                'character_name like' => $query . '%',
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
        if ($this->request->is(['post', 'put'])) {
            if (strtolower($this->request->getData('action')) == 'cancel') {
                return $this->redirect(['action' => 'view', $requestId]);
            }

            $requestRequest = $this->Requests->RequestRequests->patchEntity(
                $requestRequest,
                $this->request->getData()
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
            } else {
                $this->Flash->set('Unable to attach Request');
            }
        }

        $unattachedRequests = $this->Requests->listUnattachedRequests(
            $requestId, $this->Auth->user('user_id')
        );

        $this->set(compact('request', 'requestRequest', 'unattachedRequests'));
    }

    public function attachBluebook($requestId)
    {
        $requestBluebook = $this->Requests->RequestBluebooks->newEntity();
        $request = $this->Requests->get($requestId);
        $this->validateRequestView($request);
        if ($this->request->is(['post', 'put'])) {
            if (strtolower($this->request->getData('action')) == 'cancel') {
                return $this->redirect(['action' => 'view', $requestId]);
            }
            $requestBluebook = $this->Requests->RequestBluebooks->patchEntity(
                $requestBluebook,
                $this->request->getData()
            );

            if ($this->Requests->RequestBluebooks->save($requestBluebook)) {
                $request->updated_by_id = $this->Auth->user('user_id');
                $this->Requests->save($request);
                $this->Flash->set('Attached Bluebook');
                return $this->redirect(['action' => 'view', $requestId]);
            }
        }

        $blueBooksTable = TableRegistry::get('BlueBooks');
        /* @var BluebooksTable $blueBooksTable */
        $unattachedBluebooks = $blueBooksTable->listUnattachedBluebooks(
            $requestId, $this->Auth->user('user_id')
        );

        $this->set(compact('request', 'requestBluebook', 'unattachedBluebooks'));
    }

    public function attachScene($requestId)
    {
        $sceneRequest = $this->Requests->SceneRequests->newEntity();
        $request = $this->Requests->get($requestId);
        $this->validateRequestView($request);
        if ($this->request->is(['post', 'put'])) {
            if (strtolower($this->request->getData('action')) == 'cancel') {
                return $this->redirect(['action' => 'view', $requestId]);
            }

            $sceneRequest = $this->Requests->SceneRequests->patchEntity(
                $sceneRequest, $this->request->getData(),
                ['validate' => false]
            );
            $sceneRequest->added_on = date('Y-m-d H:i:s');

            if ($this->Requests->SceneRequests->save($sceneRequest)) {
                $request->updated_by_id = $this->Auth->user('user_id');
                $this->Requests->save($request);
                $this->Flash->set('Attached scene');
                return $this->redirect(['action' => 'view', $requestId]);
            } else {
                $this->Flash->set('Unable to attach scene right now');
            }
        }

        $scenesTable = TableRegistry::get('Scenes');
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
    }

    public function stDashboard()
    {
    }

    public function stView()
    {
    }

    public function stAddNote()
    {
        // do we need?
    }

    public function stApprove()
    {
    }

    public function stReturn()
    {
    }

    public function stDeny()
    {
    }

    public function adminTimeReport()
    {
    }

    public function adminStatusReport()
    {
    }

    public function adminActivityReport()
    {
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
            $this->Flash->set('Unable to submit the rquest');
        }

        $character = $this->Requests->RequestCharacters->Characters->findPrimaryCharacterForRequest($requestId);
        /* @var Character $character */
        if($character->user_id == $this->Auth->user('user_id')) {
            return $this->redirect(['action' => 'character', $character->id]);
        } else {
            return $this->redirect(['action' => 'index']);
        }
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

    public function forward($requestId)
    {
        $request = $this->Requests->get($requestId);
        $this->validateRequestEdit($request);

        if ($this->request->is(['post', 'put'])) {
            if (strtolower($this->request->getData('action')) == 'cancel') {
                return $this->redirect(['action' => 'view', $requestId]);
            }

            $oldGroupId = $request->group_id;
            $request = $this->Requests->patchEntity($request, $this->request->getData());
            $newGroupId = $request->group_id;

            if ($oldGroupId != $newGroupId) {
                $oldGroup = $this->Requests->Groups->get($oldGroupId);
                $newGroup = $this->Requests->Groups->get($newGroupId);

                $requestNote = $this->Requests->RequestNotes->newEntity();
                $requestNote->created_by_id = $this->Auth->user('user_id');
                $requestNote->note = 'Forwarded from group: ' . $oldGroup->name . ' to group: ' . $newGroup->name;
                $requestNote->request_id = $requestId;

                $this->Requests->RequestNotes->save($requestNote);
                $this->Requests->save($request);
                $this->redirect(['action' => 'view', $requestId]);
            } else {
                $this->Flash->set('You selected the same group for your request');
            }
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

    public function stForward()
    {
        // do we need?
    }

    public function stClose()
    {
        // do we need?
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
            'sort' => [
                'RequestStatuses.created_on'
            ]
        ]);
        /* @var RequestStatus[] $history */

        $this->set(compact('request', 'history'));
    }

    public function updateRequestCharacter()
    {
    }

    private function mayViewRequest(Request $request)
    {
        return $this->Requests->isUserAttachedToRequest($request->id, $this->Auth->user('user_id'))
            || $this->Permissions->isRequestManager();
    }

    private function mayEditRequest(Request $request)
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
}
