<?php
use classes\character\nwod2\BeatService;
use classes\character\repository\CharacterRepository;
use classes\core\repository\Database;
use classes\log\data\ActionType;


include __DIR__ . '/../vendor/autoload.php';

$db = new Database();
$beatService = new BeatService();

/* start of month tasks */
if (date('j') == 1) {
    // bulk award 2 xp
    $update_experience_query = "UPDATE characters SET current_experience = current_experience + 2, total_experience = total_experience + 2, bonus_received = 0 WHERE is_sanctioned='Y';";
    $db->query($update_experience_query)->execute();
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
    'Monthly XP Award: 2',
    NOW()
FROM
    characters
WHERE
    is_sanctioned = 'Y'
EOQ;
    $db->query($xpLogQuery)->execute(array(ActionType::XPModification));

    $beatService->awardOutstandingBeats();
}
$update_willpower_query = "UPDATE characters SET willpower_temp = willpower_temp + 1 WHERE willpower_temp < willpower_perm;";
$db->query($update_willpower_query)->execute();

// unsanction characters more than 1 month inactive
$month_ago = date('Y-m-d', mktime(0, 0, 0, date('m') - 1, date('d'), date('Y')));
$characterRepository = new CharacterRepository();

//$desancedCharacters = $characterRepository->UnsanctionInactiveCharacters($month_ago);
$desancedCharacters = 0;

$now = date("Y-m-d H:i:s");
$message = <<<EOQ
maintence completed on: $now
Desanctioned Characters: $desancedCharacters
EOQ;
mail('jeffvandenberg@gmail.com', 'WaW Maintance', $message);
