<?php
/**
 * Created by PhpStorm.
 * User: JeffVandenberg
 * Date: 6/21/2015
 * Time: 12:53 PM
 */

namespace classes\request\data;


use classes\character\data\Character;
use classes\core\data\DataModel;

/**
 * @property Request Request
 * @property Character Character
 */
class RequestCharacter extends DataModel
{
    public $Id;
    public $RequestId;
    public $CharacterId;
    public $IsPrimary;
    public $IsApproved;
    public $Note;

    public $BelongsTo = array(
        'Request',
        'Character' => 'classes\character\data\Character'
    );

    function __construct($tablePrefix = '', $database = null)
    {
        parent::__construct($tablePrefix, $database);
    }

}