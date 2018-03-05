<?php
namespace App\Model\Table;

use App\Model\Entity\RequestNote;
use Cake\Datasource\EntityInterface;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * RequestNotes Model
 *
 * @property \App\Model\Table\RequestsTable|\Cake\ORM\Association\BelongsTo $Requests
 * @property \App\Model\Table\UsersTable|\Cake\ORM\Association\BelongsTo $CreatedBy
 *
 * @method RequestNote get($primaryKey, $options = [])
 * @method RequestNote newEntity($data = null, array $options = [])
 * @method RequestNote[] newEntities(array $data, array $options = [])
 * @method RequestNote|bool save(EntityInterface $entity, $options = [])
 * @method RequestNote patchEntity(EntityInterface $entity, array $data, array $options = [])
 * @method RequestNote[] patchEntities($entities, array $data, array $options = [])
 * @method RequestNote findOrCreate($search, callable $callback = null, $options = [])
 */
class RequestNotesTable extends Table
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

        $this->setTable('request_notes');
        $this->setDisplayField('id');
        $this->setPrimaryKey('id');

        $this->addBehavior('Timestamp', [
            'events' => [
                'Model.beforeSave' => [
                    'created_on' => 'new',
                ]
            ]
        ]);

        $this->belongsTo('Requests', [
            'foreignKey' => 'request_id',
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
            ->requirePresence('note', 'create')
            ->notEmpty('note');

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
        $rules->add($rules->existsIn(['created_by_id'], 'CreatedBy'));

        return $rules;
    }
}
