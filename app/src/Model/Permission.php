<?php
namespace app\Model;

App::uses('AppModel', 'Model');

/**
 * Permission Model
 *
 * @property Role $Role
 */
class Permission extends AppModel
{
    public $displayField = 'permission_name';

    /**
     * Validation rules
     *
     * @var array
     */
    public $validate = array(
        'permission_name' => array(
            'notEmpty' => array(
                'rule' => array('notEmpty'),
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
     * hasAndBelongsToMany associations
     *
     * @var array
     */
    public $hasAndBelongsToMany = array(
        'Role' => array(
            'className' => 'Role',
            'joinTable' => 'permissions_roles',
            'foreignKey' => 'permission_id',
            'associationForeignKey' => 'role_id',
            'unique' => 'keepExisting',
            'conditions' => '',
            'fields' => '',
            'order' => '',
            'limit' => '',
            'offset' => '',
            'finderQuery' => '',
        ),
        'User' => array(
            'className' => 'User',
            'joinTable' => 'permissions_users',
            'foreignKey' => 'permission_id',
            'associationForeignKey' => 'user_id',
            'unique' => 'keepExisting',
            'conditions' => '',
            'fields' => '',
            'order' => '',
            'limit' => '',
            'offset' => '',
            'finderQuery' => '',
        )
    );

}
