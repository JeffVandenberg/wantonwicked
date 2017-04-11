<?php
use classes\core\repository\Database;

$sql = <<<EOQ
SELECT
	id,
	character_name
FROM
	characters AS C
WHERE
	C.is_sanctioned = 'Y'
	AND C.is_deleted = 'N'
	AND C.character_type = 'Vampire'
	AND C.city = 'Savannah'
ORDER BY
	character_name
EOQ;

$characters = Database::getInstance()->query($sql)->all();

$characterList = "";
$abp = new ABP();

foreach ($characters as $detail) {
    $abp->UpdateABP($detail['id']);

    $characterList .= <<<EOQ
Updated: <a href="http://www.wantonwicked.net/view_sheet.php?action=st_view_xp&view_character_id=$detail[id]">$detail[character_name]</a><br />
EOQ;
}

$page_title = "Recalculate ABP";
$page_content = <<<EOQ
<a href="/abp.php">Return to ABP Home</a><br /><br />
The Following Characters have been updated:<br />
$characterList
EOQ;
