<?php
/**
 * Created by PhpStorm.
 * User: JeffVandenberg
 * Date: 3/16/2017
 * Time: 6:02 PM
 */

namespace classes\scene\data;


use classes\core\data\DataModel;

class SceneCharacter extends DataModel
{
    public $Id;
    public $SceneId;
    public $CharacterId;
    public $Note;
    public $AddedOn;

    public $BelongsTo = [
        'Scene',
        'Character' => 'classes\character\data\Character'
    ];
}
