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
    public $CharacterId;
    public $CharacterName;
    public $PrimaryLoginId;
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
    public $IsNpc;
    public $Virtue;
    public $Vice;
    public $Splat1;
    public $Splat2;
    public $SubSplat;
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
    public $HeadSanctioned;
    public $IsSanctioned;
    public $CurrentExperience;
    public $TotalExperience;
    public $FirstLogin;
    public $LastLogin;
    public $UpdatedById;
    public $UpdatedOn;
    public $GmNotes;
    public $SheetUpdate;
    public $LoginNote;
    public $HideIcon;
    public $Helper;
    public $Status;
    public $LoggedToday;
    public $BonusAttribute;
    public $MiscPowers;
    public $TemporaryHealthLevels;
    public $NextPowerStatIncrease;
    public $IsSuspended;
    public $WillpowerTemp;
    public $WillpowerPerm;
    public $AveragePowerPoints;

    public $Mapping = array(
        'CharacterId' => 'Character_ID',
        'CharacterName' => 'Character_Name',
        'PrimaryLoginId' => 'Primary_Login_ID',
        'ShowSheet' => 'Show_Sheet',
        'ViewPassword' => 'View_Password',
        'CharacterType' => 'Character_Type',
        'City' => 'City',
        'Age' => 'Age',
        'Sex' => 'Sex',
        'ApparentAge' => 'ApparentAge',
        'Concept' => 'Concept',
        'Description' => 'Description',
        'Url' => 'URL',
        'SafePlace' => 'Safe_Place',
        'Friends' => 'Friends',
        'IsNpc' => 'Is_NPC',
        'Virtue' => 'Virtue',
        'Vice' => 'Vice',
        'Splat1' => 'Splat1',
        'Splat2' => 'Splat2',
        'SubSplat' => 'SubSplat',
        'Size' => 'Size',
        'Speed' => 'Speed',
        'InitiativeMod' => 'Initiative_Mod',
        'Defense' => 'Defense',
        'Armor' => 'Armor',
        'Health' => 'Health',
        'WoundsAgg' => 'Wounds_Agg',
        'WoundsLethal' => 'Wounds_Lethal',
        'WoundsBashing' => 'Wounds_Bashing',
        'PowerStat' => 'Power_Stat',
        'PowerPoints' => 'Power_Points',
        'Morality' => 'Morality',
        'EquipmentPublic' => 'Equipment_Public',
        'EquipmentPrivate' => 'Equipment_Private',
        'PublicEffects' => 'Public_Effects',
        'History' => 'History',
        'CharacterNotes' => 'Character_Notes',
        'Goals' => 'Goals',
        'HeadSanctioned' => 'Head_Sanctioned',
        'IsSanctioned' => 'Is_Sanctioned',
        'CurrentExperience' => 'Current_Experience',
        'TotalExperience' => 'Total_Experience',
        'FirstLogin' => 'First_Login',
        'LastLogin' => 'Last_Login',
        'UpdatedById' => 'Last_ST_Updated',
        'UpdatedOn' => 'When_Last_ST_Updated',
        'GmNotes' => 'GM_Notes',
        'SheetUpdate' => 'Sheet_Update',
        'LoginNote' => 'Login_Note',
        'HideIcon' => 'Hide_Icon',
        'Helper' => 'Helper',
        'Status' => 'Status',
        'LoggedToday' => 'Logged_Today',
        'BonusAttribute' => 'BonusAttribute',
        'MiscPowers' => 'Misc_Powers',
        'TemporaryHealthLevels' => 'Temporary_Health_Levels',
        'NextPowerStatIncrease' => 'Next_Power_Stat_Increase',
        'WillpowerTemp' => 'Willpower_Temp',
        'WillpowerPerm' => 'Willpower_Perm',
        'AveragePowerPoints' => 'Average_Power_Points'
    );

    public $HasMany = array(
        'Attributes' => 'classes\character\data\CharacterPower',
        'Skills' => 'classes\character\data\CharacterPower',
        'Specialities' => 'classes\character\data\CharacterPower',
        'Merits' => 'classes\character\data\CharacterPower'
    );

    function __construct()
    {
        parent::__construct('wod_');
        $this->IdProperty = 'CharacterId';
        $this->NameProperty = 'CharacterName';
        $this->InitializeDerivedValues();
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