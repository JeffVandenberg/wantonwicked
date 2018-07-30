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
    public const SANCTION      = 1;
    public const XP_REQUEST     = 2;
    public const NON_XP_REQUEST  = 3;
    public const BLUEBOOK      = 4;
    public const CREATIVE_THAUM = 5;
    public const SCENE  = 6;
    public const XP_REC   = 7;

    public $Id;
    public $Name;

    public function __construct() {
        parent::__construct();
    }
}
