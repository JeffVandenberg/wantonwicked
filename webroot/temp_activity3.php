<?php
use classes\character\data\CharacterStatus;
use classes\core\repository\Database;

include 'cgi-bin/start_of_page.php';

$statuses = implode(',', CharacterStatus::Sanctioned);
$query = <<<EOQ
select 
	City, 
	Character_Type, 
	Splat2, 
	count(id) as Num_of_Characters 
from 
  characters AS C
WHERE 
  C.character_status_id IN ($statuses) 
  AND is_npc='n' 
group by 
	city, 
	character_type, 
	Splat2
EOQ;
foreach (Database::getInstance()->query($query)->all() as $detail) {
    echo "$detail[City] : $detail[Character_Type] : $detail[Splat2] : $detail[Num_of_Characters]<br>";
}
