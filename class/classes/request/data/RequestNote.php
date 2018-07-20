<?php
/**
 * Created by JetBrains PhpStorm.
 * User: JeffVandenberg
 * Date: 8/11/13
 * Time: 7:06 PM
 * To change this template use File | Settings | File Templates.
 */

namespace classes\request\data;


use classes\core\data\DataModel;

class RequestNote extends DataModel
{
    public $CreatedOn;
    public $CreatedById;
    public $Note;
    public $RequestId;

    public $BelongsTo = [
        'Request',
        'CreatedBy' => 'classes\core\data\User'
    ];

    public function __construct()
    {
        parent::__construct();
    }
}
