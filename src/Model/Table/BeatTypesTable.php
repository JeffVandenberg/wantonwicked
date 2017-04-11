<?php

namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * BeatTypes Model
 *
 * @property \Cake\ORM\Association\BelongsTo $CreatedBies
 * @property \Cake\ORM\Association\BelongsTo $UpdatedBies
 * @property \Cake\ORM\Association\HasMany $CharacterBeats
 *
 * @method \App\Model\Entity\BeatType get($primaryKey, $options = [])
 * @method \App\Model\Entity\BeatType newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\BeatType[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\BeatType|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\BeatType patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\BeatType[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\BeatType findOrCreate($search, callable $callback = null, $options = [])
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class BeatTypesTable extends Table
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

        $this->setTable('beat_types');
        $this->setDisplayField('name');
        $this->setPrimaryKey('id');

        $this->addBehavior('Timestamp', [
            'events' => [
                'Model.beforeSave' => [
                    'created' => 'new',
                    'updated' => 'always',
                ]
            ]
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
        $this->hasMany('CharacterBeats', [
            'foreignKey' => 'beat_type_id'
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
            ->integer('number_of_beats')
            ->requirePresence('number_of_beats', 'create')
            ->notEmpty('number_of_beats');

        $validator
            ->boolean('admin_only')
            ->requirePresence('admin_only', 'create')
            ->notEmpty('admin_only');

        $validator
            ->boolean('may_rollover')
            ->requirePresence('may_rollover', 'create')
            ->notEmpty('may_rollover');

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
