<?php
use classes\core\repository\Database;

include 'cgi-bin/start_of_page.php';

$query = <<<EOQ
select 
	City, 
	Character_Type, 
	Splat2, 
	count(character_id) as Num_of_Characters 
from 
	characters
where 
	is_sanctioned='y' 
	and is_npc='n' 
	and is_deleted='n'
group by 
	city, 
	character_type, 
	Splat2
EOQ;
foreach(Database::getInstance()->query($query)->all() as $detail) {
  echo "$detail[City] : $detail[Character_Type] : $detail[Splat2] : $detail[Num_of_Characters]<br>";
}
