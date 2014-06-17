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
    const ViewCharacter = 1;
    const Login = 2;
    const UpdateCharacter = 3;
    const ViewRequest = 4;
    const SupporterXP = 5;
    const Sanctioned = 6;
    const InvalidAccess = 7;
    const BlueBookList = 8;
    const BlueBookView = 9;
    const Desanctioned = 10;
    const XPModification = 11;

    public $Id;
    public $Name;

    function __construct()
    {
        parent::__construct();
    }
}