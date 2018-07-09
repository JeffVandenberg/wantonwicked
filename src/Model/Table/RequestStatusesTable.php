<?php
namespace App\Model\Table;

use Cake\Datasource\EntityInterface;
use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * RequestStatuses Model
 *
 * @property \App\Model\Table\RequestStatusHistoriesTable|\Cake\ORM\Association\HasMany $RequestStatusHistories
 * @property \App\Model\Table\RequestsTable|\Cake\ORM\Association\HasMany $Requests
 *
 * @method \App\Model\Entity\RequestStatus get($primaryKey, $options = [])
 * @method \App\Model\Entity\RequestStatus newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\RequestStatus[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\RequestStatus patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\RequestStatus[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\RequestStatus findOrCreate($search, callable $callback = null, $options = [])
 */
class RequestStatusesTable extends Table
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

        $this->setTable('request_statuses');
        $this->setDisplayField('name');
        $this->setPrimaryKey('id');

        $this->hasMany('RequestStatusHistories', [
            'foreignKey' => 'request_status_id'
        ]);
        $this->hasMany('Requests', [
            'foreignKey' => 'request_status_id'
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

        return $validator;
    }

    public function save(EntityInterface $entity, $options = [])
    {
        Cache::delete('request_status_list');
        return parent::save($entity, $options);
    }
}
