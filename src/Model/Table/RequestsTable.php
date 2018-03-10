<?php
namespace App\Model\Table;

use App\Model\Entity\Request;
use App\Model\Entity\RequestStatus;
use App\Model\Entity\RequestType;
use Cake\Datasource\EntityInterface;
use Cake\ORM\Association\BelongsTo;
use Cake\ORM\Association\HasMany;
use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * Requests Model
 *
 * @property GroupsTable|BelongsTo $Groups
 * @property CharactersTable|BelongsTo $Characters
 * @property RequestTypesTable|BelongsTo $RequestTypes
 * @property RequestStatusesTable|BelongsTo $RequestStatuses
 * @property UsersTable|BelongsTo $CreatedBies
 * @property UsersTable|BelongsTo $UpdatedBies
 * @property RequestBluebooksTable|HasMany $RequestBluebooks
 * @property RequestCharactersTable|HasMany $RequestCharacters
 * @property RequestNotesTable|HasMany $RequestNotes
 * @property RequestRollsTable|HasMany $RequestRolls
 * @property RequestStatusHistoriesTable|HasMany $RequestStatusHistories
 * @property SceneRequestsTable|HasMany $SceneRequests
 * @property RequestRequestsTable $RequestRequests
 *
 * @method Request get($primaryKey, $options = [])
 * @method Request newEntity($data = null, array $options = [])
 * @method Request[] newEntities(array $data, array $options = [])
 * @method Request patchEntity(EntityInterface $entity, array $data, array $options = [])
 * @method Request[] patchEntities($entities, array $data, array $options = [])
 * @method Request findOrCreate($search, callable $callback = null, $options = [])
 */
class RequestsTable extends Table
{

    /**
     * Initialize method
     *
     * @param array $config The configuration for the Table.
     * @return void
     */
    public function initialize(array $config)
    {
        parent::initialize($config);

        $this->setTable('requests');
        $this->setDisplayField('title');
        $this->setPrimaryKey('id');

        $this->addBehavior('Timestamp', [
            'events' => [
                'Model.beforeSave' => [
                    'created_on' => 'new',
                    'updated_on' => 'always',
                ]
            ]
        ]);

        $this->belongsTo('Groups', [
            'foreignKey' => 'group_id',
            'joinType' => 'INNER'
        ]);
        $this->belongsTo('Characters', [
            'foreignKey' => 'character_id',
            'joinType' => 'INNER'
        ]);
        $this->belongsTo('RequestTypes', [
            'foreignKey' => 'request_type_id',
            'joinType' => 'INNER'
        ]);
        $this->belongsTo('RequestStatuses', [
            'foreignKey' => 'request_status_id',
            'joinType' => 'INNER'
        ]);
        $this->belongsTo('CreatedBy', [
            'foreignKey' => 'created_by_id',
            'joinType' => 'INNER',
            'className' => 'Users'
        ]);
        $this->belongsTo('UpdatedBy', [
            'foreignKey' => 'updated_by_id',
            'joinType' => 'INNER',
            'className' => 'Users'
        ]);
        $this->hasMany('RequestBluebooks', [
            'foreignKey' => 'request_id'
        ]);
        $this->hasMany('RequestCharacters', [
            'foreignKey' => 'request_id',
            'joinType' => 'LEFT'
        ]);
        $this->hasMany('RequestNotes', [
            'foreignKey' => 'request_id'
        ]);
        $this->hasMany('RequestRolls', [
            'foreignKey' => 'request_id'
        ]);
        $this->hasMany('RequestStatusHistories', [
            'foreignKey' => 'request_id'
        ]);
        $this->hasMany('SceneRequests', [
            'foreignKey' => 'request_id'
        ]);

        $this->hasMany('RequestRequests', [
            'foreignKey' => 'to_request_id',
            'targetForeignKey' => 'from_request_id',
            'joinTable' => 'request_requests'

        ]);
    }

    /**
     * Default validation rules.
     *
     * @param \Cake\Validation\Validator $validator Validator instance.
     * @return \Cake\Validation\Validator
     */
    public function validationDefault(Validator $validator)
    {
        $validator
            ->integer('id')
            ->allowEmpty('id', 'create');

        $validator
            ->requirePresence('title', 'create')
            ->notEmpty('title');

        $validator
            ->requirePresence('body', 'create')
            ->notEmpty('body');

        return $validator;
    }

    /**
     * Returns a rules checker object that will be used for validating
     * application integrity.
     *
     * @param \Cake\ORM\RulesChecker $rules The rules object to be modified.
     * @return \Cake\ORM\RulesChecker
     */
    public function buildRules(RulesChecker $rules)
    {
        $rules->add($rules->existsIn(['group_id'], 'Groups'));
        $rules->add($rules->existsIn(['character_id'], 'Characters'));
        $rules->add($rules->existsIn(['request_type_id'], 'RequestTypes'));
        $rules->add($rules->existsIn(['request_status_id'], 'RequestStatuses'));
        $rules->add($rules->existsIn(['created_by_id'], 'CreatedBy'));
        $rules->add($rules->existsIn(['updated_by_id'], 'UpdatedBy'));

        return $rules;
    }

    public function listRequestsLinkedByCharacterToUser($userId)
    {
        return $this->find('all')
            ->contain([
                'RequestTypes',
                'RequestStatuses',
                'UpdatedBy' => [
                    'fields' => ['username']
                ],
                'RequestCharacters' => [
                    'Characters' => [
                        'fields' => ['character_name']
                    ]
                ]
            ])
            ->leftJoin('request_characters', 'Requests.id = request_characters.request_id')
            ->leftJoin('characters', 'request_characters.character_id = characters.id')
            ->where([
                'characters.user_id' => $userId,
                'request_characters.is_primary' => 0,
                'Requests.request_type_id != ' => RequestType::BlueBook,
                'Requests.request_status_id IN ' => RequestStatus::$Player
            ])
            ->order([
                'Requests.updated_on' => 'DESC'
            ]);
    }

    public function buildUserRequestQuery($userId)
    {
        return $this->query()
            ->contain([
                'RequestTypes',
                'RequestStatuses',
                'UpdatedBy' => [
                    'fields' => ['username']
                ],
                'RequestCharacters' => [
                    'Characters' => [
                        'fields' => ['character_name']
                    ]
                ]
            ])
            ->where([
                'Requests.created_by_id' => $userId,
                'Requests.request_type_id != ' => RequestType::BlueBook,
                'Requests.request_status_id IN ' => RequestStatus::$Player
            ]);
    }

    public function getSummaryForCharacter($characterId)
    {
        return $this->query()
            ->select([
                'request_type_id' => 'rt.id',
                'request_type_name' => 'rt.name',
                'total' => 'count(r.id)'
            ])
            ->from([
                'r' => 'requests'
            ])
            ->leftJoin(
                ['rc' => 'request_characters'],
                'r.id = rc.request_id'
            )
            ->leftJoin(
                ['rt' => 'request_types'],
                'r.request_type_id = rt.id'
            )
            ->leftJoin(
                ['rs' => 'request_statuses'],
                'r.request_status_id = rs.id'
            )
            ->group([
                'rt.id',
                'rt.name'
            ])
            ->where([
                'rc.character_id' => $characterId,
                'rc.is_primary' => 1,
                'r.request_status_id IN ' => [
                    RequestStatus::Submitted,
                    RequestStatus::InProgress,
                    RequestStatus::InProgress,
                ]
            ])
            ->enableHydration(false)
            ->toArray();
    }

    public function listByCharacterId($characterId, $filter)
    {
        $query = $this->find('all')
            ->contain([
                'RequestTypes',
                'RequestStatuses',
                'UpdatedBy' => [
                    'fields' => ['username']
                ],
                'RequestCharacters' => [
                    'Characters' => [
                        'fields' => ['character_name']
                    ]
                ]
            ])
            ->leftJoin('request_characters', 'Requests.id = request_characters.request_id')
            ->leftJoin('characters', 'request_characters.character_id = characters.id')
            ->where([
                'request_characters.character_id' => $characterId,
                'request_characters.is_primary' => true
            ]);

        if ($filter['request_type_id']) {
            $query->andWhere([
                'Requests.request_type_id' => $filter['request_type_id']
            ]);
        } else {
            $query->andWhere([
                'Requests.request_type_id !=' => RequestType::BlueBook
            ]);
        }

        if ($filter['title']) {
            $query->andWhere([
                'Requests.title LIKE' => $filter['title'] . '%'
            ]);
        }

        if ($filter['request_status_id']) {
            $query->andWhere([
                'Requests.request_status_id' => $filter['request_status_id']
            ]);
        } else {
            $query->andWhere([
                'Requests.request_status_id IN' => RequestStatus::$Player
            ]);
        }

        return $query;
    }

    public function listLinkedRequestsForCharacter($characterId)
    {
        return $this
            ->find('all')
            ->contain([
                'RequestCharacters' => [
                    'Characters' => [
                        'fields' => [
                            'character_name'
                        ]
                    ]
                ]
            ])
            ->leftJoin(
                ['RequestCharacters' => 'request_characters'],
                'Requests.id = RequestCharacters.request_id'
            )
            ->leftJoin(
                ['Characters' => 'characters'],
                'RequestCharacters.character_id = Characters.id'
            )
            ->where([
                'Characters.id' => $characterId,
                'RequestCharacters.is_primary' => false,
                'Requests.request_type_id != ' => RequestType::BlueBook,
                'Requests.request_status_id IN ' => RequestStatus::$Player
            ]);
    }

    public function isUserAttachedToRequest($requestId, $userId)
    {
        $result = $this->query()
            ->select([
                'rows' => 'count(*)'
            ])
            ->leftJoinWith('RequestCharacters.Characters')
            ->where([
                'Requests.id' => $requestId,
                'OR' => [
                    'Requests.created_by_id' => $userId,
                    'Characters.user_id' => $userId
                ]
            ])
            ->enableHydration(false)
            ->firstOrFail();
        return $result['rows'] > 0;
    }

    public function listUnattachedRequests($requestId, $userId)
    {
        // get linked characters if any
        $linkedCharacter = $this->RequestCharacters->Characters->find('list')
            ->leftJoin(
                ['RequestCharacters' => 'request_characters'],
                'RequestCharacters.character_id = characters.id'
            )
            ->where([
                'RequestCharacters.request_id' => $requestId,
                'Characters.user_id' => $userId
            ])
            ->toArray();

        $unattachedRequests = $this
            ->find('list')
            ->contain(false)
            ->leftJoin(
                ['RequestRequests' => 'request_requests'],
                [
                    'Requests.id = RequestRequests.from_request_id',
                    'RequestRequests.to_request_id' => $requestId
                ]
            )
            ->where([
                'RequestRequests.to_request_id IS NULL',
                'Requests.request_type_id != ' => RequestType::BlueBook,
                'Requests.id != ' . $requestId
            ])
            ->order([
                'Requests.title'
            ]);

        if (count($linkedCharacter)) {
            $unattachedRequests
                ->leftJoin(
                    ['RequestCharacters' => 'request_characters'],
                    'Requests.id = RequestCharacters.request_id'
                )->andWhere([
                    'RequestCharacters.character_id IN' => array_keys($linkedCharacter)
                ]);
        } else {
            $unattachedRequests->andWhere([
                'Requests.created_by_id' => $userId
            ]);
        }

        return $unattachedRequests;
    }

    public function isRequestCreatedByUser($requestId, $userId)
    {
        $result = $this->query()
            ->select([
                'rows' => 'count(*)'
            ])
            ->where([
                'Requests.id' => $requestId,
                'Requests.created_by_id' => $userId,
            ])
            ->enableHydration(false)
            ->firstOrFail();
        return $result['rows'] > 0;
    }

    /**
     * @param $groups
     * @return Query
     */
    public function findByGroups($groups)
    {
        return $this->query()
            ->contain([
                'CreatedBy' => [
                    'fields' => ['username']
                ],
                'UpdatedBy' => [
                    'fields' => ['username']
                ],
                'RequestTypes',
                'Groups',
                'RequestStatuses'
            ])
            ->where([
                'Requests.group_id IN' => array_keys($groups),
                'Requests.request_type_id !=' => RequestType::BlueBook
            ]);
    }

    /**
     * @param $id
     * @return Request
     */
    public function getFullRequest($id): Request
    {
        return $this->get($id, [
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
    }

    public function save(EntityInterface $entity, $options = [])
    {
        /* @var Request $entity */
        $result = parent::save($entity, $options);
        if($result) {
            $history = $this->RequestStatusHistories->newEntity();
            $history->request_id = $entity->id;
            $history->request_status_id = $entity->request_status_id;
            $history->created_by_id = $entity->updated_by_id;
            $this->RequestStatusHistories->save($history);
        }
        return $result;
    }


}
