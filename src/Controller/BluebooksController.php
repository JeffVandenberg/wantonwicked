<?php
namespace App\Controller;
use App\Controller\Component\PermissionsComponent;
use App\Model\Entity\CharacterStatus;
use function compact;

/**
 * Bluebooks Controller
 *
 * @property \App\Model\Table\BluebooksTable $Bluebooks
 * @property PermissionsComponent Permissions
 *
 * @method \App\Model\Entity\Bluebook[] paginate($object = null, array $settings = [])
 */
class BluebooksController extends AppController
{
    public function initialize()
    {
        parent::initialize();
    }


    /**
     * Index method
     *
     * @return \Cake\Http\Response|void
     */
    public function index()
    {
        $this->paginate = [
            'contain' => [
                'Characters' => [
                    'fields' => ['character_name']
                ],
            ],
            'conditions' => [
                'Bluebooks.created_by_id' => $this->Auth->user('user_id')
            ]
        ];
        $bluebooks = $this->paginate($this->Bluebooks, [
            'limit' => 30,
            'order' => [
                'created_on' => 'desc'
            ],
            'sortWhitelist' => [
                'title',
                'created_on',
                'updated_on',
                'Characters.character_name',
            ]
        ]);

        $characters = $this->Bluebooks->Characters->find('list', [
            'conditions' => [
                'Characters.character_status_id IN' => CharacterStatus::Sanctioned,
                'Characters.user_id' => $this->Auth->user('user_id')
            ],
            'order' => [
                'Characters.character_name'
            ]
        ]);
        $this->set(compact('bluebooks', 'characters'));
        $this->set('_serialize', ['bluebooks']);
    }

    /**
     * Character Bluebooks method
     *
     * @param $characterId
     * @return \Cake\Network\Response
     */
    public function character($characterId)
    {
        $character = $this->Bluebooks->Characters->get($characterId, ['contain' => false]);
        if(!$this->Permissions->mayViewCharacter($character)) {
            $this->Flash->set('You may not view bluebook entries for this character.');
            return $this->redirect(['action' => 'index']);
        }
        $this->paginate = [
            'contain' => [],
            'conditions' => [
                'Bluebooks.character_id' => $characterId
            ]
        ];

        $bluebooks = $this->paginate($this->Bluebooks, [
            'limit' => 30,
            'order' => [
                'created_on' => 'desc'
            ],
            'sortWhitelist' => [
                'title',
                'created_on',
                'updated_on',
            ]
        ]);

        $submenu = $this->Menu->createCharacterMenu(
            $character->id,
            $character->character_name,
            $character->slug
        );

        $this->set(compact('bluebooks', 'character', 'submenu'));
        $this->set('_serialize', ['bluebooks']);
    }

    /**
     * View method
     *
     * @param string|null $id Bluebook id.
     * @return \Cake\Network\Response
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $bluebook = $this->Bluebooks->get($id, [
            'contain' => [
                'Characters' => [
                    'fields' => ['character_name', 'user_id']
                ]
            ]
        ]);

        if(!$this->Permissions->mayViewCharacter($bluebook->character) && !$this->Permissions->isST()) {
            $this->Flash->set('You may not view this bluebook');
            return $this->redirect(['action' => 'index']);
        }

        $backLink = ['action' => 'index'];
        $this->set(compact('bluebook', 'backLink'));
        $this->set('_serialize', ['bluebook']);
    }

    /**
     * Add method
     *
     * @return \Cake\Network\Response
     */
    public function add()
    {
        $bluebook = $this->Bluebooks->newEntity();
        $characterId = $this->getRequest()->getQuery('character_id');
        $character = $this->Bluebooks->Characters->get($characterId);
        if(!$this->Permissions->mayViewCharacter($character)) {
            $this->Flash->set('Not allowed to create bluebooks for that character.');
            return $this->redirect(['action' => 'index']);
        }

        if ($this->getRequest()->is('post')) {
            if ($this->getRequest()->getData('action') == 'cancel') {
                if ($characterId) {
                    return $this->redirect(['action' => 'character', $characterId]);
                } else {
                    return $this->redirect(['action' => 'index']);
                }
            }
            $bluebook = $this->Bluebooks->patchEntity($bluebook, $this->getRequest()->getData());
            $bluebook->character_id = $characterId;
            $bluebook->created_by_id = $bluebook->updated_by_id = $this->Auth->user('user_id');

            if ($this->Bluebooks->save($bluebook)) {
                $this->Flash->success(__('The bluebook has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The bluebook could not be saved. Please, try again.'));
        }
        $this->set(compact('bluebook', 'character'));
        $this->set('_serialize', ['bluebook']);
    }

    /**
     * Edit method
     *
     * @param string|null $id Bluebook id.
     * @return \Cake\Network\Response
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $bluebook = $this->Bluebooks->get($id, [
            'contain' => [
                'Characters'
            ]
        ]);

        if(!$this->Permissions->mayViewCharacter($bluebook->character)) {
            $this->Flash->set('Not allowed to edit bluebooks for that character.');
            return $this->redirect(['action' => 'index']);
        }

        if ($this->getRequest()->is(['patch', 'post', 'put'])) {
            $bluebook = $this->Bluebooks->patchEntity($bluebook, $this->getRequest()->getData());
            $bluebook->updated_by_id = $this->Auth->user('user_id');

            if ($this->Bluebooks->save($bluebook)) {
                $this->Flash->success(__('The bluebook has been saved.'));
                return $this->redirect(['action' => 'view', $id]);
            } else {
                $this->Flash->error(__('The bluebook could not be saved. Please, try again.'));
            }
        }

        $this->set(compact('bluebook'));
        $this->set('_serialize', ['bluebook']);
    }

    public function isAuthorized($user)
    {
        return $user['user_id'] > 1;
    }
}
