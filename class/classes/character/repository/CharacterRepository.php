<?php
/**
 * Created by JetBrains PhpStorm.
 * User: JeffVandenberg
 * Date: 8/11/13
 * Time: 10:20 AM
 * To change this template use File | Settings | File Templates.
 */

namespace classes\character\repository;


use classes\character\data\Character;
use classes\core\repository\AbstractRepository;
use classes\core\repository\Database;
use classes\log\CharacterLog;
use classes\log\data\ActionType;
use classes\request\repository\RequestRepository;

class CharacterRepository extends AbstractRepository
{

    function __construct()
    {
        parent::__construct('classes\character\data\Character');
    }

    public function MayViewCharacter($characterId, $userId)
    {
        $characterId = (int)$characterId;
        $userId = (int)$userId;

        $sql = <<<EOQ
SELECT
    COUNT(*) As `rows`
FROM
    characters
WHERE
    user_id = $userId
    AND id = $characterId;
EOQ;
        $item = ExecuteQueryItem($sql);
        return $item['rows'] > 0;
    }

    public function FindById($characterId)
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
        $params = array($characterId);
        return $this->query($sql)->single($params);
    }

    /**
     * @param $supporterId
     * @return Character[]
     */
    public function ListSelectedCharactersForSupporter($supporterId)
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

    public function ClearBonusXP()
    {
        $sql = <<<EOQ
UPDATE
    characters
SET
    bonus_received = 0;
EOQ;

        $this->query($sql)->execute();
    }

    public function AwardSupporterBonusXP($bonusXp = 5)
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
            CharacterLog::LogAction($character['id'], ActionType::SupporterXP, 'Awarded Bonus XP for: ' . $date);
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

    public function ListSupporterCharacters()
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

    public function FindByIdObj($characterId)
    {
        $sql = <<<EOQ
SELECT
    *
FROM
    characters AS C
WHERE
    C.id = ?
EOQ;

        return $this->populateObject($this->query($sql)->single(array($characterId)));
    }

    public function ListCharactersByPlayerId($userId)
    {
        $sql = <<<EOQ
SELECT
    C.id,
    C.character_name,
    C.is_sanctioned,
    U.username as updated_by_name,
    C.updated_on
FROM
    characters AS C
    LEFT JOIN phpbb_users as U ON C.updated_by_id = U.user_id
WHERE
    C.user_id = ?
    AND C.is_deleted = 'N'
ORDER BY
    is_sanctioned ASC,
    character_name
EOQ;

        return $this->query($sql)->all(array($userId));
    }

    public function ListSanctionedCharactersByPlayerId($userId)
    {
        $sql = <<<EOQ
SELECT
    C.id,
    C.character_name,
    C.is_sanctioned,
    U.username as updated_by_name,
    C.updated_on
FROM
    characters AS C
    LEFT JOIN phpbb_users as U ON C.updated_by_id = U.user_id
WHERE
    C.user_id = ?
    AND C.is_deleted = 'N'
    AND C.is_sanctioned = 'Y'
ORDER BY
    character_name
EOQ;

        return $this->query($sql)->all(array($userId));
    }

    public function FindByName($characterName)
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

    public function AutocompleteSearch($characterName, $onlySanctioned)
    {
        $sql = <<<EOQ
SELECT
    C.id,
    C.character_name
FROM
    characters AS C
WHERE
    C.is_deleted = 'N'
    AND (
        (C.is_sanctioned = 'Y' AND (:only_sanctioned = 1))
        OR
        (:only_sanctioned = 0)
    )
    AND C.character_name like :character_name
ORDER BY
    C.character_name
LIMIT 20
EOQ;
        $params = array(
            'only_sanctioned' => (int)$onlySanctioned,
            'character_name' => $characterName . '%'
        );
        return $this->query($sql)->all($params);
    }

    public function UnsanctionInactiveCharacters($cutoffDate)
    {
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
    C.is_sanctioned = 'Y'
    AND C.is_npc = 'N'
	AND A.rows IS NULL
EOQ;

        $unsanctionLogParams = array($cutoffDate, ActionType::Sanctioned, ActionType::Login);

        $characterList = $this->query($characterListQuery)->all($unsanctionLogParams);
        $characterIds = array_map(function ($item) {
            return $item['id'];
        }, $characterList);
        if (count($characterIds)) {
            $characterIdPlaceholders = implode(',', array_fill(0, count($characterIds), '?'));

            // add desanction note to character log
            $unsanctionLogQuery = <<<EOQ
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
    'Auto-Desanction for inactivity',
    NOW()
FROM
    characters
WHERE
    id IN ($characterIdPlaceholders)
EOQ;
            $unsanctionLogParams = array_merge(array(ActionType::Desanctioned), $characterIds);
            $this->query($unsanctionLogQuery)->execute($unsanctionLogParams);

            // desanction the characters
            $unsanctionQuery = <<<EOQ
UPDATE
    characters
SET
    is_sanctioned='n'
WHERE
    id IN ($characterIdPlaceholders)
EOQ;

            $unsanctionParams = $characterIds;
            $this->query($unsanctionQuery)->execute($unsanctionParams);

            // close all requests attached to the characters
            $requestRepository = new RequestRepository();
            $requestRepository->CloseRequestsForCharacter($characterIds);
        }

        return count($characterIds);
    }

    public function DoesCharacterHavePowerAtLevel($characterId, $powerName, $powerLevel)
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

    public function isNameInUse($character_name, $character_id)
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
EOQ;
        $params = array(
            $character_name,
            $character_id
        );

        return ($this->Query($sql)->Value($params) > 0);
    }
}