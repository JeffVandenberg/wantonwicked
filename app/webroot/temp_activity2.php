<?php
use classes\core\repository\Database;

include 'cgi-bin/start_of_page.php';

$query = "SELECT City, Character_Type, Splat1, count(character_id) AS Num_of_Characters FROM characters WHERE is_sanctioned='y' AND is_npc='n' AND is_deleted='n' GROUP BY city, character_type, Splat1";

foreach (Database::getInstance()->query($query)->all() as $detail) {
    echo "$detail[City] : $detail[Character_Type] : $detail[Splat1] : $detail[Num_of_Characters]<br>";
}
