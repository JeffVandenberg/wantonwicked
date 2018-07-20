<?php
/**
 * Created by PhpStorm.
 * User: jvandenberg
 * Date: 10/22/15
 * Time: 5:27 PM
 */

namespace classes\core\data;


class Role extends DataModel
{
    public $Id;
    public $Name;

    /**
     * Permission constructor.
     */
    public function __construct()
    {
        parent::__construct();
    }
}
