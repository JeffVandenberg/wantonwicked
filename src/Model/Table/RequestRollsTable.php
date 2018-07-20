<?php
namespace App\Model\Table;

use App\Model\Entity\RequestRoll;
use Cake\Datasource\EntityInterface;
use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * RequestRolls Model
 *
 * @property \App\Model\Table\RequestsTable|\Cake\ORM\Association\BelongsTo $Requests
 * @property \App\Model\Table\RollsTable|\Cake\ORM\Association\BelongsTo $Rolls
 *
 * @method RequestRoll get($primaryKey, $options = [])
 * @method RequestRoll newEntity($data = null, array $options = [])
 * @method RequestRoll[] newEntities(array $data, array $options = [])
 * @method RequestRoll|bool save(EntityInterface $entity, $options = [])
 * @method RequestRoll patchEntity(EntityInterface $entity, array $data, array $options = [])
 * @method RequestRoll[] patchEntities($entities, array $data, array $options = [])
 * @method RequestRoll findOrCreate($search, callable $callback = null, $options = [])
 */
class RequestRollsTable extends Table
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

        $this->setTable('request_rolls');
        $this->setDisplayField('id');
        $this->setPrimaryKey('id');

        $this->belongsTo('Requests', [
            'foreignKey' => 'request_id',
            'joinType' => 'INNER'
        ]);
        $this->belongsTo('Rolls', [
            'foreignKey' => 'roll_id',
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
        $rules->add($rules->existsIn(['request_id'], 'Requests'));
        $rules->add($rules->existsIn(['roll_id'], 'Rolls'));

        return $rules;
    }
}
