<?php
/**
 * Created by JetBrains PhpStorm.
 * User: JeffVandenberg
 * Date: 9/25/13
 * Time: 11:31 PM
 * To change this template use File | Settings | File Templates.
 */

namespace classes\support\repository;


use classes\character\data\Character;
use classes\core\data\DataModel;
use classes\core\repository\AbstractRepository;
use classes\core\repository\RepositoryManager;
use classes\support\data\Supporter;

class SupporterRepository extends AbstractRepository
{
    private $SupporterGroupId = 1738;

    public function __construct()
    {
        parent::__construct('classes\support\data\Supporter');
    }

    public function save(DataModel $item)
    {
        $result = parent::save($item);
        if ($result) {
            $userGroup = 0;
            if (strtotime($item->ExpiresOn) > date('U')) {
                $userGroup = 61;
            }
            $query = "UPDATE phpbb_users SET user_rank = :rank WHERE user_id = :id";
            $this->query($query)->bind('rank', $userGroup)->bind('id', $item->UserId)->execute();

        }
        return $result;
    }

    /**
     * @return array
     */
    public function ListCurrentSupporters()
    {
        $sql = <<<EOQ
SELECT
    S.id,
    S.expires_on,
    S.amount_paid,
    S.number_of_characters,
    U.username,
    U.user_id
FROM
    supporters AS S
    LEFT JOIN phpbb_users AS U ON S.user_id = U.user_id
WHERE
    S.expires_on > NOW()
ORDER BY
    U.username_clean
EOQ;

        return $this->query($sql)->all();
    }

    /**
     * @param $userId
     * @return bool
     */
    public function CheckIsCurrentSupporter($userId)
    {
        $sql = <<<EOQ
SELECT
    user_id
FROM
    supporters
WHERE
    user_id = :id
    AND expires_on > :date
EOQ;

        $result = $this->query($sql)
            ->bind('id', $userId)
            ->bind('date', date('Y-m-d H:i:s'))
            ->single();

        return $result !== false;
    }

    /**
     * @param $userId
     * @param $characterIds
     */
    public function UpdateCharactersForSupporter($userId, $characterIds)
    {
        $supporter = $this->FindByUserId($userId);
        /* @var Supporter $supporter */

        $sql = <<<EOQ
DELETE FROM supporter_characters WHERE supporter_id = :id
EOQ;
        $this->query($sql)->bind('id', $supporter->Id)->execute();

        foreach ($characterIds as $characterId) {
            $sql = <<<EOQ
INSERT INTO supporter_characters (supporter_id, character_id) VALUES (?, ?);
EOQ;

            $this->query($sql)->bind(1, $supporter->Id)->bind(2, $characterId)->execute();
        }
    }

    /**
     * @param $supporterId
     * @return Character[]
     */
    public function ListSelectedCharactersForSupporter($supporterId)
    {
        $characterRepository = RepositoryManager::GetRepository('classes\character\data\Character');
        return $characterRepository->ListSelectedCharactersForSupporter($supporterId);
    }

    public function ListSupporters($onlyActive)
    {
        if($onlyActive) {
            $sql = <<<EOQ
SELECT
    S.id,
    S.expires_on,
    S.amount_paid,
    S.number_of_characters,
    S.characters_awarded,
    S.updated_on,
    U.username,
    U.user_id,
    UB.username as updated_by_username
FROM
    supporters AS S
    LEFT JOIN phpbb_users AS U ON S.user_id = U.user_id
    LEFT JOIN phpbb_users AS UB ON S.updated_by_id = UB.user_id
WHERE
    S.expires_on > NOW()
ORDER BY
    U.username_clean
EOQ;
        }
        else {
            $sql = <<<EOQ
SELECT
    S.id,
    S.expires_on,
    S.amount_paid,
    S.number_of_characters,
    S.characters_awarded,
    S.updated_on,
    U.username,
    U.user_id,
    UB.username as updated_by_username
FROM
    supporters AS S
    LEFT JOIN phpbb_users AS U ON S.user_id = U.user_id
    LEFT JOIN phpbb_users AS UB ON S.updated_by_id = UB.user_id
ORDER BY
    U.username_clean
EOQ;
        }
        return $this->query($sql)->all();
    }

    public function ClearAwardedCharacters()
    {
        $sql = <<<EOQ
UPDATE
    supporters
SET
    characters_awarded = 0
EOQ;

        $this->query($sql)->execute();
    }

    public function ListUsersTwoWeeksFromExpiring()
    {
        $targetDate = date('Y-m-d', strtotime('+14 days'));

        $sql = <<<EOQ
SELECT
    username,
    user_email,
    expires_on
FROM
    supporters AS S
    INNER JOIN phpbb_users AS U ON S.user_id = U.user_id
WHERE
    S.expires_on = ?
EOQ;

        return $this->query($sql)->all(array($targetDate));
    }

    public function RemoveSupporterStatusFromExpiredSupporters()
    {
        $sql = <<<EOQ
DELETE FROM
    phpbb_user_group
WHERE
    group_id = ?
    AND user_id IN (
        SELECT
            user_id
        FROM
            supporters
        WHERE
            expires_on < ?
    )
EOQ;

        return $this->query($sql)->execute(array($this->SupporterGroupId, date('Y-m-d')));
    }

    public function GrantedSupporterStatus($userId)
    {
        $sql = <<<EOQ
INSERT INTO
    phpbb_user_group
    (
        group_id,
        user_id,
        group_leader,
        user_pending
    )
VALUES (?, ?, 0, 1)
EOQ;

        return $this->query($sql)->execute(array($this->SupporterGroupId, $userId));
    }
}
