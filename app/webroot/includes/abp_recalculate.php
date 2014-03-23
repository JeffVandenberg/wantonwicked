<?php
$sql = <<<EOQ
SELECT
	character_id,
	character_name
FROM
	characters AS C
WHERE
	C.is_sanctioned = 'Y'
	AND C.is_deleted = 'N'
	AND C.character_type = 'Vampire'
	AND C.city = 'San Diego'
ORDER BY
	character_name
EOQ;

$result = ExecuteQuery($sql);

$characterList = "";
$abp = new ABP();

while($detail = mysql_fetch_array($result, MYSQL_ASSOC))
{
	$abp->UpdateABP($detail['character_id']);
	
	$characterList .= <<<EOQ
Updated: <a href="http://www.wantonwicked.net/view_sheet.php?action=st_view_xp&view_character_id=$detail[character_id]">$detail[character_name]</a><br />
EOQ;
}

$page_title = "Recalculate ABP";
$page_content = <<<EOQ
<a href="/abp.php">Return to ABP Home</a><br /><br />
The Following Characters have been updated:<br />
$characterList
EOQ;
?>