<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * Groups Model
 *
 * @property \Cake\ORM\Association\BelongsTo $GroupTypes
 * @property \Cake\ORM\Association\HasMany $GroupIcons
 * @property \Cake\ORM\Association\HasMany $PhpbbAclGroups
 * @property \Cake\ORM\Association\HasMany $PhpbbExtensionGroups
 * @property \Cake\ORM\Association\HasMany $PhpbbExtensions
 * @property \Cake\ORM\Association\HasMany $PhpbbGroups
 * @property \Cake\ORM\Association\HasMany $PhpbbModeratorCache
 * @property \Cake\ORM\Association\HasMany $PhpbbTeampage
 * @property \Cake\ORM\Association\HasMany $PhpbbUserGroup
 * @property \Cake\ORM\Association\HasMany $PhpbbUsers
 * @property \Cake\ORM\Association\HasMany $Requests
 * @property \Cake\ORM\Association\HasMany $StGroups
 * @property \Cake\ORM\Association\BelongsToMany $RequestTypes
 *
 * @method \App\Model\Entity\Group get($primaryKey, $options = [])
 * @method \App\Model\Entity\Group newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\Group[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\Group|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Group patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\Group[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\Group findOrCreate($search, callable $callback = null, $options = [])
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

        $this->belongsTo('GroupTypes', [
            'foreignKey' => 'group_type_id',
            'joinType' => 'INNER'
        ]);
        $this->hasMany('GroupIcons', [
            'foreignKey' => 'group_id'
        ]);
        $this->hasMany('PhpbbAclGroups', [
            'foreignKey' => 'group_id'
        ]);
        $this->hasMany('PhpbbExtensionGroups', [
            'foreignKey' => 'group_id'
        ]);
        $this->hasMany('PhpbbExtensions', [
            'foreignKey' => 'group_id'
        ]);
        $this->hasMany('PhpbbGroups', [
            'foreignKey' => 'group_id'
        ]);
        $this->hasMany('PhpbbModeratorCache', [
            'foreignKey' => 'group_id'
        ]);
        $this->hasMany('PhpbbTeampage', [
            'foreignKey' => 'group_id'
        ]);
        $this->hasMany('PhpbbUserGroup', [
            'foreignKey' => 'group_id'
        ]);
        $this->hasMany('PhpbbUsers', [
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
}
