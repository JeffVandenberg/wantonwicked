<?php
/**
 * Created by PhpStorm.
 * User: JeffVandenberg
 * Date: 1/16/2017
 * Time: 11:21 AM
 */

namespace classes\character\nwod2;


use classes\character\data\Character;
use classes\character\data\CharacterPower;
use classes\character\repository\CharacterPowerRepository;
use classes\character\repository\CharacterRepository;
use classes\core\data\DataModel;
use classes\core\repository\Database;
use classes\core\repository\RepositoryManager;
use classes\log\CharacterLog;
use classes\log\data\ActionType;

/**
 * Class SheetService
 * @package classes\character\nwod2
 */
class SheetService
{
    /**
     * @var array
     */
    private $powerList = [
        'open' => [
            'aspiration',
            'specialty',
            'merit',
            'misc_power',
            'equipment',
            'break_point',
            'conditions',
            'icdisc',
            'oocdisc',
            'devotion',
            'arcana',
            'rote',
            'obsession',
            'praxis',
            'nimbus',
            'moongift',
            'shadowgift',
            'wolfgift',
            'rite',
            'contract',
            'trigger',
        ],
        'limited' => [
            'aspiration',
            'conditions'
        ]
    ];

    /**
     * SheetService constructor.
     */
    function __construct()
    {
        $this->repository = new CharacterRepository();
        $this->powerRepository = new CharacterPowerRepository();
    }

    public function initializeSheet($characterType = 'mortal')
    {
        $character = new Character();
        $characterType = ($characterType) ? $characterType : 'mortal';
        $character->CharacterType = $characterType;
        $character->Size = 5;
        $character->Morality = 7;

        // initialize specialities
        $this->addList($character, 3, 'specialty');
        $this->addList($character, 5, 'merit');
        $this->addList($character, 2, 'miscPower');
        $this->addList($character, 4, 'equipment');
        $this->addList($character, 3, 'aspiration');

        $this->addCharacterTypePowers($character);

        return $character;
    }

    public function addMinPowersForEdit(Character $character)
    {
        $powerTypeList = [
            'specialty' => 3,
            'merit' => 5,
            'miscPower' => 2,
            'equipment' => 3,
            'aspiration' => 3,
        ];
        foreach ($powerTypeList as $type => $min) {
            if (count($character->getPowerList($type)) < $min) {
                $this->addList($character, $min - count($character->getPowerList($type)), $type);
            }
        }

        $this->addCharacterTypePowers($character);
    }

    private function addCharacterTypePowers(Character $character)
    {
        $powers = [];
        switch($character->CharacterType) {
            case 'mortal':
                $powers = [
                    'break_point' => 5
                ];
                break;
            case 'vampire':
                $powers = [
                    'icdisc' => 2,
                    'oocdisc' => 2,
                    'devotion' => 2,
                    'touchstone' => 1
                ];
                break;
            case 'mage':
                $powers = [
                    'arcana' => 5,
                    'rote' => 6,
                    'obsession' => 1,
                    'praxis' => 1,
                    'nimbus' => 1
                ];
                break;
            case 'werewolf':
                $powers = [
                    'moongift' => 1,
                    'shadowgift' => 2,
                    'wolfgift' => 1,
                    'rite' => 2,
                    'touchstone' => 2,
                ];
                break;
            case 'changeling':
                $powers = [
                    'contracts' => 5,
                    'trigger' => 3,
                    'touchstone' => 1
                ];
                break;
        }

        foreach($powers as $type => $min) {
            if (count($character->getPowerList($type)) < $min) {
                $count = $min - count($character->getPowerList($type));

                $this->addList($character, $count, $type);

                if(($character->CharacterType == 'vampire') && ($type == 'touchstone') && $count > 0) {
                    $list = $character->getPowerList('touchstone');
                    $list[0]->PowerLevel = 6;
                }

                if(($character->CharacterType == 'werewolf') && ($type == 'touchstone') && $count > 0) {
                    $list = $character->getPowerList('touchstone');
                    $list[0]->PowerName = 'Physical';
                    $list[1]->PowerName = 'Spiritual';
                }
            }
        }
    }

    public function addList(Character $character, $numOfItems, $powerType)
    {
        foreach (range(1, $numOfItems) as $i) {
            $power = new CharacterPower();
            $power->PowerType = $powerType;
            $character->addPower($powerType, $power);
        }
    }

    /**
     * Load a character by ID if $identifier is an integer or slug otherwise.
     * @param int|string $identifier
     * @param null|string $characterType
     * @return Character|DataModel
     */
    public function loadSheet($identifier, $characterType = null)
    {
        // load blank sheet
        if(!$identifier) {
            return new Character();
        }

        if (is_int($identifier) || ($identifier + 0 > 0)) {
            $character = $this->repository->getById($identifier);
        } else {
            $character = $this->repository->FindBySlug($identifier);
        }
        /* @var Character $character */

        if($characterType) {
            $character->CharacterType = $characterType;
        }

        if ($character && $character->Id) {
            $character->loadPowers();
        }
        return $character;
    }

    /**
     * @param array $stats
     * @param array $options
     * @param $user
     * @return string|bool
     */
    public function saveSheet(array $stats, array $options, array $user)
    {
        // load old character
        $oldCharacter = $this->loadSheet($stats['character_id']);
        $powers = $oldCharacter->CharacterPower;

        // save new data
        $result = $this->saveData($stats, $options, $user);

        if (is_string($result)) {
            return $result;
        }

        if ($oldCharacter->Id) {
            // log xp change
            if ($stats['xp_spent'] > 0) {
                CharacterLog::LogAction($stats['character_id'], ActionType::XPModification, 'Removed ' . $stats['xp_gained'] . 'XP: ' . $stats['xp_note'], $user['user_id']);
            }
            if ($stats['xp_gained'] > 0) {
                CharacterLog::LogAction($stats['character_id'], ActionType::XPModification, 'Added ' . $stats['xp_gained'] . 'XP: ' . $stats['xp_note'], $user['user_id']);
            }

            // log character differences
            RepositoryManager::ClearCache();
            $newCharacter = $this->repository->getById($stats['character_id']);
            /* @var Character $newCharacter */
            $this->logChanges($newCharacter, $oldCharacter, $user);
        }

        return true;
    }

    /**
     * @param array $stats
     * @param array $options
     * @param array $user
     * @return bool
     */
    public function saveData(array $stats, array $options, array $user)
    {
        // clean data
        array_walk_recursive($stats, function(&$item, $value) {
            trim($item);
        });

        // validation
        if ($this->repository->isNameInUse($stats['character_name'], $stats['character_id'], $stats['city'])) {
            return 'That character name is already in use.';
        }

        // package up data
        $character = $this->repository->getById($stats['character_id']);
        /* @var Character $character */
        $character->loadPowers();

        if ($options['edit_mode'] == 'open') {
            $character->CharacterName = htmlspecialchars($stats['character_name']);
            if (!$character->CharacterName) {
                $character->CharacterName = 'Character ' . mt_rand(9999999, 100000000);
            }
            $character->CharacterType = $stats['character_type'];
            $character->City = $stats['city'];
            $character->Age = $stats['age'] + 0;
            $character->ApparentAge = $stats['apparent_age'] + 0;
            $character->Sex = 'Male';//$stats['sex'];
            $character->Virtue = $stats['virtue'];
            $character->Vice = $stats['vice'];
            $character->Splat1 = ($stats['splat1']) ? $stats['splat1'] : '';
            $character->Splat2 = ($stats['splat2']) ? $stats['splat2'] : '';
            $character->Subsplat = ($stats['subsplat']) ? $stats['subsplat'] : '';
            $character->Concept = $stats['concept'];
            $character->PowerStat = $stats['power_trait'] + 0;
            $character->WillpowerPerm = $stats['willpower_perm'] + 0;
            $character->Morality = $stats['morality'] + 0;
            $character->Size = $stats['size'] + 0;
            $character->Speed = $stats['speed'] + 0;
            $character->InitiativeMod = $stats['initiative_mod'] + 0;
            $character->Defense = $stats['defense'] + 0;
            $character->Armor = $stats['armor'];
            $character->Health = $stats['health'] + 0;
            $character->PowerPointsModifier = $stats['power_points_modifier'] + 0;
            $character->BonusAttribute = ($stats['bonus_attribute']) ? $stats['bonus_attribute'] : '';
            $character->History = htmlspecialchars($stats['history']);
            $character->CharacterNotes = htmlspecialchars($stats['notes']);
            $character->Slug = $stats['slug'];
        }

        if (in_array($options['edit_mode'], ['open', 'limited'])) {
            $character->Description = htmlspecialchars($stats['description']);
            $character->Splat1 = ($stats['splat1']) ? $stats['splat1'] : '';
            $character->Splat2 = ($stats['splat2']) ? $stats['splat2'] : '';
            $character->PowerPoints = $stats['power_points'] + 0;
            $character->WoundsAgg = $stats['wounds_agg'] + 0;
            $character->WoundsLethal = $stats['wounds_lethal'] + 0;
            $character->WoundsBashing = $stats['wounds_bashing'] + 0;
            $character->WillpowerTemp = $stats['willpower_temp'] + 0;
            $character->Icon = $stats['icon'];
        }
        if ($options['show_admin']) {
            $character->Status = $stats['status'];
            $character->IsSanctioned = $stats['is_sanctioned'];
            $character->IsNpc = ($stats['is_npc'] == 'Y') ? 'Y' : 'N';
        }

        // fixed values
        $character->UpdatedOn = date('Y-m-d H:i:s');
        $character->Gameline = 'NWoD2';

        // values to figure out
        $character->ShowSheet = 'N';//$stats['show_sheet'];
        $character->ViewPassword = '';//$stats['view_password'];
        $character->HideIcon = 'N';//$stats['hide_icon'];
        $character->SafePlace = '';//$stats['safe_place'];
        $character->Friends = '';//$stats['friends'];
        $character->Helper = '';//$stats['friends'];

        // legacy values. Woof.
        $character->Merits = '';
        $character->Flaws = '';
        $character->EquipmentHidden = '';
        $character->EquipmentPublic = '';
        $character->PublicEffects = '';
        $character->Goals = '';
        $character->BonusReceived = 0;
        $character->GmNotes = '';
        $character->SheetUpdate = '';
        $character->MiscPowers = '';

        if (!$character->Id) {
            $character->UserId = $user['user_id'];
        }

        if (!$this->repository->save($character)) {
            return 'Error saving character.';
        }

        $characterPowers = [];

        if ($options['edit_mode'] == 'open') {
            // save attributes
            foreach ($stats['attribute'] as $attribute => $value) {
                $cp = $character->getAttribute($attribute);
                $cp->CharacterId = $character->Id;
                $cp->PowerLevel = $value;
                $characterPowers[] = $cp;
            }

            // save skills
            foreach ($stats['skill'] as $skill => $value) {
                $cp = $character->getSkill($skill);
                $cp->CharacterId = $character->Id;
                $cp->PowerLevel = $value;
                $characterPowers[] = $cp;
            }
        }

        // save all other powers
        foreach ($this->powerList[$options['edit_mode']] as $powerType) {
            if (isset($stats[$powerType]) && is_array($stats[$powerType])) {
                foreach ($stats[$powerType] as $power) {
                    $pp = [
                        'id' => ($power['id']) ? $power['id'] : null,
                        'power_type' => $powerType,
                        'power_name' => $power['name'],
                        'power_note' => $power['note'],
                        'power_level' => $power['level'],
                        'is_public' => $power['is_public'],
                    ];

                    $pp['extra'] = json_encode(array_diff($power, $pp));

                    $cp = new CharacterPower();
                    $cp->Id = $pp['id'];
                    $cp->CharacterId = $character->Id;
                    $cp->PowerType = $pp['power_type'];
                    $cp->PowerName = $pp['power_name'];
                    $cp->PowerNote = ($pp['power_note']) ? $pp['power_note'] : '';
                    $cp->PowerLevel = $pp['power_level'] + 0;
                    $cp->IsPublic = $pp['is_public'] + 0;
                    $cp->Extra = $pp['extra'];

                    $characterPowers[] = $cp;
                }
            }
        }

        foreach ($characterPowers as $characterPower) {
            /* @var CharacterPower $characterPower */
            if (!$characterPower->PowerName) {
                // blank name which can indicate deleting
                if ($characterPower->Id) {
                    // delete it
                    $this->powerRepository->delete($characterPower->Id);
                }
            } else {
                // save it
                $this->powerRepository->save($characterPower);
            }
        }

        return true;
    }

    /**
     * @param Character $newCharacter
     * @param Character $oldCharacter
     * @param array $user
     * @return bool
     */
    private function logChanges(Character $newCharacter, Character $oldCharacter, array $user)
    {
        if (!$oldCharacter->Id) {
            // first save
            return true;
        }

        if ($newCharacter->IsSanctioned != $oldCharacter->IsSanctioned) {
            if ($newCharacter->IsSanctioned == 'Y') {
                CharacterLog::LogAction($newCharacter->Id, ActionType::Sanctioned, 'ST Sanctioned Character', $user['user_id']);
            }
            if ($newCharacter->IsSanctioned == 'N') {
                CharacterLog::LogAction($newCharacter->Id, ActionType::Desanctioned, 'ST Desanctioned Character', $user['user_id']);
            }
        }

        $excludedProperties = [
            'SheetUpdate',
            'GmNotes'
        ];

        $changedProperties = [];
        foreach ($newCharacter as $property => $value) {
            if (!in_array($property, $excludedProperties)) {
                if ($newCharacter->$property != $oldCharacter->$property) {
                    $changedProperties[] = $property;
                }
            }
        }

        $note = "";
        if (count($changedProperties) > 0) {
            foreach ($changedProperties as $property) {
                $note .= $property . ' changed from ' . $oldCharacter->$property . ' to ' . $newCharacter->$property . "<br />";
            }
        }

        $newPowerList = $newCharacter->CharacterPower;
        $oldPowerList = $oldCharacter->CharacterPower;

        $changedPowerList = [];

        foreach ($newCharacter->CharacterPower as $i => $newPower) {
            foreach ($oldCharacter->CharacterPower as $j => $oldPower) {
                // if they are the same
                if ($newPower->Id == $oldPower->Id) {
                    if (($newPower->PowerName != $oldPower->PowerName)
                        || ($newPower->PowerNote != $oldPower->PowerNote)
                        || ($newPower->PowerLevel != $oldPower->PowerLevel)
                    ) {
                        $changedPowerList[] = array(
                            'old' => $oldPower,
                            'new' => $newPower
                        );
                    }
                    unset($newPowerList[$i]);
                    unset($oldPowerList[$j]);
                }
            }
        }

        foreach ($newPowerList as $newPower) {
            $note .= 'Added Power: ' . $newPower->PowerType .
                ' Name: ' . $newPower->PowerName .
                ' Note: ' . $newPower->PowerNote .
                ' Level: ' . $newPower->PowerLevel . "<br />";
        }

        foreach ($oldPowerList as $oldPower) {
            $note .= 'Removed Power: ' . $oldPower->PowerType .
                ' Name: ' . $oldPower->PowerName .
                ' Note: ' . $oldPower->PowerNote .
                ' Level: ' . $oldPower->PowerLevel . "<br />";
        }

        foreach ($changedPowerList as $power) {
            $note .= 'Changed Power: ' . $power['new']->PowerType .
                ' <b>OLD</b>: ' .
                ' Name: ' . $power['old']->PowerName .
                ' Note: ' . $power['old']->PowerNote .
                ' Level: ' . $power['old']->PowerLevel .
                ' <b>NEW</b> ' .
                ' Name: ' . $power['new']->PowerName .
                ' Note: ' . $power['new']->PowerNote .
                ' Level: ' . $power['new']->PowerLevel . "<br />";
        }

        CharacterLog::LogAction($newCharacter->Id, ActionType::UpdateCharacter, str_replace("\n", "<br/>", $note),
            $user['user_id']);

        return true;
    }

    public function listAvailableIcons()
    {
        $sql = <<<SQL
SELECT
  Icon_ID as id,
  Icon_Name as name
FROM
  icons
WHERE
  icons.Player_Viewable = 'Y'
SQL;

        $rows = [];
        foreach(Database::getInstance()->query($sql)->all() as $row) {
            $rows[$row['id']] = $row['name'];
        }
        return $rows;
    }
}
