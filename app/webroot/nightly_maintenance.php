<?php
use classes\core\repository\Database;
use classes\core\repository\RepositoryManager;
use classes\log\data\ActionType;
use classes\support\SupportManager;

ini_set('display_errors', 1);

include 'cgi-bin/start_of_page.php';

$enrollmentManager = new SupportManager();
if (date('d') == 1) {
    $enrollmentManager->AwardBonusXP();
}
$enrollmentManager->SendReminderEmails();
$enrollmentManager->ExpireSupporterStatus();

$db = new Database();

if (date('D') == 'Fri') {
    $update_experience_query = "update characters set current_experience = current_experience + 3, total_experience = total_experience + 3 where is_sanctioned='Y';";
    $db->Query($update_experience_query)->Execute();
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
    'Weekly XP Award: 3',
    NOW()
FROM
    characters
WHERE
    is_sanctioned = 'Y'
EOQ;
    $db->Query($xpLogQuery)->Execute(array(ActionType::XPModification));
}
$update_willpower_query = "update characters set willpower_temp = willpower_temp + 1 where willpower_temp < willpower_perm;";
$db->Query($update_willpower_query)->Execute();

// unsanction characters more than 1 month inactive
$month_ago = date('Y-m-d', mktime(0, 0, 0, date('m') - 1, date('d'), date('Y')));

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
    is_sanctioned = 'Y'
    AND is_npc = 'N'
    AND id NOT IN (
        SELECT
            DISTINCT
            character_id
        FROM
            log_characters
        WHERE
            created >= ?
            AND action_type_id IN (?, ?)
    )
EOQ;
$params             = array(ActionType::Desanctioned, $month_ago, ActionType::Login, ActionType::Sanctioned);
$db->Query($unsanctionLogQuery)->Execute($params);

$unsanc_query = <<<EOQ
UPDATE
    characters
SET
    is_sanctioned='n'
WHERE
    is_sanctioned='y'
    AND is_npc='n'
    AND id NOT IN (
        SELECT
            DISTINCT
            character_id
        FROM
            log_characters
        WHERE
            created >= ?
            AND action_type_id IN (?, ?)
    )
EOQ;

$params             = array($month_ago, ActionType::Login, ActionType::Sanctioned);
$desancedCharacters = $db->Query($unsanc_query)->Execute($params);

$now     = date("Y-m-d H:i:s");
$message = <<<EOQ
maintence completed on: $now
Desanctioned Characters: $desancedCharacters
EOQ;
mail('jeffvandenberg@gmail.com', 'WaW Maintance', $message);