<?php
namespace App\Model\Table;

use App\Model\Entity\CharacterBeat;
use Cake\Datasource\EntityInterface;
use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * CharacterBeats Model
 *
 * @property \App\Model\Table\CharactersTable|\Cake\ORM\Association\BelongsTo $Characters
 * @property \App\Model\Table\BeatTypesTable|\Cake\ORM\Association\BelongsTo $BeatTypes
 * @property \App\Model\Table\BeatStatusesTable|\Cake\ORM\Association\BelongsTo $BeatStatuses
 * @property \App\Model\Table\UsersTable|\Cake\ORM\Association\BelongsTo $CreatedBies
 * @property \App\Model\Table\UsersTable|\Cake\ORM\Association\BelongsTo $UpdatedBies
 *
 * @method CharacterBeat get($primaryKey, $options = [])
 * @method CharacterBeat newEntity($data = null, array $options = [])
 * @method CharacterBeat[] newEntities(array $data, array $options = [])
 * @method CharacterBeat|bool save(EntityInterface $entity, $options = [])
 * @method CharacterBeat patchEntity(EntityInterface $entity, array $data, array $options = [])
 * @method CharacterBeat[] patchEntities($entities, array $data, array $options = [])
 * @method CharacterBeat findOrCreate($search, callable $callback = null, $options = [])
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class CharacterBeatsTable extends Table
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

        $this->setTable('character_beats');
        $this->setDisplayField('id');
        $this->setPrimaryKey('id');

        $this->addBehavior('Timestamp');

        $this->belongsTo('Characters', [
            'foreignKey' => 'character_id',
            'joinType' => 'INNER'
        ]);
        $this->belongsTo('BeatTypes', [
            'foreignKey' => 'beat_type_id',
            'joinType' => 'INNER'
        ]);
        $this->belongsTo('BeatStatuses', [
            'foreignKey' => 'beat_status_id',
            'joinType' => 'INNER'
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
            ->dateTime('applied_on')
            ->allowEmpty('applied_on');

        $validator
            ->integer('beats_awarded')
            ->requirePresence('beats_awarded', 'create')
            ->notEmpty('beats_awarded');

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
        $rules->add($rules->existsIn(['character_id'], 'Characters'));
        $rules->add($rules->existsIn(['beat_type_id'], 'BeatTypes'));
        $rules->add($rules->existsIn(['beat_status_id'], 'BeatStatuses'));
        $rules->add($rules->existsIn(['created_by_id'], 'CreatedBies'));
        $rules->add($rules->existsIn(['updated_by_id'], 'UpdatedBies'));

        return $rules;
    }
}
