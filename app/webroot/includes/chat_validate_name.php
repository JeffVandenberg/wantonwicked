<?
$character_name = htmlspecialchars($_GET['character_name']);

$query = "select Character_ID from wod_characters where character_name = '$character_name' and is_deleted='n';";
$result = mysql_query($query) or die(mysql_error());

if(mysql_num_rows($result))
{
  die("valid");
}
else
{
  die("invalid");
}
?>