<?php
/**
 * Created by PhpStorm.
 * User: JeffVandenberg
 * Date: 2/25/2017
 * Time: 9:04 AM
 */

namespace classes\character\data;


use classes\core\data\DataModel;
use classes\core\data\User;

/**
 * @property BeatType BeatType
 * @property User CreatedBy
 * @property User UpdatedBy
 * @property BeatStatus BeatStatus
 */
class CharacterBeat extends DataModel
{
    public $Id;
    public $CharacterId;
    public $BeatTypeId;
    public $BeatStatusId;
    public $Note;
    public $CreatedById;
    public $Created;
    public $UpdatedById;
    public $Updated;
    public $AppliedOn;
    public $BeatsAwarded;

    public $BelongsTo = [
        'BeatType',
        'BeatStatus',
        'CreatedBy' => 'classes\core\data\User',
        'UpdatedBy' => 'classes\core\data\User'

    ];
}
