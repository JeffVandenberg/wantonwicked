<?php
/**
 * Created by PhpStorm.
 * User: JeffVandenberg
 * Date: 2/24/14
 * Time: 6:50 PM
 */

class Character extends AppModel {
    public $name = 'Character';

    public $displayField = 'character_name';

    public function ListSanctionedForUser($userId) {
        return $this->find('all', array(
            'conditions' => array(
                'Character.user_id' => $userId,
                'Character.is_sanctioned' => 'Y',
                'Character.is_deleted' => 'N'
            ),
            'fields' => array(
                'Character.id',
                'Character.character_name'
            ),
            'order' => array(
                'Character.character_name'
            )
        ));
    }
    public function ListByCity($city) {
        return $this->find('all', array(
            'conditions' => array(
                'Character.city' => $city,
                'Character.is_sanctioned' => 'Y',
                'Character.is_deleted' => 'N'
            ),
            'order' => array(
                'Character.character_name'
            )
        ));
    }

    public function ListByCharacterType($type) {
        $conditions = array(
            'Character.is_sanctioned' => 'Y',
            'Character.is_deleted' => 'N',
            'Character.city' => 'Savannah'
        );

        if($type != 'All') {
            $conditions['Character.character_type'] = $type;
        }

        return $this->find('all', array(
            'conditions' => $conditions,
            'order' => array(
                'Character.character_name'
            )
        ));
    }

    public function FindCharactersForScene($sceneId) {
        $sceneId = (int) $sceneId;

        $query = <<<EOQ
SELECT
    SceneCharacter.id,
    SceneCharacter.note,
    SceneCharacter.character_id,
    SceneCharacter.scene_id,
    SceneCharacter.added_on,
    Character.character_name
FROM
    characters AS `Character`
    INNER JOIN scene_characters AS SceneCharacter ON Character.id = SceneCharacter.character_id
WHERE
    SceneCharacter.scene_id = $sceneId
ORDER BY
    Character.character_name
EOQ;

        $data = $this->query($query);

        return $data;
    }

    public function FindCharactersNotInScene($userId, $sceneId) {
        $userId = (int) $userId;
        $sceneId = (int) $sceneId;

        $sql = <<<EOQ
SELECT
    Character.id,
    Character.character_name
FROM
    characters AS `Character`
    LEFT JOIN scene_characters AS SceneCharacter ON (Character.id = SceneCharacter.character_id AND SceneCharacter.scene_id = $sceneId)
WHERE
    Character.user_id = $userId
    AND SceneCharacter.id IS NULL
    AND Character.is_sanctioned = 'Y'
    AND Character.is_deleted = 'N'
ORDER BY
    Character.character_name
EOQ;

        $characters = $this->query($sql);
        $list = array();
        foreach($characters as $character)
        {
            $list[$character['Character']['id']] = $character['Character']['character_name'];
        }

        return $list;

    }
    public $belongsTo = array(
        'Player' => array(
            'className' => 'User',
            'foreignKey' => 'user_id',
            'conditions' => '',
            'fields' => '',
            'order' => ''
        ),
        'UpdatedBy' => array(
            'className' => 'User',
            'foreignKey' => 'updated_by_id',
            'conditions' => '',
            'fields' => '',
            'order' => ''
        ),
    );

    public $hasMany = array(
        'SceneCharacter' => array(
            'className'    => 'SceneCharacter',
            'foreignKey'   => 'character_id',
            'dependent'    => false,
            'conditions'   => '',
            'fields'       => '',
            'order'        => '',
            'limit'        => '',
            'offset'       => '',
            'exclusive'    => '',
            'finderQuery'  => '',
            'counterQuery' => ''
        )
    );

} 