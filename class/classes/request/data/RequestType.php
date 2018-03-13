<?php
/**
 * Created by JetBrains PhpStorm.
 * User: JeffVandenberg
 * Date: 8/11/13
 * Time: 11:43 AM
 * To change this template use File | Settings | File Templates.
 */
namespace classes\request\data;

use classes\core\data\DataModel;

class RequestType extends DataModel
{
    const Sanction      = 1;
    const XpRequest     = 2;
    const NonXpRequest  = 3;
    const BlueBook      = 4;
    const CreativeThaum = 5;
    const SceneRequest  = 6;
    const XpRecommend   = 7;

    public $Id;
    public $Name;

    public function __construct() {
        parent::__construct();
    }
}
