<?
include 'cgi-bin/dbconnect.php';

$query = "select City, Character_Type, count(character_id) as Num_of_Characters from characters where is_sanctioned='y' and is_npc='n' and is_deleted='n' group by city, character_type";
$result = mysql_query($query) or die(mysql_error());

while($detail = mysql_fetch_array($result))
{
  echo "$detail[City] : $detail[Character_Type] : $detail[Num_of_Characters]<br>";
}
?>