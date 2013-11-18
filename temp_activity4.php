<?
include 'cgi-bin/dbconnect.php';

$startDate = date('Y-m-d', strtotime('+1 days'));

$i = 0;
$multiplier = 7;
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
			LEFT JOIN wod_characters AS C ON CL.character_id = C.character_id
		WHERE
			C.is_sanctioned = 'Y'
			AND C.is_npc = 'N'
			AND C.is_deleted = 'N'
			AND C.city = 'San Diego'
			AND CL.login_time >= '$startDate'
			AND CL.login_time < '$endDate'
		)
	) AS percentage
FROM
	character_logins AS CL
	LEFT JOIN wod_characters AS C ON CL.character_id = C.character_id
WHERE
	C.is_sanctioned = 'Y'
	AND C.is_npc = 'N'
	AND C.is_deleted = 'N'
	AND C.city = 'San Diego'
	AND CL.login_time >= '$startDate'
	AND CL.login_time < '$endDate'
GROUP BY
	C.character_type
ORDER BY 
	logins DESC
EOQ;
	$result = mysql_query($query) or die(mysql_error());

	while($detail = mysql_fetch_array($result))
	{
		echo "$detail[character_type] : $detail[logins] : " . $detail['percentage'] * 100 . "%<br>";
	}

	echo "<br /><br />";
}


