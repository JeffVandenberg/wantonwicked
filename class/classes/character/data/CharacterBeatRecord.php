<?php
/**
 * Created by PhpStorm.
 * User: JeffVandenberg
 * Date: 2/25/2017
 * Time: 9:10 AM
 */

namespace classes\character\data;


use classes\core\data\DataModel;

class CharacterBeatRecord extends DataModel
{
    public $Id;
    public $CharacterId;
    public $RecordMonth;
    public $ExperienceEarned;

    public $BelongsTo = [
        'Character'
    ];
}
