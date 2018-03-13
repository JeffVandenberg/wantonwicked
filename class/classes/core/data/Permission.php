<?php
/**
 * Created by PhpStorm.
 * User: jvandenberg
 * Date: 8/29/2015
 * Time: 11:12 PM
 */

namespace classes\core\data;


class Permission extends DataModel
{
    public $Id;
    public $PermissionName;

    /**
     * Permission constructor.
     */
    public function __construct()
    {
        parent::__construct();
        $this->NameColumn = 'permission_name';
        $this->SortColumn = 'permission_name';
    }
}
