<?php
/**
 * Created by JetBrains PhpStorm.
 * User: JeffVandenberg
 * Date: 7/14/13
 * Time: 12:21 AM
 * To change this template use File | Settings | File Templates.
 */
namespace classes\log;

class CharacterLog {
    public static function LogAction($characterId, $actionTypeId, $note, $userId = null, $referenceId = null)
    {
        $note = mysql_real_escape_string($note);
        $userId = ($userId != null) ? (int) $userId : 'NULL';
        $referenceId = ($referenceId != null) ? (int) $referenceId : 'NULL';

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
        $characterId,
        $actionTypeId,
        '$note',
        $referenceId,
        $userId,
        NOW()
    )
EOQ;
        ExecuteNonQuery($sql);
    }
}