<?php
use classes\core\repository\Database;
use classes\core\repository\RepositoryManager;
use classes\support\SupportManager;

ini_set('display_errors', 1);

include 'cgi-bin/start_of_page.php';
//include 'includes/classes/tenacy/abp/abp.php';
//include 'includes/classes/repository/territory_repository.php';

//$territory = new TerritoryRepository();
//$territory->UpdateAll();

//$abp = new ABP();
//$abp->UpdateAllABP();
//$abp->AdjustCurrentBlood();

$enrollmentManager = new SupportManager();
if(date('d') == 1) {
    $enrollmentManager->AwardBonusXP();
}
$enrollmentManager->SendReminderEmails();
$enrollmentManager->ExpireSupporterStatus();

$db = new Database();
$autoBpQuery = <<<EOQ
UPDATE 
	wod_characters
SET 
	Next_Power_Stat_Increase = DATE_ADD(Next_Power_Stat_Increase, INTERVAL 6 MONTH),
	Power_Stat = Power_Stat + 1
WHERE
	character_type = 'Vampire'
	AND is_sanctioned = 'Y'
	AND is_npc = 'N'
	AND city = 'San Diego'
	AND Next_Power_Stat_Increase < NOW();
EOQ;

$db->Query($autoBpQuery)->Execute();

if(date('D') == 'Fri')
{
	$update_experience_query = "update wod_characters set current_experience = current_experience + 3, total_experience = total_experience + 3 where is_sanctioned='Y';";
	$db->Query($update_experience_query)->Execute();
}
$update_willpower_query = "update wod_characters set willpower_temp = willpower_temp + 1 where willpower_temp < willpower_perm;";
$db->Query($update_willpower_query)->Execute();

// unsanction characters more than 1 month inactive
$month_ago = date('Y-m-d', mktime(0, 0, 0, date('m')-1, date('d'), date('Y')));

$unsanc_query = <<<EOQ
UPDATE
    wod_characters
SET
    is_sanctioned='n'
WHERE
    is_sanctioned='y'
    AND is_npc='n'
    AND character_id NOT IN (
        SELECT
            DISTINCT
            character_id
        FROM
            log_characters
        WHERE
            created >= :month
            AND action_type_id = :login
    )
EOQ;
//$desancedCharacters = $db->Query($unsanc_query)->Bind('month', $month_ago)->Bind('login', ActionType::Login)->Execute();

$now = date("Y-m-d H:i:s");
$message = <<<EOQ
maintence completed on: $now
Desanctioned Characters: $desancedCharacters
EOQ;
mail('jeffvandenberg@gmail.com', 'WaW Maintance', $message);