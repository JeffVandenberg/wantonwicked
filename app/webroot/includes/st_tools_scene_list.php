<?
$page_title = "Character Scene List";

include 'cgi-bin/js_doSort.php';
include 'cgi-bin/buildSortForm.php';

// variables for form
$character_list = "";
$this_order_by = "last_scene_date";
$last_order_by = "";
$order_by = "last_scene_date, character_name";
$order_dir = "asc";

// test if deleting
if(isset($_POST['action']))
{
	if($_POST['action'] == 'sort')
	{
	  $this_order_by = $_POST['this_order_by'];
	  $last_order_by = $_POST['last_order_by'];
	  if(($_POST['this_order_by'] == $_POST['last_order_by']) && $_POST['this_order_dir'] == 'desc')
	  {
	    $order_dir = "asc";
	  }
	}
	$order_by = "$this_order_by $order_dir, character_name";
	
	if($_POST['action'] == 'update')
	{
  	$character_ids = $_POST['character_ids'];
  	if(!empty($character_ids))
  	{
    	$description = htmlspecialchars($_POST['scene_description']);
    	
    	$update_list = "";
    	$insert_list = "";
    	//$now = date('Y-m-d H:i:s');
      $server_hour = date("H") + $timezone_adjustment;
      $now = date("Y,m,d,$server_hour,i,s");
    	
    	while(list($key, $character_id) = each($character_ids))
    	{
      	$character_id += 0;
      	$update_list .= "character_id = $character_id OR ";
      	$insert_list .= "(null, $userdata[user_id], $character_id, '$now', '$description', '', ''),";
    	}
    	
    	$update_list = substr($update_list, 0, strlen($update_list) - 4);
    	$insert_list = substr($insert_list, 0, strlen($insert_list) - 1);
    	
    	$update_query = "update characters set last_scene_date = '$now' where $update_list;";
    	$insert_query = "insert into scene_record values $insert_list;";
    	
    	//echo "$update_query<br>$insert_query<br>";
    	$update_result = mysql_query($update_query) || die(mysql_error());
    	$insert_result = mysql_query($insert_query) || die(mysql_error());
  	}
	}
}

$cities = Array("Seattle", "Boston", "New Orleans", "Denver");

if(($userdata['is_asst']) && (in_array($userdata['city'], $cities)))
{
  $character_query = <<<EOQ
SELECT
  wc.id, 
  wc.Character_Name,
  wc.City,
  s1.Scene_Date, 
  s1.Scene_Description 
FROM 
characters wc
  LEFT JOIN scene_record s1 
    ON wc.character_id = s1.character_id 
  LEFT JOIN scene_record s2 
    ON s1.character_id = s2.character_id 
      AND s1.scene_date < s2.scene_date 
WHERE 
  wc.is_deleted = 'N' 
  AND wc.city='$userdata[city]'
  AND wc.is_sanctioned='Y' 
  AND wc.is_npc = 'N' 
  AND s2.scene_date is null 
ORDER BY 
  $order_by;  
EOQ;
}
else
{
  $character_query = <<<EOQ
SELECT
  wc.id, 
  wc.Character_Name,
  wc.City,
  s1.Scene_Date, 
  s1.Scene_Description 
FROM 
characters wc
  LEFT JOIN scene_record s1 
    ON wc.character_id = s1.character_id 
  LEFT JOIN scene_record s2 
    ON s1.character_id = s2.character_id 
      AND s1.scene_date < s2.scene_date 
WHERE 
  wc.is_deleted = 'N' 
  AND wc.is_sanctioned='Y' 
  AND wc.is_npc = 'N' 
  AND s2.scene_date is null 
ORDER BY 
  $order_by;  
EOQ;
}

$character_result = mysql_query($character_query) || die(mysql_error());

$character_list = <<<EOQ
When updating characters, please also include a very brief description of the scene, 
for example: Awakening, Dog with bone in the Quarter, combat scene, ghouls, etc.  Optionally
if it is a scene in a plot in the plot tracker you can also provide that as the description.<br>
<br>
<form method="post" action="$_SERVER[PHP_SELF]?action=scene_list">
<input type="hidden" name="action" value="update">
<div align="center">
Description of scene: <input type="text" size="35" maxlength="100" value="" name="scene_description">
<input type="submit" value="Update characters"><br>
<table border="0" cellpadding="2" cellspacing="2" class="normal_text">
  <tr bgcolor="#000000">
    <th>
    </th>
    <th>
      <a href="javascript:doSort('character_name')">Character Name</a>
    </th>
    <th>
      <a href="javascript:doSort('city')">City</a>
    </th>
    <th>
      <a href="javascript:doSort('last_scene_date')">Last Scene Date</a>
    </th>
    <th>
      Description
    </th>
  </tr>
EOQ;

$row = 0;
while($character_detail = mysql_fetch_array($character_result, MYSQL_ASSOC))
{
  $row_color = (($row++)%2) ? "#443f33" : "";
  
  $character_list .= <<<EOQ
  <tr bgcolor="$row_color">
    <td>
      <input type="checkbox" value="$character_detail[id]" name="character_ids[]">
    </td>
    <td>
      $character_detail[Character_Name]
    </td>
    <td>
      $character_detail[City]
    </td>
    <td>
      $character_detail[Scene_Date]
    </td>
    <td>
      $character_detail[Scene_Description]
    </td>
  </tr>
EOQ;
}

$character_list .= "</table></div></form>";

$sort_form = buildSortForm($this_order_by, $order_dir, $last_order_by, "$_SERVER[PHP_SELF]?action=scene_list");

$page_content .= $character_list . $sort_form;


?>