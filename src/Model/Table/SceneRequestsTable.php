<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * SceneRequests Model
 *
 * @property \Cake\ORM\Association\BelongsTo $Scenes
 * @property \Cake\ORM\Association\BelongsTo $Requests
 *
 * @method \App\Model\Entity\SceneRequest get($primaryKey, $options = [])
 * @method \App\Model\Entity\SceneRequest newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\SceneRequest[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\SceneRequest|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\SceneRequest patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\SceneRequest[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\SceneRequest findOrCreate($search, callable $callback = null, $options = [])
 */
class SceneRequestsTable extends Table
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

        $this->setTable('scene_requests');
        $this->setDisplayField('id');
        $this->setPrimaryKey('id');

        $this->belongsTo('Scenes', [
            'foreignKey' => 'scene_id',
            'joinType' => 'INNER'
        ]);
        $this->belongsTo('Requests', [
            'foreignKey' => 'request_id',
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
            ->allowEmpty('note');

        $validator
            ->dateTime('added_on')
            ->requirePresence('added_on', 'create')
            ->notEmpty('added_on');

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
        $rules->add($rules->existsIn(['scene_id'], 'Scenes'));
        $rules->add($rules->existsIn(['request_id'], 'Requests'));

        return $rules;
    }
}
