<?php

namespace App\Model\Table;

use App\Model\Entity\PlayPreferenceResponse;
use Cake\Database\Connection;
use Cake\Datasource\ConnectionManager;
use Cake\Datasource\EntityInterface;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * PlayPreferenceResponses Model
 *
 * @property \Cake\ORM\Association\BelongsTo $Users
 * @property \Cake\ORM\Association\BelongsTo $PlayPreferences
 *
 * @method PlayPreferenceResponse get($primaryKey, $options = [])
 * @method PlayPreferenceResponse newEntity($data = null, array $options = [])
 * @method PlayPreferenceResponse[] newEntities(array $data, array $options = [])
 * @method PlayPreferenceResponse|bool save(EntityInterface $entity, $options = [])
 * @method PlayPreferenceResponse patchEntity(EntityInterface $entity, array $data, array $options = [])
 * @method PlayPreferenceResponse[] patchEntities($entities, array $data, array $options = [])
 * @method PlayPreferenceResponse findOrCreate($search, callable $callback = null, $options = [])
 */
class PlayPreferenceResponsesTable extends Table
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

        $this->setTable('play_preference_responses');
        $this->setDisplayField('id');
        $this->setPrimaryKey('id');

        $this->belongsTo('Users', [
            'foreignKey' => 'user_id',
            'joinType' => 'INNER'
        ]);
        $this->belongsTo('PlayPreferences', [
            'foreignKey' => 'play_preference_id',
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
            ->integer('rating')
            ->requirePresence('rating', 'create')
            ->notEmpty('rating');

        $validator
            ->requirePresence('note', 'create')
            ->notEmpty('note');

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
        $rules->add($rules->existsIn(['user_id'], 'Users'));
        $rules->add($rules->existsIn(['play_preference_id'], 'PlayPreferences'));

        return $rules;
    }

    public function reportResponsesForPlayersInScene($sceneId)
    {
        $sql = <<<EOQ
SELECT
 PP.id,
 PP.name,
 IFNULL((
  SELECT
 (sum(rating)) / count(PPR.id) * 100
  FROM
   play_preference_responses AS PPR
   LEFT JOIN characters AS C ON PPR.user_id = C.user_id
   LEFT JOIN scene_characters AS SC ON C.id = SC.character_id
  WHERE
   PPR.play_preference_id = PP.id
   AND SC.scene_id = ?
 ), 0) AS percentage
FROM
 play_preferences AS PP
ORDER BY
 PP.name;
EOQ;
        $params = [
            $sceneId
        ];

        $conn = ConnectionManager::get('default');
        /* @var Connection $conn */
        return $conn->execute($sql, $params)->fetchAll('assoc');
    }

    /**
     * @param int $userId
     * @return PlayPreferenceResponse[]
     */
    public function listByUserId($userId)
    {
        return $this->find()
            ->where([
                'PlayPreferenceResponses.user_id' => $userId
            ])
            ->contain([
                'PlayPreferences' => [
                    'fields' => [
                        'name',
                        'description'
                    ]
                ]
            ])
            ->order([
                'PlayPreferences.name'
            ])
            ->toArray();
    }

    public function updateUserResponse($userId, $data)
    {
        $this->deleteAll(
            [
                'PlayPreferenceResponse.user_id' => $userId
            ]
        );
        foreach ($data['user_preference'] as $playPreferenceId => $value) {
            $item = $this->newEntity();
            /* @var PlayPreferenceResponse $item */
            $item->play_preference_id = $playPreferenceId;
            $item->user_id = $userId;
            $item->rating = $value;
            $item->created_on = date('Y-m-d H:i:s');
            $item->note = '';
            $this->save($item);
        }
        return true;
    }

    public function getVenueReport($venue, $playPreferenceId)
    {
        if (strtolower($venue) == 'all') {
            $venue = '';
        }
        if (strtolower($playPreferenceId) == 'all') {
            $playPreferenceId = 0;
        }
        $sql = <<<SQL
SELECT
  C.character_type,
  PP.name,
  PP.slug,
  (
    SELECT count(*)
    FROM
      play_preference_responses AS PPR1
      INNER JOIN characters AS C2 ON PPR1.user_id = C2.user_id
    WHERE
      C2.is_sanctioned = 'Y'
      AND C2.character_type = C.character_type
      AND C2.is_npc = 'N'
      AND C2.is_deleted = 'N'
      AND PPR1.rating = 1
      AND PPR1.play_preference_id = PP.id
      AND (
        C.character_type = :venue
        OR :venue = ''
      )
  ) AS `hits`,
  (
    SELECT count(*)
    FROM
      play_preference_responses AS PPR2
      INNER JOIN characters AS C2 ON PPR2.user_id = C2.user_id
    WHERE
      C2.is_sanctioned = 'Y'
      AND C2.character_type = C.character_type
      AND C2.is_npc = 'N'
      AND C2.is_deleted = 'N'
      AND PPR2.play_preference_id = PP.id
      AND (
        C.character_type = :venue
        OR :venue = ''
      )
  ) AS `total`
FROM
  play_preferences AS PP
  CROSS JOIN (
               SELECT DISTINCT character_type
               FROM characters
               WHERE is_sanctioned = 'Y'
               AND is_npc = 'N'
               AND is_deleted = 'N'
             ) AS C
WHERE
  (
        C.character_type = :venue
        OR :venue = ''
  )
  AND
  (
      PP.id = :playPrefId
      OR
      :playPrefId = 0
  )
GROUP BY
  C.character_type,
  PP.name,
  PP.slug
ORDER BY
  C.character_type,
  PP.name;
SQL;
        $params = [
            'venue' => $venue,
            'playPrefId' => $playPreferenceId
        ];
        $conn = ConnectionManager::get('default');
        /* @var Connection $conn */
        return $conn->execute($sql, $params)->fetchAll('assoc');
    }

}
