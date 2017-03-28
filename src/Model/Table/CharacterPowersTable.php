<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * CharacterPowers Model
 *
 * @property \Cake\ORM\Association\BelongsTo $Characters
 *
 * @method \App\Model\Entity\CharacterPower get($primaryKey, $options = [])
 * @method \App\Model\Entity\CharacterPower newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\CharacterPower[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\CharacterPower|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\CharacterPower patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\CharacterPower[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\CharacterPower findOrCreate($search, callable $callback = null, $options = [])
 */
class CharacterPowersTable extends Table
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

        $this->setTable('character_powers');
        $this->setDisplayField('power_name');
        $this->setPrimaryKey('id');

        $this->belongsTo('Characters', [
            'foreignKey' => 'character_id',
            'joinType' => 'INNER'
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
            ->requirePresence('power_type', 'create')
            ->notEmpty('power_type');

        $validator
            ->requirePresence('power_name', 'create')
            ->notEmpty('power_name');

        $validator
            ->requirePresence('power_note', 'create')
            ->notEmpty('power_note');

        $validator
            ->integer('power_level')
            ->requirePresence('power_level', 'create')
            ->notEmpty('power_level');

        $validator
            ->boolean('is_public')
            ->requirePresence('is_public', 'create')
            ->notEmpty('is_public');

        $validator
            ->allowEmpty('extra');

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

        return $rules;
    }
}
