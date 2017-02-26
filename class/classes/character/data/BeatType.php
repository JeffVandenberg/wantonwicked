<?php
/**
 * Created by PhpStorm.
 * User: JeffVandenberg
 * Date: 2/25/2017
 * Time: 9:26 AM
 */

namespace classes\character\data;


use classes\core\data\DataModel;

class BeatType extends DataModel
{
    public $Id;
    public $Name;
    public $NumberOfBeats;
    public $AdminOnly;
    public $CreatedById;
    public $Created;
    public $UpdatedById;
    public $Updated;
    public $MayRollover;

    public $BelongsTo = [
        'CreatedBy' => 'classes\core\data\User',
        'UpdatedBy' => 'classes\core\data\User'
    ];

    public $HasMany = [
        'CharacterBeat'
    ];
}
