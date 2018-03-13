<?php
/**
 * Created by JetBrains PhpStorm.
 * User: JeffVandenberg
 * Date: 7/14/13
 * Time: 12:24 AM
 * To change this template use File | Settings | File Templates.
 */

namespace classes\log\data;


use classes\core\data\DataModel;

class ActionType extends DataModel
{
    const VIEW_CHARACTER = 1;
    const LOGIN = 2;
    const UPDATE_CHARACTER = 3;
    const VIEW_REQUEST = 4;
    const SUPPORTER_XP = 5;
    const SANCTIONED = 6;
    const INVALID_ACCESS = 7;
    const BLUE_BOOK_LIST = 8;
    const BLUE_BOOK_VIEW = 9;
    const DESANCTIONED = 10;
    const XP_MODIFICATION = 11;

    public $Id;
    public $Name;

    public function __construct()
    {
        parent::__construct();
    }
}
