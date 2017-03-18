<?php
/**
 * Created by PhpStorm.
 * User: JeffVandenberg
 * Date: 3/16/2017
 * Time: 6:00 PM
 */

namespace classes\scene\data;


use classes\core\data\DataModel;

class SceneStatus extends DataModel
{
    const Open = 1;
    const Completed = 2;
    const Cancelled = 3;

    public $Id;
    public $Name;
}
