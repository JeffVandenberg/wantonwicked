<?php
/**
 * Created by JetBrains PhpStorm.
 * User: jvandenberg
 * Date: 9/13/13
 * Time: 4:08 PM
 * To change this template use File | Settings | File Templates.
 */

namespace classes\core\data;


use classes\core\data\DataModel;

class User extends DataModel 
{
    public $Id;
    public $Username;
    public $UserEmail;
    public $RoleId;

    public $Mapping = array(
        'Id' => 'user_id'
    );

    function __construct()
    {
        parent::__construct('', '');
        $this->TableName = 'phpbb_users';
        $this->NameColumn = 'username';
        $this->IdColumn = 'user_id';
    }
}