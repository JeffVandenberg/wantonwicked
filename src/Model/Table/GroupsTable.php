<?php

namespace App\Model\Table;

use App\Model\Entity\Group;
use Cake\Datasource\EntityInterface;
use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * Groups Model
 *
 * @property \Cake\ORM\Association\BelongsTo $Users
 * @property \Cake\ORM\Association\BelongsTo $GroupTypes
 * @property \Cake\ORM\Association\HasMany $GroupIcons
 * @property \Cake\ORM\Association\HasMany $Requests
 * @property \Cake\ORM\Association\HasMany $StGroups
 * @property \Cake\ORM\Association\BelongsToMany $RequestTypes
 *
 * @method Group get($primaryKey, $options = [])
 * @method Group newEntity($data = null, array $options = [])
 * @method Group[] newEntities(array $data, array $options = [])
 * @method Group|bool save(EntityInterface $entity, $options = [])
 * @method Group patchEntity(EntityInterface $entity, array $data, array $options = [])
 * @method Group[] patchEntities($entities, array $data, array $options = [])
 * @method Group findOrCreate($search, callable $callback = null, $options = [])
 */
class GroupsTable extends Table
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

        $this->setTable('groups');
        $this->setDisplayField('name');
        $this->setPrimaryKey('id');

        $this->belongsTo('Users', [
            'foreignKey' => 'created_by',
            'joinType' => 'INNER'
        ]);
        $this->belongsTo('GroupTypes', [
            'foreignKey' => 'group_type_id',
            'joinType' => 'INNER'
        ]);
        $this->hasMany('GroupIcons', [
            'foreignKey' => 'group_id'
        ]);
        $this->hasMany('Requests', [
            'foreignKey' => 'group_id'
        ]);
        $this->hasMany('StGroups', [
            'foreignKey' => 'group_id'
        ]);
        $this->belongsToMany('RequestTypes', [
            'foreignKey' => 'group_id',
            'targetForeignKey' => 'request_type_id',
            'joinTable' => 'groups_request_types'
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
            ->requirePresence('name', 'create')
            ->notEmpty('name');

        $validator
            ->boolean('is_deleted')
            ->requirePresence('is_deleted', 'create')
            ->notEmpty('is_deleted');

        $validator
            ->integer('created_by')
            ->requirePresence('created_by', 'create')
            ->notEmpty('created_by');

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
        $rules->add($rules->existsIn(['group_type_id'], 'GroupTypes'));

        return $rules;
    }

    /**
     * @param $characterId
     * @return EntityInterface
     */
    public function getDefaultGroupForCharacter($characterId)
    {
        return $this->find()
            ->leftJoin(
                ['Characters' => 'characters'],
                'Groups.name = Characters.character_type'
            )
            ->where([
                'Characters.id' => $characterId
            ])
            ->first();
    }

    /**
     * @param int $userId user id
     * @return Query
     */
    public function listStGroupsForUser($userId): Query
    {
        return $this->find('list')
            ->leftJoin(
                ['StGroups' => 'st_groups'],
                'StGroups.group_id = Groups.id'
            )
            ->where([
                'StGroups.user_id' => $userId,
            ]);
    }

    /**
     * @return Query
     */
    public function findActiveGroups(): Query
    {
        return $this->find(
            'list',
            [
                'conditions' => [
                    'is_deleted' => 0,
                ],
                'order' => [
                    'name',
                ],
            ]
        );

    }
}
