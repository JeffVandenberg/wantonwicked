<?php
/**
 * Created by JetBrains PhpStorm.
 * User: JeffVandenberg
 * Date: 8/11/13
 * Time: 2:00 PM
 * To change this template use File | Settings | File Templates.
 */

namespace classes\request\data;


class RequestStatus {
    const NewRequest = 1;
    const Submitted = 6;
    const InProgress = 2;
    const Returned = 3;
    const Approved = 4;
    const Denied = 5;
    const Closed = 7;

    public static $Player = array(
        RequestStatus::NewRequest,
        RequestStatus::Submitted,
        RequestStatus::InProgress,
        RequestStatus::Returned,
        RequestStatus::Approved,
        RequestStatus::Denied
    );

    public static $PlayerEdit = array(
        RequestStatus::NewRequest,
        RequestStatus::Submitted,
        RequestStatus::Returned
    );

    public static $Storyteller = array(
        RequestStatus::Submitted,
        RequestStatus::InProgress
    );

    public static $PlayerSubmit = array(
        RequestStatus::NewRequest,
        RequestStatus::Returned
    );

    public static $Final = array(
        RequestStatus::Approved,
        RequestStatus::Denied
    );

    public static $Terminal = array(
        RequestStatus::Approved,
        RequestStatus::Denied,
        RequestStatus::Closed
    );
}