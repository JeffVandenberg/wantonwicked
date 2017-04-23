<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * PlayPreferences Model
 *
 * @property \Cake\ORM\Association\BelongsTo $CreatedBy
 * @property \Cake\ORM\Association\BelongsTo $UpdatedBy
 * @property \Cake\ORM\Association\HasMany $PlayPreferenceResponseHistory
 * @property \Cake\ORM\Association\HasMany $PlayPreferenceResponses
 *
 * @method \App\Model\Entity\PlayPreference get($primaryKey, $options = [])
 * @method \App\Model\Entity\PlayPreference newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\PlayPreference[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\PlayPreference|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\PlayPreference patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\PlayPreference[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\PlayPreference findOrCreate($search, callable $callback = null, $options = [])
 */
class PlayPreferencesTable extends Table
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

        $this->setTable('play_preferences');
        $this->setDisplayField('name');
        $this->setPrimaryKey('id');

        $this->belongsTo('CreatedBy', [
            'foreignKey' => 'created_by_id',
            'joinType' => 'LEFT',
            'className' => 'Users'
        ]);
        $this->belongsTo('UpdatedBy', [
            'foreignKey' => 'updated_by_id',
            'joinType' => 'LEFT',
            'className' => 'Users'
        ]);
        $this->hasMany('PlayPreferenceResponseHistory', [
            'foreignKey' => 'play_preference_id'
        ]);
        $this->hasMany('PlayPreferenceResponses', [
            'foreignKey' => 'play_preference_id'
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
            ->dateTime('created_on')
            ->requirePresence('created_on', 'create')
            ->notEmpty('created_on');

        $validator
            ->dateTime('updated_on')
            ->requirePresence('updated_on', 'create')
            ->notEmpty('updated_on');

        $validator
            ->requirePresence('description', 'create')
            ->notEmpty('description');

        $validator
            ->requirePresence('slug', 'create')
            ->notEmpty('slug');

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
        $rules->add($rules->existsIn(['created_by_id'], 'CreatedBy'));
        $rules->add($rules->existsIn(['updated_by_id'], 'UpdatedBy'));

        return $rules;
    }
}
