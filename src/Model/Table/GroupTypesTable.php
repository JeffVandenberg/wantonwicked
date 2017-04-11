<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * GroupTypes Model
 *
 * @property \Cake\ORM\Association\HasMany $Groups
 *
 * @method \App\Model\Entity\GroupType get($primaryKey, $options = [])
 * @method \App\Model\Entity\GroupType newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\GroupType[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\GroupType|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\GroupType patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\GroupType[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\GroupType findOrCreate($search, callable $callback = null, $options = [])
 */
class GroupTypesTable extends Table
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

        $this->setTable('group_types');
        $this->setDisplayField('name');
        $this->setPrimaryKey('id');

        $this->hasMany('Groups', [
            'foreignKey' => 'group_type_id'
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
            ->requirePresence('description', 'create')
            ->notEmpty('description');

        return $validator;
    }
}
