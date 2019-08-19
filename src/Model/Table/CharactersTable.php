<?php

namespace App\Model\Table;

use App\Model\Entity\Character;
use App\Model\Entity\CharacterStatus;
use Cake\Cache\Cache;
use Cake\Datasource\EntityInterface;
use Cake\ORM\Association\BelongsTo;
use Cake\ORM\Association\HasMany;
use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\ORM\TableRegistry;
use Cake\Validation\Validator;
use function intval;

/**
 * Characters Model
 *
 * @property BelongsTo $Users
 * @property BelongsTo $UpdatedBy
 * @property BelongsTo $Locations
 * @property BelongsTo $CharacterStatus
 * @property HasMany $CharacterBeatRecords
 * @property HasMany $CharacterBeats
 * @property HasMany $CharacterLogins
 * @property HasMany $CharacterNotes
 * @property HasMany $CharacterPowers
 * @property HasMany $CharacterUpdates
 * @property HasMany $LogCharacters
 * @property HasMany $RequestCharacters
 * @property HasMany $Requests
 * @property HasMany $SceneCharacters
 * @property HasMany $SupporterCharacters
 * @property HasMany $Territories
 *
 * @method Character newEntity($data = null, array $options = [])
 * @method Character[] newEntities(array $data, array $options = [])
 * @method Character patchEntity(EntityInterface $entity, array $data, array $options = [])
 * @method Character[] patchEntities($entities, array $data, array $options = [])
 * @method Character findOrCreate($search, callable $callback = null, $options = [])
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
        $this->setDisplayField('character_name');
        $this->setPrimaryKey('id');

        $this->belongsTo('Users', [
            'foreignKey' => 'user_id',
            'joinType' => 'INNER'
        ]);
        $this->belongsTo('UpdatedBy', [
            'foreignKey' => 'updated_by_id',
            'className' => 'Users'
        ]);
        $this->belongsTo('CharacterStatuses', [
            'foreignKey' => 'character_status_id',
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
     * @param Validator $validator Validator instance.
     * @return Validator
     */
    public function validationDefault(Validator $validator)
    {
        $validator
            ->integer('id')
            ->allowEmptyFor('id', 'create');

        $validator
            ->requirePresence('character_name', 'create')
            ->notEmptyString('character_name');

        $validator
            ->requirePresence('show_sheet', 'create')
            ->notEmptyString('show_sheet');

        $validator
            ->requirePresence('view_password', 'create')
            ->notEmptyString('view_password');

        $validator
            ->requirePresence('character_type', 'create')
            ->notEmptyString('character_type');

        $validator
            ->requirePresence('city', 'create')
            ->notEmptyString('city');

        $validator
            ->integer('age')
            ->requirePresence('age', 'create');

        $validator
            ->requirePresence('sex', 'create')
            ->notEmptyString('sex');

        $validator
            ->integer('apparent_age')
            ->requirePresence('apparent_age', 'create')
            ->notEmptyString('apparent_age');

        $validator
            ->requirePresence('concept', 'create')
            ->notEmptyString('concept');

        $validator
            ->requirePresence('description', 'create')
            ->notEmptyString('description');

        $validator
            ->requirePresence('url', 'create')
            ->notEmptyString('url');

        $validator
            ->requirePresence('safe_place', 'create')
            ->notEmptyString('safe_place');

        $validator
            ->requirePresence('friends', 'create')
            ->notEmptyString('friends');

        $validator
            ->requirePresence('exit_line', 'create')
            ->notEmptyString('exit_line');

        $validator
            ->requirePresence('icon', 'create')
            ->notEmptyString('icon');

        $validator
            ->requirePresence('is_npc', 'create')
            ->notEmptyString('is_npc');

        $validator
            ->allowEmptyString('virtue');

        $validator
            ->allowEmptyString('vice');

        $validator
            ->requirePresence('splat1', 'create')
            ->notEmptyString('splat1');

        $validator
            ->requirePresence('splat2', 'create')
            ->notEmptyString('splat2');

        $validator
            ->requirePresence('subsplat', 'create')
            ->notEmptyString('subsplat');

        $validator
            ->integer('size')
            ->requirePresence('size', 'create');

        $validator
            ->integer('speed')
            ->requirePresence('speed', 'create');

        $validator
            ->integer('initiative_mod')
            ->requirePresence('initiative_mod', 'create');

        $validator
            ->integer('defense')
            ->requirePresence('defense', 'create');

        $validator
            ->requirePresence('armor', 'create');

        $validator
            ->integer('health')
            ->requirePresence('health', 'create');

        $validator
            ->integer('wounds_agg')
            ->requirePresence('wounds_agg', 'create');

        $validator
            ->integer('wounds_lethal')
            ->requirePresence('wounds_lethal', 'create');

        $validator
            ->integer('wounds_bashing')
            ->requirePresence('wounds_bashing', 'create');

        $validator
            ->integer('willpower_perm')
            ->requirePresence('willpower_perm', 'create');

        $validator
            ->integer('willpower_temp')
            ->requirePresence('willpower_temp', 'create');

        $validator
            ->integer('power_stat')
            ->requirePresence('power_stat', 'create');

        $validator
            ->integer('power_points')
            ->requirePresence('power_points', 'create');

        $validator
            ->integer('morality')
            ->requirePresence('morality', 'create');

        $validator
            ->requirePresence('merits', 'create')
            ->notEmptyString('merits');

        $validator
            ->requirePresence('flaws', 'create')
            ->notEmptyString('flaws');

        $validator
            ->requirePresence('equipment_public', 'create')
            ->notEmptyString('equipment_public');

        $validator
            ->requirePresence('equipment_hidden', 'create')
            ->notEmptyString('equipment_hidden');

        $validator
            ->requirePresence('public_effects', 'create')
            ->notEmptyString('public_effects');

        $validator
            ->requirePresence('history', 'create')
            ->notEmptyString('history');

        $validator
            ->requirePresence('character_notes', 'create')
            ->notEmptyString('character_notes');

        $validator
            ->requirePresence('goals', 'create')
            ->notEmptyString('goals');

        $validator
            ->numeric('current_experience')
            ->requirePresence('current_experience', 'create');

        $validator
            ->numeric('total_experience')
            ->requirePresence('total_experience', 'create');

        $validator
            ->integer('bonus_received')
            ->requirePresence('bonus_received', 'create');

        $validator
            ->dateTime('updated_on')
            ->allowEmptyDateTime('updated_on');

        $validator
            ->requirePresence('gm_notes', 'create')
            ->notEmptyString('gm_notes');

        $validator
            ->requirePresence('sheet_update', 'create')
            ->notEmptyString('sheet_update');

        $validator
            ->requirePresence('hide_icon', 'create')
            ->notEmptyString('hide_icon');

        $validator
            ->requirePresence('helper', 'create')
            ->notEmptyString('helper');

        $validator
            ->requirePresence('status', 'create')
            ->notEmptyString('status');

        $validator
            ->requirePresence('bonus_attribute', 'create')
            ->notEmptyString('bonus_attribute');

        $validator
            ->requirePresence('misc_powers', 'create')
            ->notEmptyString('misc_powers');

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
            ->requirePresence('temporary_health_levels', 'create');

        $validator
            ->boolean('is_suspended')
            ->requirePresence('is_suspended', 'create');

        $validator
            ->requirePresence('gameline', 'create')
            ->notEmptyString('gameline');

        $validator
            ->requirePresence('slug', 'create')
            ->notEmptyString('slug');

        return $validator;
    }

    /**
     * Returns a rules checker object that will be used for validating
     * application integrity.
     *
     * @param RulesChecker $rules The rules object to be modified.
     * @return RulesChecker
     */
    public function buildRules(RulesChecker $rules)
    {
        $rules->add($rules->existsIn(['user_id'], 'Users'));
        $rules->add($rules->existsIn(['updated_by_id'], 'Users'));
        $rules->add($rules->existsIn(['location_id'], 'Locations'));
        $rules->add($rules->existsIn(['character_status_id'], 'CharacterStatuses'));

        return $rules;
    }

    /**
     * @param bool $onlySanctioned only list sonctioned characters
     * @return array
     */
    public function listCharacterTypes(bool $onlySanctioned)
    {
        $query = $this
            ->find('all')
            ->select([
                'Characters.character_type'
            ])
            ->distinct([
                'Characters.character_type'
            ])
            ->order([
                'Characters.character_type'
            ]);
        if ($onlySanctioned) {
            $query->where([
                'Characters.character_status_id IN' => CharacterStatus::Sanctioned
            ]);
        }

        $list = [];
        foreach ($query->toArray() as $row) {
            $list[$row->character_type] = $row->character_type;
        }

        return $list;
    }

    public function findNameUsedInCity($id, $name, $city)
    {
        $query = $this
            ->find()
            ->select([
                'total' => 'COUNT(*)'
            ])
            ->where([
                'Characters.character_name' => $name,
                'Characters.city' => $city
            ]);
        if ($id) {
            $query->where([
                'Characters.id != ' => $id
            ]);
        }

        $result = $query->first();

        return $result['total'] > 0;
    }

    public function listBarelyPlaying()
    {
        $statuses = implode(',', CharacterStatus::Sanctioned);
        $query = <<<EOQ
SELECT
  *
FROM
  (
    SELECT
        LC.character_id,
        C.character_name,
        date_format(created, '%y') AS `year`,
        date_format(created, '%m') AS `month`,
        count(*) AS total
    FROM
      log_characters AS LC
      INNER JOIN characters AS C ON LC.character_id = C.id
    WHERE
      action_type_id = 2
      AND C.is_npc = 'N'
      AND C.character_status_id IN ($statuses)
    GROUP BY
      character_id,
      `year`,
      `month`
  ) AS activity
WHERE
  total < 3
ORDER BY
  character_name,
  `year`,
  `month`
EOQ;

        return $this->getConnection()->execute($query)->fetchAll('assoc');
    }

    public function listAllLoginActivity()
    {
        $statuses = implode(',', CharacterStatus::Sanctioned);

        $query = <<<EOQ
SELECT
  *
FROM
  (
    SELECT
        LC.character_id,
        C.character_name,
        date_format(created, '%y') AS `year`,
        date_format(created, '%m') AS `month`,
        count(*) AS total
    FROM
      log_characters AS LC
      INNER JOIN characters AS C ON LC.character_id = C.id
    WHERE
      action_type_id = 2
      AND created > '2015-01-01'
      AND C.is_npc = 'N'
      AND C.character_status_id IN ($statuses)
    GROUP BY
      character_id,
      `year`,
      `month`
  ) AS activity
ORDER BY
  character_name,
  `year`,
  `month`
EOQ;
        return $this->getConnection()->execute($query)->fetchAll('assoc');
    }


    /**
     * @param $characterId
     * @param null $options
     * @return EntityInterface|mixed
     */
    public function get($characterId, $options = null)
    {
        if((int)$characterId) {
            $conditions = [
                'Characters.id' => $characterId
            ];
        } else {
            $conditions = [
                'Characters.slug' => $characterId
            ];
        }
        return $this
            ->find('all', [
                'conditions' => $conditions,
                'contain' => false
            ])
            ->cache(function ($q) use ($characterId) {
                return 'character_' . $characterId . '_simple';
            })
            ->first();
    }

    public function save(EntityInterface $entity, $options = [])
    {
        $return = parent::save($entity, $options);
        Cache::delete('character_' . $entity->id . '_simple');
        Cache::delete('character_' . $entity->slug . '_simple');
        Cache::delete($entity->user_id . '_characters_home');
        return $return;
    }

    public function findCharacterLinkedToRequest($userId, $requestId)
    {
        return $this
            ->query()
            ->select([
                'Characters.id',
                'Characters.character_name',
                'Characters.slug',
            ])
            ->leftJoin(
                ['RequestCharacters' => 'request_characters'],
                'Characters.id = RequestCharacters.character_id'
            )
            ->where([
                'Characters.user_id' => $userId,
                'RequestCharacters.request_id' => $requestId
            ])
            ->first();
    }

    public function findPrimaryCharacterForRequest($requestId)
    {
        return $this->find('all')
            ->leftJoin(
                ['RequestCharacters' => 'request_characters'],
                'RequestCharacters.character_id = Characters.id'
            )
            ->where([
                'RequestCharacters.is_primary' => true,
                'RequestCharacters.request_id' => $requestId
            ])
            ->first();
    }

    /**
     * @param int $userId User ID
     * @param string $city City to search in
     * @return array|Query
     */
    public function listForHome(int $userId, string $city)
    {
        return $this
            ->find()
            ->select([
                'Characters.id',
                'Characters.character_name',
                'Characters.character_status_id',
                'Characters.slug'
            ])
            ->contain([
                'CharacterStatuses'
            ])
            ->where([
                'Characters.city' => $city,
                'Characters.character_status_id IN' => CharacterStatus::NonDeleted,
                'Characters.user_id' => $userId
            ])
            ->order([
                'CharacterStatuses.sort_order',
                'Characters.character_name'
            ])
            ->cache($userId . '_characters_home');
    }
}
