<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * Characters Model
 *
 * @property \Cake\ORM\Association\BelongsTo $Users
 * @property \Cake\ORM\Association\BelongsTo $UpdatedBies
 * @property \Cake\ORM\Association\BelongsTo $Locations
 * @property \Cake\ORM\Association\HasMany $CharacterBeatRecords
 * @property \Cake\ORM\Association\HasMany $CharacterBeats
 * @property \Cake\ORM\Association\HasMany $CharacterLogins
 * @property \Cake\ORM\Association\HasMany $CharacterNotes
 * @property \Cake\ORM\Association\HasMany $CharacterPowers
 * @property \Cake\ORM\Association\HasMany $CharacterUpdates
 * @property \Cake\ORM\Association\HasMany $LogCharacters
 * @property \Cake\ORM\Association\HasMany $RequestCharacters
 * @property \Cake\ORM\Association\HasMany $Requests
 * @property \Cake\ORM\Association\HasMany $SceneCharacters
 * @property \Cake\ORM\Association\HasMany $SupporterCharacters
 * @property \Cake\ORM\Association\HasMany $Territories
 *
 * @method \App\Model\Entity\Character get($primaryKey, $options = [])
 * @method \App\Model\Entity\Character newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\Character[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\Character|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Character patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\Character[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\Character findOrCreate($search, callable $callback = null, $options = [])
 */
class CharactersTable extends Table
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

        $this->setTable('characters');
        $this->setDisplayField('id');
        $this->setPrimaryKey('id');

        $this->belongsTo('Users', [
            'foreignKey' => 'user_id',
            'joinType' => 'INNER'
        ]);
        $this->belongsTo('UpdatedBies', [
            'foreignKey' => 'updated_by_id'
        ]);
        $this->hasMany('CharacterBeatRecords', [
            'foreignKey' => 'character_id'
        ]);
        $this->hasMany('CharacterBeats', [
            'foreignKey' => 'character_id'
        ]);
        $this->hasMany('CharacterLogins', [
            'foreignKey' => 'character_id'
        ]);
        $this->hasMany('CharacterNotes', [
            'foreignKey' => 'character_id'
        ]);
        $this->hasMany('CharacterPowers', [
            'foreignKey' => 'character_id'
        ]);
        $this->hasMany('CharacterUpdates', [
            'foreignKey' => 'character_id'
        ]);
        $this->hasMany('Locations', [
            'foreignKey' => 'character_id'
        ]);
        $this->hasMany('LogCharacters', [
            'foreignKey' => 'character_id'
        ]);
        $this->hasMany('RequestCharacters', [
            'foreignKey' => 'character_id'
        ]);
        $this->hasMany('Requests', [
            'foreignKey' => 'character_id'
        ]);
        $this->hasMany('SceneCharacters', [
            'foreignKey' => 'character_id'
        ]);
        $this->belongsToMany('Territories', [
            'foreignKey' => 'character_id',
            'targetForeignKey' => 'territory_id',
            'joinTable' => 'characters_territories'
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
            ->requirePresence('character_name', 'create')
            ->notEmpty('character_name');

        $validator
            ->requirePresence('show_sheet', 'create')
            ->notEmpty('show_sheet');

        $validator
            ->requirePresence('view_password', 'create')
            ->notEmpty('view_password');

        $validator
            ->requirePresence('character_type', 'create')
            ->notEmpty('character_type');

        $validator
            ->requirePresence('city', 'create')
            ->notEmpty('city');

        $validator
            ->integer('age')
            ->requirePresence('age', 'create')
            ->notEmpty('age');

        $validator
            ->requirePresence('sex', 'create')
            ->notEmpty('sex');

        $validator
            ->integer('apparent_age')
            ->requirePresence('apparent_age', 'create')
            ->notEmpty('apparent_age');

        $validator
            ->requirePresence('concept', 'create')
            ->notEmpty('concept');

        $validator
            ->requirePresence('description', 'create')
            ->notEmpty('description');

        $validator
            ->requirePresence('url', 'create')
            ->notEmpty('url');

        $validator
            ->requirePresence('safe_place', 'create')
            ->notEmpty('safe_place');

        $validator
            ->requirePresence('friends', 'create')
            ->notEmpty('friends');

        $validator
            ->requirePresence('exit_line', 'create')
            ->notEmpty('exit_line');

        $validator
            ->requirePresence('icon', 'create')
            ->notEmpty('icon');

        $validator
            ->requirePresence('is_npc', 'create')
            ->notEmpty('is_npc');

        $validator
            ->allowEmpty('virtue');

        $validator
            ->allowEmpty('vice');

        $validator
            ->requirePresence('splat1', 'create')
            ->notEmpty('splat1');

        $validator
            ->requirePresence('splat2', 'create')
            ->notEmpty('splat2');

        $validator
            ->requirePresence('subsplat', 'create')
            ->notEmpty('subsplat');

        $validator
            ->integer('size')
            ->requirePresence('size', 'create')
            ->notEmpty('size');

        $validator
            ->integer('speed')
            ->requirePresence('speed', 'create')
            ->notEmpty('speed');

        $validator
            ->integer('initiative_mod')
            ->requirePresence('initiative_mod', 'create')
            ->notEmpty('initiative_mod');

        $validator
            ->integer('defense')
            ->requirePresence('defense', 'create')
            ->notEmpty('defense');

        $validator
            ->requirePresence('armor', 'create')
            ->notEmpty('armor');

        $validator
            ->integer('health')
            ->requirePresence('health', 'create')
            ->notEmpty('health');

        $validator
            ->integer('wounds_agg')
            ->requirePresence('wounds_agg', 'create')
            ->notEmpty('wounds_agg');

        $validator
            ->integer('wounds_lethal')
            ->requirePresence('wounds_lethal', 'create')
            ->notEmpty('wounds_lethal');

        $validator
            ->integer('wounds_bashing')
            ->requirePresence('wounds_bashing', 'create')
            ->notEmpty('wounds_bashing');

        $validator
            ->integer('willpower_perm')
            ->requirePresence('willpower_perm', 'create')
            ->notEmpty('willpower_perm');

        $validator
            ->integer('willpower_temp')
            ->requirePresence('willpower_temp', 'create')
            ->notEmpty('willpower_temp');

        $validator
            ->integer('power_stat')
            ->requirePresence('power_stat', 'create')
            ->notEmpty('power_stat');

        $validator
            ->integer('power_points')
            ->requirePresence('power_points', 'create')
            ->notEmpty('power_points');

        $validator
            ->integer('morality')
            ->requirePresence('morality', 'create')
            ->notEmpty('morality');

        $validator
            ->requirePresence('merits', 'create')
            ->notEmpty('merits');

        $validator
            ->requirePresence('flaws', 'create')
            ->notEmpty('flaws');

        $validator
            ->requirePresence('equipment_public', 'create')
            ->notEmpty('equipment_public');

        $validator
            ->requirePresence('equipment_hidden', 'create')
            ->notEmpty('equipment_hidden');

        $validator
            ->requirePresence('public_effects', 'create')
            ->notEmpty('public_effects');

        $validator
            ->requirePresence('history', 'create')
            ->notEmpty('history');

        $validator
            ->requirePresence('character_notes', 'create')
            ->notEmpty('character_notes');

        $validator
            ->requirePresence('goals', 'create')
            ->notEmpty('goals');

        $validator
            ->allowEmpty('is_sanctioned');

        $validator
            ->allowEmpty('asst_sanctioned');

        $validator
            ->requirePresence('is_deleted', 'create')
            ->notEmpty('is_deleted');

        $validator
            ->numeric('current_experience')
            ->requirePresence('current_experience', 'create')
            ->notEmpty('current_experience');

        $validator
            ->numeric('total_experience')
            ->requirePresence('total_experience', 'create')
            ->notEmpty('total_experience');

        $validator
            ->integer('bonus_received')
            ->requirePresence('bonus_received', 'create')
            ->notEmpty('bonus_received');

        $validator
            ->dateTime('updated_on')
            ->allowEmpty('updated_on');

        $validator
            ->requirePresence('gm_notes', 'create')
            ->notEmpty('gm_notes');

        $validator
            ->requirePresence('sheet_update', 'create')
            ->notEmpty('sheet_update');

        $validator
            ->requirePresence('hide_icon', 'create')
            ->notEmpty('hide_icon');

        $validator
            ->requirePresence('helper', 'create')
            ->notEmpty('helper');

        $validator
            ->requirePresence('status', 'create')
            ->notEmpty('status');

        $validator
            ->requirePresence('bonus_attribute', 'create')
            ->notEmpty('bonus_attribute');

        $validator
            ->requirePresence('misc_powers', 'create')
            ->notEmpty('misc_powers');

        $validator
            ->decimal('average_power_points')
            ->requirePresence('average_power_points', 'create')
            ->notEmpty('average_power_points');

        $validator
            ->decimal('power_points_modifier')
            ->requirePresence('power_points_modifier', 'create')
            ->notEmpty('power_points_modifier');

        $validator
            ->integer('temporary_health_levels')
            ->requirePresence('temporary_health_levels', 'create')
            ->notEmpty('temporary_health_levels');

        $validator
            ->boolean('is_suspended')
            ->requirePresence('is_suspended', 'create')
            ->notEmpty('is_suspended');

        $validator
            ->requirePresence('gameline', 'create')
            ->notEmpty('gameline');

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
        $rules->add($rules->existsIn(['user_id'], 'Users'));
        $rules->add($rules->existsIn(['updated_by_id'], 'Users'));
        $rules->add($rules->existsIn(['location_id'], 'Locations'));

        return $rules;
    }
}
