<?
/*UserGroupID
- Array with each user group
  - Key GroupID
  - Value Array
    - Key of Array Entry is column name
    - Value of Array Value is database Value
  

Example: 
Boston Apostate
GroupID: 234
Array("Character_Type"=>"Mage", "Splat2"=>"Apostate")*/

include 'cgi-bin/dbconnect.php';

$master_array = array();

// set up all of the groups
//Boston Apostate
$group_array = array("character_type"=>"Mage", "City"=>"Boston", "splat2"=>"Apostate");
$master_array[234] = $group_array;
$group_array = array("character_type"=>"Mage", "City"=>"Boston", "splat2"=>"The Adamantine Arrows");
$master_array[171] = $group_array;
$group_array = array("character_type"=>"Mage", "City"=>"Boston", "splat2"=>"Free Council");
$master_array[172] = $group_array;
$group_array = array("City"=>"Boston");
$master_array[167] = $group_array;
$group_array = array("character_type"=>"Mage", "City"=>"Boston", "splat2"=>"Guardians of the Veil");
$master_array[173] = $group_array;
$group_array = array("character_type"=>"Mage", "City"=>"Boston", "splat2"=>"The Mysterium");
$master_array[174] = $group_array;
$group_array = array("character_type"=>"Mage", "City"=>"Boston", "splat2"=>"The Silver Ladder");
$master_array[175] = $group_array;
$group_array = array("City"=>"Boston");
$master_array[201] = $group_array;
$group_array = array("City"=>"Denver");
$master_array[165] = $group_array;
$group_array = array("City"=>"Denver");
$master_array[200] = $group_array;
$group_array = array("City"=>"Denver", "Character_Type"=>"Werewolf");
$master_array[166] = $group_array;
$group_array = array("character_type"=>"Vampire", "City"=>"New Orleans", "splat2"=>"Carthian");
$master_array[160] = $group_array;
$group_array = array("character_type"=>"Vampire", "City"=>"New Orleans", "splat2"=>"Circle of the Crone");
$master_array[161] = $group_array;
$group_array = array("City"=>"New Orleans");
$master_array[157] = $group_array;
$group_array = array("character_type"=>"Vampire", "City"=>"New Orleans", "splat2"=>"Invictus");
$master_array[162] = $group_array;
$group_array = array("character_type"=>"Vampire", "City"=>"New Orleans", "splat2"=>"Lancea Sanctum");
$master_array[163] = $group_array;
$group_array = array("character_type"=>"Vampire", "City"=>"New Orleans", "splat2"=>"Ordo Dracul");
$master_array[164] = $group_array;
$group_array = array("character_type"=>"Vampire", "City"=>"New Orleans", "splat2"=>"Unaligned");
$master_array[233] = $group_array;
$group_array = array("City"=>"New Orleans");
$master_array[199] = $group_array;
$group_array = array("character_type"=>"Mage", "City"=>"Seattle", "splat2"=>"Apostate");
$master_array[237] = $group_array;
$group_array = array("character_type"=>"Mage", "City"=>"Seattle", "splat2"=>"The Adamantine Arrows");
$master_array[188] = $group_array;
$group_array = array("character_type"=>"Mage", "City"=>"Seattle", "splat2"=>"Free Council");
$master_array[189] = $group_array;
$group_array = array("character_type"=>"Mage", "City"=>"Seattle", "splat2"=>"Guardians of the Veil");
$master_array[190] = $group_array;
$group_array = array("character_type"=>"Mage", "City"=>"Seattle", "splat2"=>"The Mysterium");
$master_array[191] = $group_array;
$group_array = array("character_type"=>"Mage", "City"=>"Seattle", "splat2"=>"The Silver Ladder");
$master_array[192] = $group_array;
$group_array = array("character_type"=>"Vampire", "City"=>"Seattle", "splat2"=>"Carthian");
$master_array[181] = $group_array;
$group_array = array("character_type"=>"Vampire", "City"=>"Seattle", "splat2"=>"Circle of the Crone");
$master_array[182] = $group_array;
$group_array = array("character_type"=>"Vampire", "City"=>"Seattle", "splat2"=>"Invictus");
$master_array[183] = $group_array;
$group_array = array("character_type"=>"Vampire", "City"=>"Seattle", "splat2"=>"Lancea Sanctum");
$master_array[184] = $group_array;
$group_array = array("character_type"=>"Vampire", "City"=>"Seattle", "splat2"=>"Ordo Dracul");
$master_array[185] = $group_array;
$group_array = array("character_type"=>"Vampire", "City"=>"Seattle", "splat2"=>"Unaligned");
$master_array[236] = $group_array;
$group_array = array("character_type"=>"Werewolf", "City"=>"Seattle");
$master_array[186] = $group_array;
$group_array = array("City"=>"Seattle");
$master_array[176] = $group_array;
$group_array = array("City"=>"Seattle");
$master_array[202] = $group_array;

// get list of keys from the master array
$master_keys = array_keys($master_array);

while(list($key, $value) = each($master_keys))
{
  // cycle through each group
  //echo "$value<br>";
  
  // build SQL
  $character_query = "select primary_login_id from characters where is_sanctioned='Y' and is_npc='N' and is_deleted='n'";
  while(list($column_name, $column_value) = each($master_array[$value]))
  {
    //echo "$column_name: $column_value<br>";
    $character_query .= " and $column_name='$column_value' ";
  }
  
  echo $character_query."<br>";
  
  $character_result = mysql_query($character_query) or die(mysql_error());
  
  // build list of user ids that matched.
  $login_ids = "";
  while($character = mysql_fetch_array($character_result, MYSQL_ASSOC))
  {
    $login_ids .= "$character[primary_login_id],";
  }
  $login_ids = substr($login_ids, 0, strlen($login_ids)-1);
  
  //echo $login_ids."<br>";
  
  // remove those that don't match from the phpbb_user_group table
  $delete_query = "delete from phpbb_user_group where group_id = $value and user_id not in ($login_ids);";
  echo "$delete_query<br><br>";
  mysql_query($delete_query) or die(mysql_error());
}

?>