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

} 