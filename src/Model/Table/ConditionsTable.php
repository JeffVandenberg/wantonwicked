<?php
namespace App\Model\Table;

use App\Model\Entity\Condition;
use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * Conditions Model
 *
 * @property \App\Model\Table\ConditionTypesTable|\Cake\ORM\Association\BelongsTo $ConditionTypes
 *
 * @method \App\Model\Entity\Condition get($primaryKey, $options = [])
 * @method \App\Model\Entity\Condition newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\Condition[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\Condition|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Condition patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\Condition[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\Condition findOrCreate($search, callable $callback = null, $options = [])
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class ConditionsTable extends Table
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

        $this->setTable('conditions');
        $this->setDisplayField('name');
        $this->setPrimaryKey('id');

        $this->addBehavior('Timestamp');

        $this->belongsTo('ConditionTypes', [
            'foreignKey' => 'condition_type_id',
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
            ->requirePresence('name', 'create')
            ->notEmpty('name');

        $validator
            ->requirePresence('source', 'create')
            ->notEmpty('source');

        $validator
            ->boolean('is_persistent')
            ->requirePresence('is_persistent', 'create')
            ->notEmpty('is_persistent');

        $validator
            ->requirePresence('description', 'create')
            ->notEmpty('description');

        $validator
            ->allowEmpty('resolution');

        $validator
            ->allowEmpty('beat');

        $validator
            ->integer('created_by')
            ->requirePresence('created_by', 'create')
            ->notEmpty('created_by');

        $validator
            ->integer('updated_by')
            ->requirePresence('updated_by', 'create')
            ->notEmpty('updated_by');

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
        $rules->add($rules->existsIn(['condition_type_id'], 'ConditionTypes'));

        return $rules;
    }

    /**
     * @param $slug
     * @return Condition
     */
    public function findCondition($slug) {
        return $this->find()
            ->contain(false)
            ->where([
                'Conditions.slug' => $slug
            ])
            ->firstOrFail();
    }
}
