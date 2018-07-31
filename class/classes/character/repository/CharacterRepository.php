<?php
/**
 * Created by JetBrains PhpStorm.
 * User: JeffVandenberg
 * Date: 8/11/13
 * Time: 10:20 AM
 * To change this template use File | Settings | File Templates.
 */

namespace classes\character\repository;


use classes\character\data\BeatStatus;
use classes\character\data\Character;
use classes\character\data\CharacterStatus;
use classes\core\repository\AbstractRepository;
use classes\core\repository\Database;
use classes\log\CharacterLog;
use classes\log\data\ActionType;
use classes\request\repository\RequestRepository;

/**
 * Class CharacterRepository
 * @package classes\character\repository
 */
class CharacterRepository extends AbstractRepository
{

    public function __construct()
    {
        parent::__construct(Character::class);
    }

    public function mayViewCharacter($characterId, $userId): bool
    {
        $characterId = (int)$characterId;
        $userId = (int)$userId;

        $sql = <<<EOQ
SELECT
    COUNT(*) As `rows`
FROM
    characters
WHERE
    user_id = ?
    AND id = ?
EOQ;
        $params = array(
            $userId,
            $characterId
        );
        return $this->query($sql)->value($params) > 0;
    }

    public function findById($characterId)
    {
        $characterId = (int)$characterId;
        $sql = <<<EOQ
SELECT
    U.username,
    C.*,
    UU.username as updated_by_username
FROM
    characters AS C
    INNER JOIN phpbb_users AS U ON C.user_id = U.user_id
    LEFT JOIN phpbb_users AS UU ON C.updated_by_id = UU.user_id
WHERE
    C.id = ?
EOQ;
        $params = [$characterId];
        return $this->query($sql)->single($params);
    }

    /**
     * @param $supporterId
     * @return Character[]
     */
    public function listSelectedCharactersForSupporter($supporterId): array
    {
        $sql = <<<EOQ
SELECT
    C.*
FROM
    characters AS C
    INNER JOIN supporter_characters AS SC ON C.id = SC.character_id
WHERE
    SC.supporter_id = ?
    AND C.is_sanctioned = 'Y'
    AND C.is_deleted = 'N'
ORDER BY
    C.character_name
EOQ;

        $list = array();
        foreach ($this->query($sql)->bind(1, $supporterId)->all() as $row) {
            $list[] = $this->populateObject($row);
        }
        return $list;
    }

    public function clearBonusXP(): void
    {
        $sql = <<<EOQ
UPDATE
    characters
SET
    bonus_received = 0;
EOQ;

        $this->query($sql)->execute();
    }

    public function awardSupporterBonusXP($bonusXp = 5): void
    {
        $date = date('Y-m-d');
        $sql = <<<EOQ
UPDATE
    characters AS C
    INNER JOIN supporter_characters AS SC ON C.id = SC.character_id
    INNER JOIN supporters AS S ON SC.supporter_id = S.id
SET
    bonus_received = :xp,
    current_experience = current_experience + :xp,
    total_experience = total_experience + :xp
WHERE
    S.expires_on > '$date'
    AND S.number_of_characters > S.characters_awarded
    AND C.is_sanctioned = 'Y'
    AND C.is_deleted = 'N'
    AND C.bonus_received = 0
EOQ;

        $parameters = array('xp' => $bonusXp);
        $this->query($sql)->execute($parameters);

        $sql = <<<EOQ
SELECT
    id
FROM
    characters AS C
WHERE
    bonus_received = ?
EOQ;

        $characters = $this->query($sql)->all(array($bonusXp));
        foreach ($characters as $character) {
            CharacterLog::logAction($character['id'], ActionType::SUPPORTER_XP, 'Awarded Bonus XP for: ' . $date);
        }

        // set supporters
        $sql = <<<EOQ
UPDATE
    supporters AS S
SET
    S.characters_awarded = IFNULL((
        SELECT
            COUNT(*)
        FROM
            characters AS C
            INNER JOIN supporter_characters AS SC ON C.id = SC.character_id
        WHERE
            C.is_sanctioned = 'Y'
            AND C.is_deleted = 'N'
            AND C.bonus_received = ?
            AND SC.supporter_id = S.id
        GROUP BY
            S.id
    ),0)
WHERE
    S.expires_on >= '$date'
EOQ;
        $this->query($sql)->execute(array($bonusXp));
    }

    public function listSupporterCharacters(): array
    {
        $sql = <<<EOQ
SELECT
    C.*
FROM
    characters AS C
    INNER JOIN supporter_characters AS SC ON C.id = SC.character_id
    INNER JOIN supporters AS S ON SC.supporter_id = S.id
WHERE
    C.is_sanctioned = 'Y'
    AND C.is_deleted = 'N'
EOQ;

        return $this->query($sql)->all();
    }

    public function findByIdObj($characterId)
    {
        $sql = <<<EOQ
SELECT
    *
FROM
    characters AS C
WHERE
    C.id = ?
EOQ;

        return $this->populateObject($this->query($sql)->single([$characterId]));
    }

    public function listCharactersByPlayerId($userId): array
    {
        $sql = <<<EOQ
SELECT
    C.id,
    C.character_name,
    C.character_status_id,
    U.username as updated_by_name,
    C.updated_on
FROM
    characters AS C
    LEFT JOIN phpbb_users as U ON C.updated_by_id = U.user_id
    LEFT JOIN character_statuses AS CS ON C.character_status_id = CS.id
WHERE
    C.user_id = ?
    AND C.character_status_id != ?
ORDER BY
    CS.sort_order,
    character_name
EOQ;
        $params = [
            $userId,
            CharacterStatus::DELETED
        ];

        return $this->query($sql)->all($params);
    }

    public function listSanctionedCharactersByPlayerId($userId): array
    {
        $placeholders = implode(',', array_fill(0, count(CharacterStatus::Sanctioned), '?'));
        $sql = <<<EOQ
SELECT
    C.id,
    C.character_name,
    C.character_status_id,
    C.slug,
    U.username as updated_by_name,
    C.updated_on
FROM
    characters AS C
    LEFT JOIN phpbb_users as U ON C.updated_by_id = U.user_id
WHERE
    C.user_id = ?
    AND C.character_status_id IN ($placeholders)
ORDER BY
    character_name
EOQ;

        $params = array_merge([$userId], CharacterStatus::Sanctioned);
        return $this->query($sql)->all($params);
    }

    public function findByName($characterName)
    {
        $query = <<<EOQ
SELECT
    U.username,
    C.*,
    UU.username as updated_by_username
FROM
    characters AS C
    INNER JOIN phpbb_users AS U ON C.user_id = U.user_id
    LEFT JOIN phpbb_users AS UU ON C.updated_by_id = UU.user_id
WHERE
    character_name = ?
EOQ;

        $params = array($characterName);
        return $this->query($query)->single($params);
    }

    public function autocompleteSearch($characterName, $onlySanctioned, $city = 'portland'): array
    {
        $statusIds = implode(',', CharacterStatus::Sanctioned);
        $nonDeletedStatuses = implode(',', CharacterStatus::NonDeleted);
        $sql = <<<EOQ
SELECT
    C.id,
    C.character_name
FROM
    characters AS C
WHERE
    (
        (C.character_status_id IN ($statusIds) AND (:only_sanctioned = 1))
        OR
        (C.character_status_id IN ($nonDeletedStatuses) AND (:only_sanctioned = 0))
    )
    AND C.character_name like :character_name
    AND C.city = :city
ORDER BY
    C.character_name
LIMIT 20
EOQ;
        $params = array(
            'only_sanctioned' => (int)$onlySanctioned,
            'character_name' => $characterName . '%',
            'city' => $city
        );
        return $this->query($sql)->all($params);
    }

    public function doesCharacterHavePowerAtLevel($characterId, $powerName, $powerLevel): bool
    {
        $query = <<<EOQ
SELECT
	count(*) as HitCount
FROM
	character_powers
WHERE
	character_id = ?
	AND power_name = ?
	AND power_level >= ?
EOQ;
        $params = array(
            $characterId,
            $powerName,
            $powerLevel
        );

        $row = Database::getInstance()->query($query)->single($params);
        return ($row['HitCount'] > 0);
    }

    public function isNameInUse($character_name, $character_id, $city = 'portland'): bool
    {
        $str_to_find = array("'", "\"");
        $str_to_replace = array("-", "-");
        $character_name =
            htmlspecialchars(
                str_replace(
                    $str_to_find,
                    $str_to_replace,
                    stripslashes($character_name)
                )
            );

        $sql = <<<EOQ
select
    count(*) as hits
FROM
    characters
WHERE
    character_name = ?
    AND id != ?
    AND city = ?
EOQ;
        $params = array(
            $character_name,
            $character_id,
            $city
        );

        return ($this->query($sql)->value($params) > 0);
    }

    /**
     * @param $userId
     * @return array
     */
    public function listForDashboard($userId): array
    {
        $activeStatuses = CharacterStatus::NonDeleted;
        $statusPlaceholders = implode(',', array_fill(0, count($activeStatuses), '?'));

        $sql = <<<SQL
SELECT
  C.*
FROM
  characters AS C
  LEFT JOIN character_statuses AS CS ON C.character_status_id = CS.id
WHERE
  user_id = ?
  AND C.character_status_id IN ($statusPlaceholders)
  AND city = 'portland'
ORDER BY
  CS.sort_order,
  character_name
SQL;

        $params = array_merge([$userId], $activeStatuses);
        $rows = [];
        foreach($this->query($sql)->all($params) as $row) {
            $rows[] = $this->populateObject($row);
        }

        return $rows;
    }

    public function listCharactersWithOutstandingBeats(): array
    {
        $sql = <<<SQL
SELECT
  DISTINCT C.id, C.character_name
FROM
  characters AS C
  INNER JOIN character_beats AS CB ON C.id = CB.character_id
WHERE
  CB.beat_status_id IN (?, ?)
SQL;
        $params = [
            BeatStatus::NEW_BEAT,
            BeatStatus::STAFF_AWARDED
        ];

        return $this->query($sql)->all($params);
    }

    public function findCharactersWithNoActivityInDate($currentStatusId, $dateAdjustment)
    {
        $cutoffDate = date('Y-m-d', strtotime($dateAdjustment));
        // get list of characters
        $characterListQuery = <<<EOQ
SELECT
    DISTINCT
    id
FROM
    characters AS C
    LEFT JOIN (
        SELECT
            LC.character_id,
            count(*) AS `rows`
        FROM
            log_characters AS LC
        WHERE
            LC.created >= ?
            AND LC.action_type_id IN (?, ?)
		GROUP BY
			LC.character_id
    ) AS A ON C.id = A.character_id
WHERE
    C.character_status_id = ?
    AND C.is_npc = 'N'
	AND A.rows IS NULL
EOQ;

        $params = array($cutoffDate, ActionType::SANCTIONED, ActionType::LOGIN, CharacterStatus::ACTIVE);

        $characterList = $this->query($characterListQuery)->all($params);
        $characterIds = array_map(function ($item) {
            return $item['id'];
        }, $characterList);

        return $characterIds;
    }

    public function migrateCharactersToNewStatus($characterIds, $statusId, $logNote): void
    {
        if (count($characterIds)) {
            $characterIdPlaceholders = implode(',', array_fill(0, count($characterIds), '?'));

            // add desanction note to character log
            $query = <<<EOQ
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
    ?,
    NOW()
FROM
    characters
WHERE
    id IN ($characterIdPlaceholders)
EOQ;
            $unsanctionLogParams = array_merge([ActionType::UPDATE_CHARACTER, $logNote], $characterIds);
            $this->query($query)->execute($unsanctionLogParams);

            // migrate characters to new status
            $query = <<<EOQ
UPDATE
    characters
SET
    character_status_id = ?
WHERE
    id IN ($characterIdPlaceholders)
EOQ;

            $params = array_merge([$statusId], $characterIds);
            $this->query($query)->execute($params);
        }
    }
}
