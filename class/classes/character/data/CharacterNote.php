<?php
/**
 * Created by PhpStorm.
 * User: JeffVandenberg
 * Date: 2/22/2017
 * Time: 7:31 AM
 */

namespace classes\character\data;


use classes\core\data\DataModel;
use classes\core\data\User;

/**
 * @property User User
 */
class CharacterNote extends DataModel
{
    public $Id;
    public $CharacterId;
    public $UserId;
    public $Note;
    public $Created;

    public $BelongsTo = [
        'Character',
        'User' => 'classes\core\data\User',
    ];
}
