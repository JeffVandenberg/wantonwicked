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
    public $EquipmentPrivate;
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
    public $LoginNote;
    public $HideIcon;
    public $Helper;
    public $Status;
    public $BonusAttribute;
    public $MiscPowers;
    public $TemporaryHealthLevels;
    public $NextPowerStatIncrease;
    public $IsSuspended;
    public $WillpowerTemp;
    public $WillpowerPerm;
    public $AveragePowerPoints;

    public $HasMany = array(
        'Attributes' => 'classes\character\data\CharacterPower',
        'Skills' => 'classes\character\data\CharacterPower',
        'Specialities' => 'classes\character\data\CharacterPower',
        'Merits' => 'classes\character\data\CharacterPower'
    );

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
                if (!isset($this->Attributes)) {
                    $this->Attributes = RepositoryManager::GetRepository('classes\character\data\CharacterPower')->ListByCharacterIdAndPowerType($this->CharacterId, 'Attribute');
                }
                return $this->Attributes;
                break;
            case 'skills':
                if (!isset($this->Skills)) {
                    $this->Skills = RepositoryManager::GetRepository('classes\character\data\CharacterPower')->ListByCharacterIdAndPowerType($this->CharacterId, 'Skill');
                }
                return $this->Skills;
                break;
            case 'specialties':
                if (!isset($this->Specialties)) {
                    $this->Specialties = RepositoryManager::GetRepository('classes\character\data\CharacterPower')->ListByCharacterIdAndPowerType($this->CharacterId, 'Specialty');
                }
                return $this->Specialties;
                break;
            case 'merits':
                if (!isset($this->Merits)) {
                    $this->Merits = RepositoryManager::GetRepository('classes\character\data\CharacterPower')->ListByCharacterIdAndPowerType($this->CharacterId, 'Merit');
                }
                return $this->Merits;
                break;
            case 'flaws':
                if (!isset($this->Flaws)) {
                    $this->Flaws = RepositoryManager::GetRepository('classes\character\data\CharacterPower')->ListByCharacterIdAndPowerType($this->CharacterId, 'Flaw');
                }
                return $this->Flaws;
                break;
            case 'inclandisciplines':
                if (!isset($this->InClanDisciplines)) {
                    $this->InClanDisciplines = RepositoryManager::GetRepository('classes\character\data\CharacterPower')->ListByCharacterIdAndPowerType($this->CharacterId, 'ICDisc');
                }
                return $this->InClanDisciplines;
                break;
            case 'outofclandisciplines':
                if (!isset($this->OutOfClanDisciplines)) {
                    $this->OutOfClanDisciplines = RepositoryManager::GetRepository('classes\character\data\CharacterPower')->ListByCharacterIdAndPowerType($this->CharacterId, 'OOCDisc');
                }
                return $this->OutOfClanDisciplines;
                break;
            case 'devotions':
                if (!isset($this->Devotions)) {
                    $this->Devotions = RepositoryManager::GetRepository('classes\character\data\CharacterPower')->ListByCharacterIdAndPowerType($this->CharacterId, 'Devotion');
                }
                return $this->Devotions;
                break;
            default:
                return parent::__get($property);
                break;
        }
    }

    /**
     * @param $attributeName
     * @return CharacterPower
     */
    public function getAttribute($attributeName) {
        foreach($this->Attributes as $attribute) {
            if($attribute->PowerName === $attributeName) {
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
        foreach($this->Skills as $skill) {
            if($skill->PowerName === $skillName) {
                return $skill;
            }
        }
        $item = new CharacterPower();
        $item->PowerType = 'Skill';
        $item->PowerName = $skillName;
        return $item;
    }
}