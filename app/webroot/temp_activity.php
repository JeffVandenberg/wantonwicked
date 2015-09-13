<?php
use classes\core\repository\Database;

include 'cgi-bin/start_of_page.php';
$query = "select city, character_type, count(id) as Num_of_Characters from characters where is_sanctioned='y' and is_npc='n' and is_deleted='n' group by city, character_type";

foreach(Database::getInstance()->query($query)->all() as $detail) {
  echo "$detail[city] : $detail[character_type] : $detail[Num_of_Characters]<br>";
}
