<?php
/**
 * Created by PhpStorm.
 * User: JeffVandenberg
 * Date: 5/2/2017
 * Time: 9:05 AM
 */

namespace classes\territory\data;


use classes\core\data\DataModel;

class Territory extends DataModel
{
    public $Id;
    public $TerritoryName;
    public $TerritoryTypeId;
    public $CharacterId;
    public $OptimalPopulation;
    public $NpcPopulation;
    public $IsActive;
    public $Quality;
    public $Security;
    public $IsOpen;
    public $TerritoryNotes;
    public $Attribute;
    public $Skill;
    public $CreatedOn;
    public $CreatedBy;
    public $UpdatedOn;
    public $UpdatedBy;
    public $MaxQuality;
    public $CurrentQuality;

    public function __construct()
    {
        parent::__construct();
        $this->NameColumn = 'territory_name';
    }

}
