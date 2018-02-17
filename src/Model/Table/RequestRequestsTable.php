<?php
namespace App\Model\Table;

use App\Model\Entity\RequestRequest;
use App\Model\Table\RequestsTable;
use Cake\Datasource\EntityInterface;
use Cake\ORM\Association\BelongsTo;
use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * RequestRequests Model
 *
 * @property RequestsTable|BelongsTo $FromRequests
 * @property RequestsTable|BelongsTo $ToRequests
 *
 * @method RequestRequest get($primaryKey, $options = [])
 * @method RequestRequest newEntity($data = null, array $options = [])
 * @method RequestRequest[] newEntities(array $data, array $options = [])
 * @method RequestRequest|bool save(EntityInterface $entity, $options = [])
 * @method RequestRequest patchEntity(EntityInterface $entity, array $data, array $options = [])
 * @method RequestRequest[] patchEntities($entities, array $data, array $options = [])
 * @method RequestRequest findOrCreate($search, callable $callback = null, $options = [])
 */
class RequestRequestsTable extends Table
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

        $this->setTable('request_requests');
        $this->setDisplayField('id');
        $this->setPrimaryKey('id');

        $this->belongsTo('FromRequest', [
            'foreignKey' => 'from_request_id',
            'joinType' => 'INNER',
            'className' => 'Requests'
        ]);
        $this->belongsTo('ToRequest', [
            'foreignKey' => 'to_request_id',
            'joinType' => 'INNER',
            'className' => 'Requests'
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
        $rules->add($rules->existsIn(['from_request_id'], 'FromRequests'));
        $rules->add($rules->existsIn(['to_request_id'], 'ToRequests'));

        return $rules;
    }
}
