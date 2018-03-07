<?php

namespace App\Model\Table;

use App\Model\Entity\RequestCharacter;
use Cake\Datasource\EntityInterface;
use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * RequestCharacters Model
 *
 * @property \App\Model\Table\RequestsTable|\Cake\ORM\Association\BelongsTo $Requests
 * @property \App\Model\Table\CharactersTable|\Cake\ORM\Association\BelongsTo $Characters
 *
 * @method RequestCharacter get($primaryKey, $options = [])
 * @method RequestCharacter newEntity($data = null, array $options = [])
 * @method RequestCharacter[] newEntities(array $data, array $options = [])
 * @method RequestCharacter|bool save(EntityInterface $entity, $options = [])
 * @method RequestCharacter patchEntity(EntityInterface $entity, array $data, array $options = [])
 * @method RequestCharacter[] patchEntities($entities, array $data, array $options = [])
 * @method RequestCharacter findOrCreate($search, callable $callback = null, $options = [])
 */
class RequestCharactersTable extends Table
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

        $this->setTable('request_characters');
        $this->setDisplayField('id');
        $this->setPrimaryKey('id');

        $this->belongsTo('Requests', [
            'foreignKey' => 'request_id',
            'joinType' => 'INNER'
        ]);
        $this->belongsTo('Characters', [
            'foreignKey' => 'character_id',
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
            ->requirePresence('note', 'create')
            ->notEmpty('note');

        $validator
            ->boolean('is_approved')
            ->requirePresence('is_approved', 'create')
            ->notEmpty('is_approved');

        $validator
            ->boolean('is_primary')
            ->requirePresence('is_primary', 'create')
            ->notEmpty('is_primary');

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
        $rules->add($rules->existsIn(['character_id'], 'Characters'));

        return $rules;
    }

    public function requestHasPrimaryCharacter($requestId)
    {
        return $this->find('all', [
                'where' => [
                    'RequestCharacters.request_id' => $requestId,
                    'RequestCharacters.is_primary' => 1,
                ],
                'contain' => false
            ])->count() > 0;
    }
}
