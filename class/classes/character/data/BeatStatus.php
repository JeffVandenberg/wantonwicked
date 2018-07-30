<?php
/**
 * Created by PhpStorm.
 * User: JeffVandenberg
 * Date: 2/25/2017
 * Time: 9:17 AM
 */

namespace classes\character\data;


use classes\core\data\DataModel;

class BeatStatus extends DataModel
{
    public const NEW_BEAT = 1;
    public const STAFF_AWARDED = 2;
    public const APPLIED = 3;
    public const INVALIDATED = 4;
    public const EXPIRED = 5;

    public $Id;
    public $Name;

    public $HasMany = [
        'CharacterBeat'
    ];
}
