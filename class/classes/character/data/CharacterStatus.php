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

    public const NEW_CHARACTER = 1;
    public const ACTIVE = 2;
    public const UNSANCTIONED = 3;
    public const INACTIVE = 4;
    public const DELETED = 5;
    public const IDLE = 6;

    public const NonDeleted = [
        self::NEW_CHARACTER,
        self::ACTIVE,
        self::UNSANCTIONED,
        self::INACTIVE,
        self::IDLE,
    ];

    public const Sanctioned = [
        self::ACTIVE,
        self::INACTIVE,
        self::IDLE
    ];

    public $HasMany = [
        'Character'
    ];
}
