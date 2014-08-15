<?php
App::uses('AppModel', 'Model');
/**
 * User Model
 *
 */
class User extends AppModel {

/**
 * Display field
 *
 * @var string
 */
	public $displayField = 'username';
    public $primaryKey = 'user_id';
    public $useTable = 'phpbb_users';

    public function CheckUserPermission($userId, $permissionId)
    {
        return false;
    }
}
