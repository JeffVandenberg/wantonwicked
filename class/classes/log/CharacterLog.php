<?php
/**
 * Created by JetBrains PhpStorm.
 * User: JeffVandenberg
 * Date: 7/14/13
 * Time: 12:21 AM
 * To change this template use File | Settings | File Templates.
 */
namespace classes\log;

use classes\core\repository\Database;

class CharacterLog
{
    public static function LogAction($characterId, $actionTypeId, $note, $userId = null, $referenceId = null)
    {
        $sql = <<<EOQ
INSERT INTO
    log_characters
    (
        character_id,
        action_type_id,
        note,
        reference_id,
        created_by_id,
        created
    )
VALUES
    (
        ?,
        ?,
        ?,
        ?,
        ?,
        NOW()
    )
EOQ;
        $params = array(
            $characterId,
            $actionTypeId,
            $note,
            $referenceId,
            $userId
        );
        Database::getInstance()->query($sql)->execute($params);
    }
}
