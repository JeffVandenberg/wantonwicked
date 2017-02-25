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
    const NewBeat = 1;
    const StaffAwarded = 2;
    const Applied = 3;
    const Invalidated = 4;
    const Expired = 5;

    public $Id;
    public $Name;

    public $HasMany = [
        'CharacterBeat'
    ];
}
