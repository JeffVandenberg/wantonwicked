<?php

namespace App\Model\Table;

use App\Model\Entity\Request;
use App\Model\Entity\RequestStatus;
use App\Model\Entity\RequestType;
use Cake\Datasource\EntityInterface;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\ORM\TableRegistry;
use Cake\Validation\Validator;

/**
 * Requests Model
 *
 * @property \App\Model\Table\CharactersTable|\Cake\ORM\Association\BelongsTo $Characters
 * @property \App\Model\Table\UsersTable|\Cake\ORM\Association\BelongsTo $CreatedBies
 * @property \App\Model\Table\UsersTable|\Cake\ORM\Association\BelongsTo $UpdatedBies
 * @property \App\Model\Table\RequestBluebooksTable|\Cake\ORM\Association\HasMany $RequestBluebooks
 *
 * @method Request get($primaryKey, $options = [])
 * @method Request newEntity($data = null, array $options = [])
 * @method Request[] newEntities(array $data, array $options = [])
 * @method Request|bool save(EntityInterface $entity, $options = [])
 * @method Request patchEntity(EntityInterface $entity, array $data, array $options = [])
 * @method Request[] patchEntities($entities, array $data, array $options = [])
 * @method Request findOrCreate($search, callable $callback = null, $options = [])
 */
class BluebooksTable extends Table
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

        $this->setTable('bluebooks');
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

        $this->belongsTo('Characters', [
            'foreignKey' => 'character_id',
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
        $this->hasMany('Requests', [
            'foreignKey' => 'bluebook_id'
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
        $rules->add($rules->existsIn(['character_id'], 'Characters'));
        $rules->add($rules->existsIn(['created_by_id'], 'CreatedBy'));
        $rules->add($rules->existsIn(['updated_by_id'], 'UpdatedBy'));

        return $rules;
    }

    public function listUnattachedBluebooks($requestId, $userId)
    {
        // get linked characters if any
        $linkedCharacter = TableRegistry::get('Characters')->find('list')
            ->leftJoin(
                ['RequestCharacters' => 'request_characters'],
                'RequestCharacters.character_id = Characters.id'
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
                ['RequestBluebooks' => 'request_bluebooks'],
                [
                    'Bluebooks.id = RequestBluebooks.bluebook_id',
                    'RequestBluebooks.request_id' => $requestId
                ]
            )
            ->where([
                'RequestBluebooks.request_id IS NULL',
                'Bluebooks.id != ' . $requestId
            ])
            ->order([
                'Bluebooks.title'
            ]);

        if (count($linkedCharacter)) {
            $unattachedRequests
                ->andWhere([
                    'Bluebooks.character_id IN' => array_keys($linkedCharacter)
                ]);
        } else {
            $unattachedRequests->andWhere([
                'Bluebooks.created_by_id' => $userId
            ]);
        }

        return $unattachedRequests;
    }
}
