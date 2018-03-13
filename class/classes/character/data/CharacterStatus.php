<?php
/**
 * Created by PhpStorm.
 * User: JeffVandenberg
 * Date: 5/23/2017
 * Time: 1:19 PM
 */

namespace classes\character\data;


use classes\core\data\DataModel;

class CharacterStatus extends DataModel
{
    public $Id;
    public $Name;
    public $SortOrder;

    const NewCharacter = 1;
    const Active = 2;
    const Unsanctioned = 3;
    const Inactive = 4;
    const Deleted = 5;
    const Idle = 6;

    const NonDeleted = [
        self::NewCharacter,
        self::Active,
        self::Unsanctioned,
        self::Inactive,
        self::Idle,
    ];

    const Sanctioned = [
        self::Active,
        self::Inactive,
        self::Idle
    ];

    public $HasMany = [
        'Character'
    ];
}
