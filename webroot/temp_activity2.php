<?php
use classes\core\repository\Database;

include 'cgi-bin/start_of_page.php';

$statuses = implode(',', CharacterStatus::Sanctioned);
$query = <<<SQL
SELECT 
  City, 
  Character_Type, 
  Splat1, 
  count(id) AS Num_of_Characters 
FROM 
  characters AS C
WHERE 
  C.character_status_id IN ($statuses) 
  AND is_npc='n' 
GROUP BY 
  city, 
  character_type, 
  Splat1
SQL;

foreach (Database::getInstance()->query($query)->all() as $detail) {
    echo "$detail[City] : $detail[Character_Type] : $detail[Splat1] : $detail[Num_of_Characters]<br>";
}
