<?php
/**
 * Created by PhpStorm.
 * User: JeffVandenberg
 * Date: 3/16/2017
 * Time: 5:56 PM
 */

namespace classes\scene\data;


use classes\core\data\DataModel;

class Scene extends DataModel
{
    public $Id;
    public $Name;
    public $Summary;
    public $RunById;
    public $RunOnDate;
    public $Description;
    public $CreatedById;
    public $CreatedOn;
    public $UpdatedById;
    public $UpdatedOn;
    public $Slug;
    public $SceneStatusId;

    public $BelongsTo = [
        'RunBy' => 'classes\core\data\User',
        'UpdatedBy' => 'classes\core\data\User',
        'CreatedBy' => 'classes\core\data\User',
        'SceneStatus'
    ];

    public $HasMany = [
        'SceneCharacter'
    ];

    public function __construct()
    {
        parent::__construct();
    }


}
