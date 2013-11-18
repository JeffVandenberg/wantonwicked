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
use classes\log\CharacterLog;
use classes\log\data\ActionType;

class CharacterRepository extends AbstractRepository
{

    function __construct()
    {
        parent::__construct('classes\character\data\Character');
    }

    public function MayViewCharacter($characterId, $userId)
    {
        $characterId = (int) $characterId;
        $userId = (int) $userId;

        $sql = <<<EOQ
SELECT
    COUNT(*) As `rows`
FROM
    login_character_index AS LCI
WHERE
    login_id = $userId
    AND character_id = $characterId;
EOQ;
        $item = ExecuteQueryItem($sql);
        return $item['rows'] > 0;
    }

    public function FindById($characterId)
    {
        $characterId = (int) $characterId;
        $sql = <<<EOQ
SELECT
    *
FROM
    wod_characters
WHERE
    character_id = $characterId
EOQ;

        return ExecuteQueryItem($sql);
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
    wod_characters AS C
    INNER JOIN supporter_characters AS SC ON C.character_id = SC.character_id
WHERE
    SC.supporter_id = ?
    AND C.is_sanctioned = 'Y'
    AND C.is_deleted = 'N'
ORDER BY
    C.character_name
EOQ;

        $list = array();
        foreach($this->Query($sql)->Bind(1, $supporterId)->All() as $row)
        {
            $list[] = $this->PopulateObject($row);
        }
        return $list;
    }

    public function ClearBonusXP()
    {
        $sql = <<<EOQ
UPDATE
    wod_characters
SET
    bonus_received = 0;
EOQ;

        $this->Query($sql)->Execute();
    }

    public function AwardSupporterBonusXP($bonusXp = 5)
    {
        $date = date('Y-m-d');
        $sql = <<<EOQ
UPDATE
    wod_characters AS C
    INNER JOIN supporter_characters AS SC ON C.character_id = SC.character_id
    INNER JOIN supporters AS S ON SC.supporter_id = S.id
SET
    bonus_received = :xp,
    current_experience = current_experience + :xp,
    total_experience = total_experience + :xp
WHERE
    S.expires_on > :date
    AND S.number_of_characters > S.characters_awarded
    AND C.is_sanctioned = 'Y'
    AND C.is_deleted = 'N'
EOQ;

        $parameters = array('xp' => $bonusXp, 'date' => $date);
        $this->Query($sql)->Execute($parameters);

        $sql = <<<EOQ
SELECT
    character_id
FROM
    wod_characters AS C
WHERE
    bonus_received = ?
EOQ;

        $characters = $this->Query($sql)->All(array($bonusXp));
        foreach($characters as $character) {
            CharacterLog::LogAction($character['character_id'], ActionType::SupporterXP, 'Awarded Bonus XP for: ' . $date);
        }

        // set supporters
        $sql = <<<EOQ
UPDATE
    supporters AS S
SET
    S.characters_awarded = (
        SELECT
            COUNT(*)
        FROM
            wod_characters AS C
            INNER JOIN supporter_characters AS SC ON C.character_id = SC.character_id
        WHERE
            C.is_sanctioned = 'Y'
            AND C.is_deleted = 'N'
            AND C.bonus_received = ?
            AND SC.supporter_id = S.id
        GROUP BY
            S.id
    )
WHERE
    S.expires_on > ?
EOQ;
        $this->Query($sql)->Execute(array($bonusXp, $date));
    }

    public function ListSupporterCharacters()
    {
        $sql = <<<EOQ
SELECT
    C.*
FROM
    wod_characters AS C
    INNER JOIN supporter_characters AS SC ON C.character_id = SC.character_id
    INNER JOIN supporters AS S ON SC.supporter_id = S.id
WHERE
    C.is_sanctioned = 'Y'
    AND C.is_deleted = 'N'
EOQ;

        return $this->Query($sql)->All();
    }

    public function FindByIdObj($characterId)
    {
        $sql = <<<EOQ
SELECT
    *
FROM
    wod_characters AS C
WHERE
    C.character_id = ?
EOQ;

        return $this->PopulateObject($this->Query($sql)->Single(array($characterId)));
    }

    public function ListCharactersByPlayerId($userId)
    {
        $sql = <<<EOQ
SELECT
    C.character_id,
    C.character_name,
    C.is_sanctioned,
    U.username as updated_by_name,
    C.when_last_st_updated as updated_on
FROM
    wod_characters AS C
    LEFT JOIN phpbb_users as U ON C.last_st_updated = U.user_id
WHERE
    C.primary_login_id = ?
    AND C.is_deleted = 'N'
ORDER BY
    is_sanctioned ASC,
    character_name
EOQ;

        return $this->Query($sql)->All(array($userId));
    }
}