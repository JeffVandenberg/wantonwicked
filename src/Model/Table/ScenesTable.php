<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * Scenes Model
 *
 * @property \Cake\ORM\Association\BelongsTo $RunBies
 * @property \Cake\ORM\Association\BelongsTo $CreatedBies
 * @property \Cake\ORM\Association\BelongsTo $UpdatedBies
 * @property \Cake\ORM\Association\BelongsTo $SceneStatuses
 * @property \Cake\ORM\Association\HasMany $SceneCharacters
 * @property \Cake\ORM\Association\HasMany $SceneRequests
 *
 * @method \App\Model\Entity\Scene get($primaryKey, $options = [])
 * @method \App\Model\Entity\Scene newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\Scene[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\Scene|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Scene patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\Scene[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\Scene findOrCreate($search, callable $callback = null, $options = [])
 */
class ScenesTable extends Table
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

        $this->setTable('scenes');
        $this->setDisplayField('name');
        $this->setPrimaryKey('id');

        $this->belongsTo('RunBy', [
            'foreignKey' => 'run_by_id',
            'className' => 'Users'
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
        $this->belongsTo('SceneStatuses', [
            'foreignKey' => 'scene_status_id',
            'joinType' => 'INNER'
        ]);
        $this->hasMany('SceneCharacters', [
            'foreignKey' => 'scene_id'
        ]);
        $this->hasMany('SceneRequests', [
            'foreignKey' => 'scene_id'
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
            ->allowEmpty('summary');

        $validator
            ->dateTime('run_on_date')
            ->allowEmpty('run_on_date');

        $validator
            ->allowEmpty('description');

        $validator
            ->dateTime('created_on')
            ->allowEmpty('created_on');

        $validator
            ->dateTime('updated_on')
            ->allowEmpty('updated_on');

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
        $rules->add($rules->existsIn(['run_by_id'], 'Users'));
        $rules->add($rules->existsIn(['created_by_id'], 'Users'));
        $rules->add($rules->existsIn(['updated_by_id'], 'Users'));
        $rules->add($rules->existsIn(['scene_status_id'], 'SceneStatuses'));

        return $rules;
    }
}
