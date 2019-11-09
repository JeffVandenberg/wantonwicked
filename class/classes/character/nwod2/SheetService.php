<?php
/**
 * Created by PhpStorm.
 * User: JeffVandenberg
 * Date: 1/16/2017
 * Time: 11:21 AM
 */

namespace classes\character\nwod2;


use classes\character\data\Character;
use classes\character\data\CharacterNote;
use classes\character\data\CharacterPower;
use classes\character\data\CharacterStatus;
use classes\character\repository\CharacterPowerRepository;
use classes\character\repository\CharacterRepository;
use classes\core\data\DataModel;
use classes\core\repository\Database;
use classes\core\repository\RepositoryManager;
use classes\log\CharacterLog;
use classes\log\data\ActionType;
use classes\request\repository\RequestRepository;
use classes\request\data\Request;
use Exception;
use function in_array;
use function is_array;
use function is_int;
use function is_string;

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
            'attainment',
            'touchstone',
            'pledge',
            'renown',
            'misc',
            'theme',
            'twist',
            'malison',
            'atavism',
            'nightmare',
            'lairTrait',
            'clarityHealth',
        ],
        'limited' => [
            'aspiration',
            'conditions',
            'clarityHealth',
        ]
    ];
    /**
     * @var CharacterRepository
     */
    private $repository;
    /**
     * @var CharacterPowerRepository
     */
    private $powerRepository;

    /**
     * SheetService constructor.
     */
    public function __construct()
    {
        $this->repository = new CharacterRepository();
        $this->powerRepository = new CharacterPowerRepository();
    }

    /**
     * @param string $characterType
     * @return Character
     */
    public function initializeSheet($characterType = 'mortal'): Character
    {
        $character = new Character();
        $characterType = $characterType ?: 'mortal';
        $character->CharacterType = $characterType;
        $character->Size = 5;
        $character->Morality = 7;

        // initialize specialities
        $this->addList($character, 3, 'specialty');
        $this->addList($character, 5, 'merit');
        $this->addList($character, 2, 'misc_power');
        $this->addList($character, 4, 'equipment');
        $this->addList($character, 3, 'aspiration');

        $this->addCharacterTypePowers($character);

        return $character;
    }

    /**
     * @param Character $character
     * @param array $powerTypes
     */
    public function addMinPowers(Character $character, array $powerTypes = []): void
    {
        $powerTypeList = [
            'specialty' => 3,
            'merit' => 5,
            'misc_power' => 2,
            'equipment' => 3,
            'aspiration' => 3,
        ];
        if (!count($powerTypes)) {
            $powerTypes = array_keys($powerTypeList);
        }

        foreach ($powerTypeList as $type => $min) {
            if (in_array($type, $powerTypes, true) && count($character->getPowerList($type)) < $min) {
                $this->addList($character, $min - count($character->getPowerList($type)), $type);
            }
        }

        $this->addCharacterTypePowers($character);
    }

    /**
     * @param Character $character
     */
    private function addCharacterTypePowers(Character $character): void
    {
        $powers = [];
        switch ($character->CharacterType) {
            case 'mortal':
            case 'wolfblooded':
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
                    'nimbus' => 1,
                    'attainment' => 1
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
                    'contract' => 5,
                    'trigger' => 3,
                    'touchstone' => 1,
                    'pledge' => 1,
                    'clarityHealth' => 1,
                ];
                break;
            case 'fae-touched':
                $powers = [
                    'contract' => 2,
                ];
                break;
            case 'ghoul':
                $powers = [
                    'icdisc' => 1,
                    'oocdisc' => 1,
                    'devotion' => 1,
                ];
                break;
            case 'dhampir':
                $powers = [
                    'theme' => 2,
                    'twist' => 2,
                    'malison' => 2,
                    'break_point' => 5,
                ];
                break;
            case 'beast':
                $powers = [
                    'atavism' => 3,
                    'nightmare' => 3,
                    'lairTrait' => 1
                ];
                break;
        }

            foreach ($powers as $type => $min) {
            if (count($character->getPowerList($type)) < $min) {
                $count = $min - count($character->getPowerList($type));

                $this->addList($character, $count, $type);

                if (($character->CharacterType === 'vampire') && ($type === 'touchstone') && $count > 0) {
                    $list = $character->getPowerList('touchstone');
                    $list[0]->PowerLevel = 6;
                }

                if (($character->CharacterType === 'werewolf') && ($type === 'touchstone') && $count > 0) {
                    $list = $character->getPowerList('touchstone');
                    $list[0]->PowerName = 'Physical';
                    $list[1]->PowerName = 'Spiritual';
                }
            }
        }
    }

    /**
     * @param Character $character
     * @param $numOfItems
     * @param $powerType
     */
    public function addList(Character $character, $numOfItems, $powerType): void
    {
        $i = 0;
        while($i++ < $numOfItems) {
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
        if (!$identifier) {
            return new Character();
        }

        if (is_int($identifier) || ((int)$identifier > 0)) {
            $character = $this->repository->getById($identifier);
        } else {
            $character = $this->repository->FindBySlug($identifier);
        }
        /* @var Character $character */

        if ($characterType) {
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

        // load old powers
        count($oldCharacter->CharacterPower);

        // save new data
        $result = $this->saveData($stats, $options, $user);

        if (is_string($result)) {
            return $result;
        }

        if ($oldCharacter->Id) {
            // log xp change
            if (isset($stats['xp_spent']) && $stats['xp_spent'] > 0) {
                CharacterLog::logAction($stats['character_id'], ActionType::XP_MODIFICATION, 'Removed ' . $stats['xp_spent'] . 'XP: ' . $stats['xp_note'], $user['user_id']);
            }
            if (isset($stats['xp_gained']) && $stats['xp_gained'] > 0) {
                CharacterLog::logAction($stats['character_id'], ActionType::XP_MODIFICATION, 'Added ' . $stats['xp_gained'] . 'XP: ' . $stats['xp_note'], $user['user_id']);
            }

            // log character differences
            RepositoryManager::clearCache();
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
     * @throws Exception
     */
    public function saveData(array $stats, array $options, array $user): bool
    {
        // clean data
        array_walk_recursive($stats, function (&$item, $value) {
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

        if ($options['edit_mode'] === 'open') {
            $character->CharacterName = htmlspecialchars($stats['character_name']);
            if (!$character->CharacterName) {
                $character->CharacterName = 'Character ' . random_int(9999999, 100000000);
            }
            $character->CharacterType = $stats['character_type'];
            $character->City = $stats['city'];
            $character->Age = (int) $stats['age'];
            $character->ApparentAge = $stats['apparent_age'] ?? 0;
            $character->Sex = 'Male';
            $character->Virtue = $stats['virtue'];
            $character->Vice = $stats['vice'];
            $character->Splat1 = $stats['splat1'] ?? '';
            $character->Splat2 = $stats['splat2'] ?? '';
            $character->Subsplat = $stats['subsplat'] ?? '';
            $character->Concept = $stats['concept'];
            $character->PowerStat = $stats['power_stat'] ?? 0;
            $character->WillpowerPerm = $stats['willpower_perm'] + 0;
            $character->Morality = $stats['morality'] + 0;
            $character->Size = (int) $stats['size'];
            $character->Speed = (int) $stats['speed'];
            $character->InitiativeMod = (int) $stats['initiative_mod'];
            $character->Defense = (int) $stats['defense'];
            $character->Armor = $stats['armor'];
            $character->Health = $stats['health'] + 0;
            $character->PowerPointsModifier = $stats['power_points_modifier'] ?? 0;
            $character->BonusAttribute = $stats['bonus_attribute'] ?? '';
            $character->History = htmlspecialchars($stats['history']);
            $character->CharacterNotes = htmlspecialchars($stats['notes']);
            $character->Slug = $stats['slug'];
            $character->Friends = $stats['friends'] ?? '';
        }

        if (in_array($options['edit_mode'], ['open', 'limited'])) {
            $character->Description = htmlspecialchars($stats['description']);
            $character->Splat1 = $stats['splat1'] ?: '';
            $character->Splat2 = $stats['splat2'] ?: '';
            $character->PowerPoints = (int) $stats['power_points'];
            $character->WoundsAgg = (int) $stats['wounds_agg'];
            $character->WoundsLethal = (int) $stats['wounds_lethal'];
            $character->WoundsBashing = (int) $stats['wounds_bashing'];
            $character->WillpowerTemp = (int) $stats['willpower_temp'];
            $character->Icon = $stats['icon'];
        }
        if ($options['show_admin']) {
            $character->Status = $stats['status'];
            $character->CharacterStatusId = $stats['character_status_id'];
            $character->IsNpc = ($stats['is_npc'] === 'Y') ? 'Y' : 'N';
            if ($stats['xp_spent'] > 0) {
                $character->CurrentExperience -= $stats['xp_spent'];
            }
            if ($stats['xp_gained'] > 0) {
                $character->CurrentExperience += $stats['xp_gained'];
                $character->TotalExperience += $stats['xp_gained'];
                $character->BonusReceived += $stats['xp_gained'];
            }

            if ($stats['st_note']) {
                $note = new CharacterNote();
                $note->CharacterId = $stats['character_id'];
                $note->UserId = $user['user_id'];
                $note->Note = $stats['st_note'];
                $note->Created = date('Y-m-d H:i:s');
                $noteRepo = RepositoryManager::getRepository(CharacterNote::class);
                $noteRepo->save($note);
            }
        }

        if ($options['owner']) {
            $character->ViewPassword = $stats['view_password'];
        }

        // fixed values
        $character->UpdatedById = $user['user_id'];
        $character->UpdatedOn = date('Y-m-d H:i:s');
        $character->Gameline = 'NWoD2';

        // values to figure out
        $character->ShowSheet = 'N';
        $character->HideIcon = 'N';
        $character->SafePlace = '';
        $character->Helper = '';

        // legacy values. Woof.
        $character->Merits = '';
        $character->Flaws = '';
        $character->EquipmentHidden = '';
        $character->EquipmentPublic = '';
        $character->PublicEffects = '';
        $character->Goals = '';
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

        if ($options['edit_mode'] === 'open') {
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
                        'id' => $power['id'] ?: null,
                        'power_type' => $powerType,
                        'power_name' => $power['name'],
                        'power_note' => $power['note'] ?? '',
                        'power_level' => $power['level'] ?? 0,
                        'is_public' => $power['is_public'] ?? false,
                    ];

                    $pp['extra'] = json_encode(array_diff($power, $pp));

                    $cp = new CharacterPower();
                    $cp->Id = $pp['id'];
                    $cp->CharacterId = $character->Id;
                    $cp->PowerType = $pp['power_type'];
                    $cp->PowerName = $pp['power_name'];
                    $cp->PowerNote = $pp['power_note'] ?? '';
                    $cp->PowerLevel = (int)$pp['power_level'];
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
    private function logChanges(Character $newCharacter, Character $oldCharacter, array $user): bool
    {
        if (!$oldCharacter->Id) {
            // first save
            return true;
        }

        if ((int)$newCharacter->CharacterStatusId !== (int)$oldCharacter->CharacterStatusId) {
            if ((int)$newCharacter->CharacterStatusId === CharacterStatus::ACTIVE) {
                CharacterLog::logAction($newCharacter->Id, ActionType::SANCTIONED, 'ST Sanctioned Character', $user['user_id']);
            }
            if ((int)$newCharacter->CharacterStatusId === CharacterStatus::UNSANCTIONED) {
                CharacterLog::logAction($newCharacter->Id, ActionType::DESANCTIONED, 'ST Desanctioned Character', $user['user_id']);
            }
        }

        $excludedProperties = [
            'SheetUpdate',
            'GmNotes'
        ];

        $changedProperties = [];
        foreach ($newCharacter as $property => $value) {
            if ($newCharacter->$property !== $oldCharacter->$property && !in_array($property, $excludedProperties, true)) {
                $changedProperties[] = $property;
            }
        }

        $note = '';
        if (count($changedProperties) > 0) {
            foreach ($changedProperties as $property) {
                $note .= $property . ' changed from ' . $oldCharacter->$property . ' to ' . $newCharacter->$property . '<br />';
            }
        }

        $newPowerList = $newCharacter->CharacterPower;
        $oldPowerList = $oldCharacter->CharacterPower;

        $changedPowerList = [];

        foreach ($newCharacter->CharacterPower as $i => $newPower) {
            foreach ($oldCharacter->CharacterPower as $j => $oldPower) {
                // if they are the same
                if ((int)$newPower->Id === (int)$oldPower->Id) {
                    if (($newPower->PowerName !== $oldPower->PowerName)
                        || ($newPower->PowerNote !== $oldPower->PowerNote)
                        || ((int)$newPower->PowerLevel !== (int)$oldPower->PowerLevel)
                    ) {
                        $changedPowerList[] = array(
                            'old' => $oldPower,
                            'new' => $newPower
                        );
                    }
                    unset($newPowerList[$i], $oldPowerList[$j]);
                }
            }
        }

        foreach ($newPowerList as $newPower) {
            $note .= 'Added Power: ' . $newPower->PowerType .
                ' Name: ' . $newPower->PowerName .
                ' Note: ' . $newPower->PowerNote .
                ' Level: ' . $newPower->PowerLevel . '<br />';
        }

        foreach ($oldPowerList as $oldPower) {
            $note .= 'Removed Power: ' . $oldPower->PowerType .
                ' Name: ' . $oldPower->PowerName .
                ' Note: ' . $oldPower->PowerNote .
                ' Level: ' . $oldPower->PowerLevel . '<br />';
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
                ' Level: ' . $power['new']->PowerLevel . '<br />';
        }

        CharacterLog::logAction($newCharacter->Id, ActionType::UPDATE_CHARACTER, str_replace("\n", '<br/>', $note),
            $user['user_id']);

        return true;
    }

    /**
     * @return array
     */
    public function listAvailableIcons(): array
    {
        $sql = <<<SQL
SELECT
  icon_id AS id,
  icon_name AS name
FROM
  icons
WHERE
  icons.player_viewable = 'Y'
SQL;

        $rows = [];
        foreach (Database::getInstance()->query($sql)->all() as $row) {
            $rows[$row['id']] = $row['name'];
        }
        return $rows;
    }

    /**
     * @param $characterId
     * @param $xpAmount
     * @param $logNote
     * @param $userId
     * @return bool
     */
    public function grantXpToCharacter($characterId, $xpAmount, $logNote, $userId): bool
    {
        $character = $this->repository->getById($characterId);
        /* @var Character $character */

        if (!$characterId) {
            return false;
        }

        $character->CurrentExperience += $xpAmount;
        $character->TotalExperience += $xpAmount;

        $this->repository->save($character);
        $logNote .= ('<br />Current XP: ' . $character->CurrentExperience
            . '<br />Total Experience: ' . $character->TotalExperience);
        CharacterLog::logAction($characterId, ActionType::XP_MODIFICATION, $logNote, $userId);
        return true;
    }

    /**
     * @param $xpAward
     */
    public function awardXpToActiveCharacters($xpAward): void
    {
        $xpAward = (int)$xpAward;
        $update_experience_query = <<<SQL
UPDATE 
  characters 
SET 
  current_experience = current_experience + ?, 
  total_experience = total_experience + ?, 
  bonus_received = 0 
WHERE 
  character_status_id = ?;
SQL;

        $this->repository->query($update_experience_query)->execute([
            $xpAward,
            $xpAward,
            CharacterStatus::ACTIVE
        ]);

        $xpLogQuery = <<<EOQ
INSERT INTO
    log_characters
    (
        character_id,
        action_type_id,
        note,
        created
    )
SELECT
    id,
    ?,
    'Monthly XP Award: $xpAward',
    NOW()
FROM
    characters AS C
WHERE
    C.character_status_id = ?;
EOQ;
        $params = [
            ActionType::XP_MODIFICATION,
            CharacterStatus::ACTIVE
        ];
        $this->repository->query($xpLogQuery)->execute($params);
    }

    /**
     *
     */
    public function restoreTempWillpower(): void
    {
        $update_willpower_query = 'UPDATE characters SET willpower_temp = willpower_temp + 1 WHERE willpower_temp < willpower_perm;';
        $this->repository->query($update_willpower_query)->execute();
    }

    /**
     * @return array
     */
    public function checkCharacterActivity(): array
    {
        // mark idle characters with 1 month of no activity
        $idleCharacterIds = $this->repository->findCharactersWithNoActivityInDate(
            CharacterStatus::ACTIVE,
            '-1 month');
        $this->repository->migrateCharactersToNewStatus(
            $idleCharacterIds,
            CharacterStatus::IDLE,
            'Moved to Idle status for inactivity'
        );

        // mark inactive characters with 4 months of no activity
        $inactiveCharacterIds = $this->repository->findCharactersWithNoActivityInDate(
            CharacterStatus::IDLE,
            '-4 month'
        );
        $this->repository->migrateCharactersToNewStatus(
            $inactiveCharacterIds,
            CharacterStatus::INACTIVE,
            'Moved to Inactive status for inactivity'
        );

        if (count($inactiveCharacterIds)) {
            // close requests for Inactive Characters
            $requestRepository = RepositoryManager::getRepository(Request::class);
            /* @var RequestRepository $requestRepository */
            $requestRepository->closeRequestsForCharacter($inactiveCharacterIds);
        }

        return [
            'idle' => count($idleCharacterIds),
            'inactive' => count($inactiveCharacterIds)
        ];
    }

    /**
     * @param Character $character
     * @return bool
     */
    public function saveModel(Character $character): ?bool
    {
        $this->repository->save($character);
    }

    /**
     * @param Character $character
     * @param int $userId
     * @param string $note
     */
    public function reactivateCharacter(Character $character, int $userId, string $note): void
    {
        $character->CharacterStatusId = CharacterStatus::ACTIVE;
        $this->repository->save($character);
        CharacterLog::logAction($character->Id, ActionType::UPDATE_CHARACTER, $note, $userId);
    }
}
