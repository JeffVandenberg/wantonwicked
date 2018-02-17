<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * RequestBluebooks Model
 *
 * @property \App\Model\Table\RequestsTable|\Cake\ORM\Association\BelongsTo $Requests
 * @property \App\Model\Table\BluebooksTable|\Cake\ORM\Association\BelongsTo $Bluebooks
 *
 * @method \App\Model\Entity\RequestBluebook get($primaryKey, $options = [])
 * @method \App\Model\Entity\RequestBluebook newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\RequestBluebook[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\RequestBluebook|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\RequestBluebook patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\RequestBluebook[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\RequestBluebook findOrCreate($search, callable $callback = null, $options = [])
 */
class RequestBluebooksTable extends Table
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

        $this->setTable('request_bluebooks');
        $this->setDisplayField('id');
        $this->setPrimaryKey('id');

        $this->belongsTo('Requests', [
            'foreignKey' => 'request_id',
            'joinType' => 'INNER'
        ]);
        $this->belongsTo('Bluebooks', [
            'foreignKey' => 'bluebook_id',
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
        $rules->add($rules->existsIn(['bluebook_id'], 'Bluebooks'));

        return $rules;
    }
}
