<?php
/**
 * Created by PhpStorm.
 * User: jvandenberg
 * Date: 8/17/15
 * Time: 3:18 PM
 */

namespace classes\dice\data;


use classes\core\data\DataModel;

class Dice extends DataModel
{
    public function __construct()
    {
        parent::__construct();
        $this->IdColumn = 'id';
        $this->NameColumn = 'description';
    }
}
