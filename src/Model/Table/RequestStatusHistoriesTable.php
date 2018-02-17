<?php
namespace App\Model\Table;

use App\Model\Entity\RequestStatusHistory;
use Cake\Datasource\EntityInterface;
use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * RequestStatusHistories Model
 *
 * @property \App\Model\Table\RequestsTable|\Cake\ORM\Association\BelongsTo $Requests
 * @property \App\Model\Table\RequestStatusesTable|\Cake\ORM\Association\BelongsTo $RequestStatuses
 * @property \App\Model\Table\UsersTable|\Cake\ORM\Association\BelongsTo $CreatedBies
 *
 * @method RequestStatusHistory get($primaryKey, $options = [])
 * @method RequestStatusHistory newEntity($data = null, array $options = [])
 * @method RequestStatusHistory[] newEntities(array $data, array $options = [])
 * @method RequestStatusHistory|bool save(EntityInterface $entity, $options = [])
 * @method RequestStatusHistory patchEntity(EntityInterface $entity, array $data, array $options = [])
 * @method RequestStatusHistory[] patchEntities($entities, array $data, array $options = [])
 * @method RequestStatusHistory findOrCreate($search, callable $callback = null, $options = [])
 */
class RequestStatusHistoriesTable extends Table
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

        $this->setTable('request_status_histories');
        $this->setDisplayField('id');
        $this->setPrimaryKey('id');

        $this->belongsTo('Requests', [
            'foreignKey' => 'request_id',
            'joinType' => 'INNER'
        ]);
        $this->belongsTo('RequestStatuses', [
            'foreignKey' => 'request_status_id',
            'joinType' => 'INNER'
        ]);
        $this->belongsTo('CreatedBy', [
            'foreignKey' => 'created_by_id',
            'joinType' => 'INNER',
            'className' => 'Users'
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
            ->dateTime('created_on')
            ->requirePresence('created_on', 'create')
            ->notEmpty('created_on');

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
        $rules->add($rules->existsIn(['request_status_id'], 'RequestStatuses'));
        $rules->add($rules->existsIn(['created_by_id'], 'CreatedBy'));

        return $rules;
    }
}
