<?php
use classes\character\data\CharacterStatus;
use classes\core\repository\Database;

include 'cgi-bin/start_of_page.php';

$statuses = implode(',', CharacterStatus::Sanctioned);

$query = <<<SQL
SELECT 
  city, 
  character_type, 
  count(id) AS Num_of_Characters 
FROM 
  characters AS C
WHERE 
  C.character_status_id IN ($statuses) 
  AND is_npc='n' 
GROUP BY 
  city, 
  character_type
SQL;

foreach (Database::getInstance()->query($query)->all() as $detail) {
    echo "$detail[city] : $detail[character_type] : $detail[Num_of_Characters]<br>";
}
