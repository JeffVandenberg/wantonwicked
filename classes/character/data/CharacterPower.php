<?php
/**
 * Created by JetBrains PhpStorm.
 * User: jvandenberg
 * Date: 8/29/13
 * Time: 1:51 PM
 * To change this template use File | Settings | File Templates.
 */

namespace classes\character\data;


use classes\core\data\DataModel;

/**
 * @property Character Character
 */
class CharacterPower extends DataModel
{
    public $PowerId;
    public $PowerType;
    public $PowerName;
    public $PowerNote;
    public $PowerLevel;
    public $CharacterId;

    public $Mapping = array(
        'PowerId' => 'PowerID',
        'PowerType' => 'PowerType',
        'PowerName' => 'PowerName',
        'PowerNote' => 'PowerNote',
        'PowerLevel' => 'PowerLevel',
        'CharacterId' => 'CharacterID'
    );

    public $BelongsTo = array(
        'Character'
    );

    function __construct()
    {
        parent::__construct('wod_');
        $this->TableName = 'wod_characters_powers';
        $this->IdProperty = 'PowerId';
        $this->IdColumn = 'PowerID';
        $this->NameProperty = 'PowerName';
        $this->NameColumn = 'PowerName';
        $this->SortColumn = array('PowerName', 'PowerNote', 'PowerLevel');
    }
}