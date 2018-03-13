<?php
/**
 * Created by JetBrains PhpStorm.
 * User: jvandenberg
 * Date: 9/13/13
 * Time: 3:48 PM
 * To change this template use File | Settings | File Templates.
 */

namespace classes\character\data;


use classes\core\data\DataModel;
use classes\core\data\User;
use classes\log\data\ActionType;

/**
 * @property ActionType ActionType
 * @property User CreatedBy
 */
class LogCharacter extends DataModel
{
    public $Id;
    public $CharacterId;
    public $ActionTypeId;
    public $Note;
    public $ReferenceId;
    public $CreatedById;
    public $Created;

    public $BelongsTo = array(
        'ActionType' => 'classes\log\data\ActionType',
        'CreatedBy' => 'classes\core\data\User'
    );

    public function __construct()
    {
        parent::__construct();
        $this->SortColumn = 'created';
    }
}
