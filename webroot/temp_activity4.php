<?php
use classes\character\data\CharacterStatus;
use classes\core\repository\Database;

include 'cgi-bin/start_of_page.php';

$startDate = date('Y-m-d', strtotime('+1 days'));

$i = 0;
$multiplier = 7;
$statuses = implode(',', CharacterStatus::Sanctioned);
while($i < 7)
{
	$endDate = $startDate;
	$dayAdjust = $multiplier + ($i * $multiplier) - 1;
	$startDate = date('Y-m-d', strtotime("-$dayAdjust days"));
	echo "Start: $startDate : End: $endDate<br>";
	$i++;
	
	$query = <<<EOQ
SELECT
	C.character_type,
	COUNT(CL.id) AS logins,
	(COUNT(CL.id) / 
		(SELECT
			COUNT(*)
		FROM
			character_logins AS CL
			LEFT JOIN characters AS C ON CL.character_id = C.id
		WHERE
            C.character_status_id IN ($statuses) 
			AND C.is_npc = 'N'
			AND C.city = 'Portland'
			AND CL.login_time >= '$startDate'
			AND CL.login_time < '$endDate'
		)
	) AS percentage
FROM
	character_logins AS CL
	LEFT JOIN characters AS C ON CL.character_id = C.id
WHERE
    C.character_status_id IN ($statuses) 
	AND C.is_npc = 'N'
	AND C.city = 'Portland'
	AND CL.login_time >= '$startDate'
	AND CL.login_time < '$endDate'
GROUP BY
	C.character_type
ORDER BY 
	logins DESC
EOQ;
	foreach(Database::getInstance()->query($query)->all() as $detail) {
		echo "$detail[character_type] : $detail[logins] : " . $detail['percentage'] * 100 . "%<br>";
	}

	echo "<br /><br />";
}
