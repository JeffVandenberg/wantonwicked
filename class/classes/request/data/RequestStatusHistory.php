<?php
/**
 * Created by JetBrains PhpStorm.
 * User: JeffVandenberg
 * Date: 10/25/13
 * Time: 8:01 PM
 * To change this template use File | Settings | File Templates.
 */

namespace classes\request\data;


use classes\core\data\DataModel;
use classes\core\data\User;

/**
 * @property RequestStatus RequestStatus
 * @property User CreatedBy
 */
class RequestStatusHistory extends DataModel
{
    public $Id;
    public $RequestId;
    public $RequestStatusId;
    public $CreatedById;
    public $CreatedOn;

    public $BelongsTo = array(
        'Request',
        'RequestStatus',
        'CreatedBy' => 'classes\core\data\User'
    );

    public function __construct()
    {
        parent::__construct();
        $this->NameProperty = 'CreatedOn';
        $this->NameColumn = 'created_on';
        $this->SortColumn = 'created_on';
    }
}
