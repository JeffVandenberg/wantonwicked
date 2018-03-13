<?php
/**
 * Created by JetBrains PhpStorm.
 * User: JeffVandenberg
 * Date: 8/11/13
 * Time: 2:00 PM
 * To change this template use File | Settings | File Templates.
 */

namespace classes\request\data;


use classes\core\data\DataModel;

class RequestStatus extends DataModel
{
    const NEW_REQUEST = 1;
    const SUBMITTED = 6;
    const IN_PROGRESS = 2;
    const RETURNED = 3;
    const APPROVED = 4;
    const DENIED = 5;
    const CLOSED = 7;

    public static $Player = array(
        RequestStatus::NEW_REQUEST,
        RequestStatus::SUBMITTED,
        RequestStatus::IN_PROGRESS,
        RequestStatus::RETURNED,
        RequestStatus::APPROVED,
        RequestStatus::DENIED
    );

    public static $PlayerEdit = array(
        RequestStatus::NEW_REQUEST,
        RequestStatus::SUBMITTED,
        RequestStatus::RETURNED
    );

    public static $Storyteller = array(
        RequestStatus::SUBMITTED,
        RequestStatus::IN_PROGRESS
    );

    public static $PlayerSubmit = array(
        RequestStatus::NEW_REQUEST,
        RequestStatus::RETURNED
    );

    public static $Final = array(
        RequestStatus::APPROVED,
        RequestStatus::DENIED
    );

    public static $Terminal = array(
        RequestStatus::APPROVED,
        RequestStatus::DENIED,
        RequestStatus::CLOSED
    );

    public $Id;
    public $Name;

    public function __construct() {
        parent::__construct();
    }
}
