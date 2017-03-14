<?php
namespace app\Model;

use App\Model\AppModel;
/**
 * Permission Model
 *
 * @property User $User
 */
class SitePermission extends AppModel {

    public static $IsAdmin = 1;
    public static $IsHead = 2;
    public static $IsST = 3;
    public static $IsAsst = 4;
    public static $WikiManager = 5;
    public static $ManageRequests = 6;
    public static $ManageCharacters = 7;
    public static $ManageScenes = 8;
    public static $ManageDatabase = 9;

	public $useTable = 'permissions';
/**
 * Display field
 *
 * @var string
 */
	public $displayField = 'permission_name';
/**
 * Validation rules
 *
 * @var array
 */
	public $validate = array(
		'permission_name' => array(
			'notempty' => array(
				'rule' => array('notempty'),
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
			'deleteQuery' => '',
			'insertQuery' => ''
		)
	);

}
