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
use classes\character\data\CharacterPower;

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
 * @property User User
 * @Property CharacterStatus CharacterStatus
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
    public $BonusReceived;
    public $Slug;
    public $Gameline;
    public $CharacterStatusId;

    private $powers;
    private $lastStNote;

    public $HasMany = [
        'Attributes' => CharacterPower::class,
        'Skills' => CharacterPower::class,
        'Specialities' => CharacterPower::class,
        'Merits' => CharacterPower::class,
        'CharacterPower',
        'CharacterNote'
    ];

    public $BelongsTo = [
        'User' => User::class,
        'UpdatedBy' => User::class,
        'CharacterStatus'
    ];

    public function __construct()
    {
        parent::__construct();
        $this->NameColumn = 'character_name';
        $this->SortColumn = 'character_name';

        // defaults values
        $this->BonusReceived = 0;
    }

    public function __get($property)
    {
        switch (strtolower($property)) {
            case 'attributes':
                return $this->getPowerList('attribute');
            case 'skills':
                return $this->getPowerList('skill');
            case 'specialties':
                return $this->getPowerList('specialty');
            case 'merits':
                return $this->getPowerList('merit');
            case 'flaws':
                return $this->getPowerList('flaw');
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
        $this->powers[$typeName][$powerName] = $power;
        return $power;
    }

    /**
     * @param $powerType
     * @return CharacterPower[]
     */
    public function getPowerList($powerType)
    {
        if (!isset($this->powers[$powerType])) {
            $this->powers[$powerType] = RepositoryManager::GetRepository(CharacterPower::class)->ListByCharacterIdAndPowerType($this->CharacterId, ucfirst($powerType));
        }
        return $this->powers[$powerType];
    }

    public function loadPowers()
    {
        $powers = RepositoryManager::GetRepository(CharacterPower::class)->ListByCharacterId($this->Id);
        /* @var CharacterPower[] $powers */
        foreach ($powers as $power) {
            $powertype = lcfirst($power->PowerType);
            $power->Extra = json_decode($power->Extra, true);
            if (in_array($powertype, ['attribute', 'skill', 'renown'])) {
                $this->powers[$powertype][$power->PowerName] = $power;
            } else {
                $this->powers[lcfirst($power->PowerType)][] = $power;
            }
        }
    }

    public function addPower($powerType, $power)
    {
        $this->powers[$powerType][] = $power;
    }

    /**
     * @return CharacterNote
     */
    public function getLastStNote()
    {
        return $this->lastStNote;
    }

    /**
     * @param CharacterNote $stNote
     */
    public function setLastStNote(CharacterNote $stNote)
    {
        $this->lastStNote = $stNote;
    }

    public function inSanctionedStatus()
    {
        return in_array($this->CharacterStatusId, CharacterStatus::Sanctioned);
    }
}
