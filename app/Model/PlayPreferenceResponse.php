<?php
App::uses('AppModel', 'Model');

/**
 * PlayPreferenceResponse Model
 *
 * @property User $User
 * @property PlayPreference $PlayPreference
 */
class PlayPreferenceResponse extends AppModel
{

    /**
     * Validation rules
     *
     * @var array
     */
    public $validate = array(
        'user_id' => array(
            'numeric' => array(
                'rule' => array('numeric'),
                //'message' => 'Your custom message here',
                //'allowEmpty' => false,
                //'required' => false,
                //'last' => false, // Stop validation after this rule
                //'on' => 'create', // Limit validation to 'create' or 'update' operations
            ),
        ),
        'play_preference_id' => array(
            'numeric' => array(
                'rule' => array('numeric'),
                //'message' => 'Your custom message here',
                //'allowEmpty' => false,
                //'required' => false,
                //'last' => false, // Stop validation after this rule
                //'on' => 'create', // Limit validation to 'create' or 'update' operations
            ),
        ),
        'rating' => array(
            'numeric' => array(
                'rule' => array('numeric'),
                //'message' => 'Your custom message here',
                //'allowEmpty' => false,
                //'required' => false,
                //'last' => false, // Stop validation after this rule
                //'on' => 'create', // Limit validation to 'create' or 'update' operations
            ),
        ),
        'note' => array(
            'notEmpty' => array(
                'rule' => array('notEmpty'),
                //'message' => 'Your custom message here',
                //'allowEmpty' => false,
                //'required' => false,
                //'last' => false, // Stop validation after this rule
                //'on' => 'create', // Limit validation to 'create' or 'update' operations
            ),
        ),
        'created_on' => array(
            'datetime' => array(
                'rule' => array('datetime'),
                //'message' => 'Your custom message here',
                //'allowEmpty' => false,
                //'required' => false,
                //'last' => false, // Stop validation after this rule
                //'on' => 'create', // Limit validation to 'create' or 'update' operations
            ),
        ),
    );

    //The Associations below have been created with all possible keys, those that are not needed can be removed

    /**
     * belongsTo associations
     *
     * @var array
     */
    public $belongsTo = array(
        'User' => array(
            'className' => 'User',
            'foreignKey' => 'user_id',
            'conditions' => '',
            'fields' => '',
            'order' => ''
        ),
        'PlayPreference' => array(
            'className' => 'PlayPreference',
            'foreignKey' => 'play_preference_id',
            'conditions' => '',
            'fields' => '',
            'order' => ''
        )
    );

    public function listByUserId($userId)
    {
        return $this->find('all', [
            'conditions' => [
                'PlayPreferenceResponse.user_id' => $userId
            ],
            'contain' => [
                'PlayPreference' => [
                    'name',
                    'description'
                ]
            ],
            'order' => [
                'PlayPreference.name'
            ]
        ]);
    }

    public function updateUserResponse($userId, $data)
    {
        $this->recursive = 0;
        $this->deleteAll([
            'PlayPreferenceResponse.user_id' => $userId
        ],
            false
        );
        foreach ($data['user_preference'] as $playPreferenceId => $value) {
            $this->create();
            $data = [
                'PlayPreferenceResponse' => [
                    'play_preference_id' => $playPreferenceId,
                    'user_id' => $userId,
                    'rating' => $value,
                    'created_on' => date('Y-m-d H:i:s')
                ]
            ];
            $this->save($data);
        }
        return true;
    }

    public function reportResponsesForPlayersInScene($id)
    {
        $sql = <<<EOQ
SELECT
 PP.id,
 PP.name,
 IFNULL((
  select
 (sum(rating)) / count(PPR.id) * 100
  FROM
   play_preference_responses AS PPR
   LEFT JOIN characters as C ON PPR.user_id = C.user_id
   LEFT JOIN scene_characters AS SC ON C.id = SC.character_id
  WHERE
   PPR.play_preference_id = PP.id
   AND SC.scene_id = $id
 ), 0) as percentage
FROM
 play_preferences AS PP
ORDER BY
 PP.name;
EOQ;
        return $this->query($sql);
    }

    public function getVenueReport($venue)
    {
        $sql = /** @lang MySQL */
            <<<EOQ
            SELECT
  C.character_type,
  PP.name,
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
        C.character_type = '$venue'
        OR '$venue' = ''
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
        C.character_type = '$venue'
        OR '$venue' = ''
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
    C.character_type = '$venue'
    OR '$venue' = ''
  )
GROUP BY
  C.character_type,
  PP.name
ORDER BY
  C.character_type,
  PP.name;
EOQ;
        return $this->query($sql);
    }
}
