<?php
/**
 * Created by JetBrains PhpStorm.
 * User: JeffVandenberg
 * Date: 8/11/13
 * Time: 1:57 PM
 * To change this template use File | Settings | File Templates.
 */

namespace classes\request\data;

use classes\character\data\Character;
use classes\core\data\DataModel;
use classes\core\data\Group;
use classes\core\data\User;

/**
 * @property Character Character
 * @property Group Group
 * @property RequestType RequestType
 * @property RequestStatus RequestStatus
 * @property RequestCharacter RequestCharacter
 * @property RequestStatusHistory[] RequestStatusHistory
 * @property User UpdatedBy
 * @property User CreatedBy
 */
class Request extends DataModel
{
    public $Id;
    public $CharacterId;
    public $Title;
    public $UpdatedOn;
    public $UpdatedById;
    public $CreatedOn;
    public $CreatedById;
    public $Body;
    public $RequestStatusId;
    public $RequestTypeId;
    public $GroupId;

    function __construct()
    {
        parent::__construct();
        $this->NameProperty = 'Title';
        $this->NameColumn = 'title';
        $this->SortColumn = 'title';
    }

    public $BelongsTo = array(
        'Character' => 'classes\character\data\Character',
        'Group' => 'classes\core\data\Group',
        'RequestType',
        'RequestStatus',
        'UpdatedBy' => 'classes\core\data\User',
        'CreatedBy' => 'classes\core\data\User'
    );

    public $HasMany = array(
        'RequestStatusHistory',
        'RequestCharacter'
    );
}