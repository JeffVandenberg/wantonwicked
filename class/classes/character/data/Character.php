<?php
/**
 * Created by JetBrains PhpStorm.
 * User: jvandenberg
 * Date: 8/29/13
 * Time: 10:03 AM
 * To change this template use File | Settings | File Templates.
 */

namespace classes\character\data;


use classes\core\data\DataModel;
use classes\core\data\User;
use classes\core\repository\RepositoryManager;

/**
 * @property CharacterPower[] Attributes
 * @property CharacterPower[] Specialties
 * @property CharacterPower[] Skills
 * @property CharacterPower[] Merits
 * @property CharacterPower[] Flaws
 * @property CharacterPower[] InClanDisciplines
 * @property CharacterPower[] OutOfClanDisciplines
 * @property CharacterPower[] Devotions
 * @property CharacterPower[] CharacterPower
 * @property User UpdatedBy
 */
class Character extends DataModel
{
    public $Id;
    public $CharacterName;
    public $UserId;
    public $ShowSheet;
    public $ViewPassword;
    public $CharacterType;
    public $City;
    public $Age;
    public $Sex;
    public $ApparentAge;
    public $Concept;
    public $Description;
    public $Url;
    public $SafePlace;
    public $Friends;
    public $Icon;
    public $IsNpc;
    public $Virtue;
    public $Vice;
    public $Splat1;
    public $Splat2;
    public $Subsplat;
    public $Size;
    public $Speed;
    public $InitiativeMod;
    public $Defense;
    public $Armor;
    public $Health;
    public $WoundsAgg;
    public $WoundsLethal;
    public $WoundsBashing;
    public $PowerStat;
    public $PowerPoints;
    public $Morality;
    public $EquipmentPublic;
    public $EquipmentHidden;
    public $PublicEffects;
    public $History;
    public $CharacterNotes;
    public $Goals;
    public $IsSanctioned;
    public $CurrentExperience;
    public $TotalExperience;
    public $UpdatedById;
    public $UpdatedOn;
    public $GmNotes;
    public $SheetUpdate;
    public $HideIcon;
    public $Helper;
    public $Status;
    public $BonusAttribute;
    public $MiscPowers;
    public $TemporaryHealthLevels;
    public $IsSuspended;
    public $WillpowerTemp;
    public $WillpowerPerm;
    public $AveragePowerPoints;
    public $PowerPointsModifier;
    public $AsstSanctioned;
    public $BonusReceived;
    public $Slug;
    public $Gameline;

    private $powers;

    public $HasMany = array(
        'Attributes' => 'classes\character\data\CharacterPower',
        'Skills' => 'classes\character\data\CharacterPower',
        'Specialities' => 'classes\character\data\CharacterPower',
        'Merits' => 'classes\character\data\CharacterPower',
        'CharacterPower'
    );

    public $BelongsTo = [
        'UpdatedBy' => 'classes\core\data\User'
    ];

    function __construct()
    {
        parent::__construct();
        $this->NameColumn = 'character_name';
        $this->SortColumn = 'character_name';
    }

    public function __get($property)
    {
        switch (strtolower($property)) {
            case 'attributes':
                return $this->getPowerlist('attribute');
            case 'skills':
                return $this->getPowerlist('skill');
            case 'specialties':
                return $this->getPowerlist('specialty');
            case 'merits':
                return $this->getPowerList('merit');
            case 'flaws':
                return $this->getPowerlist('flaw');
            default:
                return parent::__get($property);
                break;
        }
    }

    /**
     * @param $attributeName
     * @return CharacterPower
     */
    public function getAttribute($attributeName)
    {
        foreach ($this->getPowerList('attribute') as $attribute) {
            if ($attribute->PowerName === $attributeName) {
                return $attribute;
            }
        }
        $item = new CharacterPower();
        $item->PowerType = 'Attribute';
        $item->PowerName = $attributeName;
        return $item;
    }

    /**
     * @param $skillName
     * @return CharacterPower
     */
    public function getSkill($skillName)
    {
        foreach ($this->getPowerList('skill') as $skill) {
            if ($skill->PowerName === $skillName) {
                return $skill;
            }
        }
        $item = new CharacterPower();
        $item->PowerType = 'Skill';
        $item->PowerName = $skillName;
        return $item;
    }

    /**
     * @param $typeName
     * @param $powerName
     * @return CharacterPower
     */
    public function getPowerByTypeAndName($typeName, $powerName)
    {
        if (isset($this->powers[$typeName][$powerName])) {
            return $this->powers[$typeName][$powerName];
        }
        $power = new CharacterPower();
        $power->PowerType = $typeName;
        $power->PowerName = $powerName;
        return $power;
    }

    public function initializeNew($characterType = 'mortal')
    {
        $this->CharacterType = $characterType;
        $this->Size = 5;
        $this->Morality = 7;

        // initialize specialities
        $this->addList(3, 'specialty');
        $this->addList(5, 'merit');
        $this->addList(2, 'miscPower');
        $this->addList(4, 'equipment');
        $this->addList(3, 'aspiration');
        $this->addList(5, 'morality');

        $this->addCharacterTypePowers();
    }

    public function addList($numOfItems, $powerType)
    {
        foreach (range(1, $numOfItems) as $i) {
            $power = new CharacterPower();
            $power->PowerType = $powerType;
            $this->powers[$powerType][] = $power;
        }
    }

    private function addCharacterTypePowers()
    {
        // do something here later
    }

    /**
     * @param $powerType
     * @return CharacterPower[]
     */
    public function getPowerList($powerType)
    {
        if(!isset($this->powers[$powerType])) {
            $this->powers[$powerType] = RepositoryManager::GetRepository('classes\character\data\CharacterPower')->ListByCharacterIdAndPowerType($this->CharacterId, ucfirst($powerType));
        }
        return $this->powers[$powerType];
    }

    public function loadPowers()
    {
        $powers = RepositoryManager::GetRepository('classes\character\data\CharacterPower')->ListByCharacterId($this->Id);
        /* @var CharacterPower[] $powers */
        foreach($powers as $power) {
            $powertype = lcfirst($power->PowerType);
            $power->Extra = json_decode($power->Extra, true);
            if(in_array($powertype, ['attribute', 'skill'])) {
                $this->powers[$powertype][$power->PowerName] = $power;
            } else {
                $this->powers[lcfirst($power->PowerType)][] = $power;
            }
        }

        $powerTypeList = [
            'specialty' => 1,
            'merit' => 1,
            'miscPower' => 1,
            'equipment' => 1,
            'aspiration' => 1,
            'break_point' => 5
        ];
        foreach($powerTypeList as $type => $min) {
            if(count($this->getPowerList($type)) < $min) {
                $this->addList($min - count($this->getPowerList($type)), $type);
            }
        }

        $this->addCharacterTypePowers();
    }
}
