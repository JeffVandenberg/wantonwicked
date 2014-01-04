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

    public $BelongsTo = array(
        'Character'
    );

    function __construct()
    {
        parent::__construct();
        $this->NameProperty = 'PowerName';
        $this->NameColumn = 'power_name';
        $this->SortColumn = array('power_name', 'power_note', 'power_level');
    }
}