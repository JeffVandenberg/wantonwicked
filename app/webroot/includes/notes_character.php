<?php
/* @var array $userdata */

// grab passed variables
use classes\core\helpers\MenuHelper;
use classes\core\helpers\Request;

$characterId = Request::GetValue('character_id', 0);

$log_npc = isset($_POST['log_npc']) ? $_POST['log_npc'] : "n";
$log_npc = isset($_GET['log_npc']) ? $_GET['log_npc'] : $log_npc;

// includes for sortable forms
include 'cgi-bin/js_doSort.php';
include 'cgi-bin/buildSortForm.php';

// query used depends on what we're looking for
if(($userdata['is_asst'] || $userdata['is_gm'] || $userdata['is_head'] || $userdata['is_admin']) && ($log_npc == 'y'))
{
	$character_query = <<<EOQ
SELECT C.*, Character_Name, l.Name
FROM (characters AS C INNER JOIN login_character_index as lci ON C.id = lci.character_id) INNER JOIN login as l on lci.login_id = l.id
WHERE C.id = $characterId
	AND is_npc = 'Y';
EOQ;
}
else
{
	$character_query = <<<EOQ
SELECT C.*, Character_Name, l.Name
FROM (characters AS C INNER JOIN login_character_index as lci ON C.id = lci.character_id) INNER JOIN login as l on lci.login_id = l.id
WHERE C.id = $characterId
	AND lci.login_id = $userdata[user_id]
EOQ;
}

$character_result = mysql_query($character_query) or die(mysql_error());
// check the person is looking at a valid character
if(mysql_num_rows($character_result))
{
	$character_detail = mysql_fetch_array($character_result, MYSQL_ASSOC);
	$page_title = "Notes for $character_detail[Character_Name]";
	$contentHeader = $page_title;

	// variables for form
	$notes_list = "";
	$this_order_by = "update_date";
	$last_order_by = "";
	$order_by = "update_date";
	$order_dir = "desc";
	
	// test if deleting
	if(isset($_POST['action']))
	{
		if($_POST['action'] == 'update')
		{
			$note_list = $_POST['delete'];
			while(list($key, $value) = each($note_list))
			{
				//echo "delete: $key: $value<br>";
				// delete note from system
				$delete_query = "update personal_notes set is_deleted='Y' where personal_note_id=$value;";
				//echo "$delete_query<br>";
				$delete_result = mysql_query($delete_query) or die(mysql_error());
			}
		}
		if($_POST['action'] == 'sort')
		{
		  $this_order_by = $_POST['this_order_by'];
		  $last_order_by = $_POST['last_order_by'];
		  if(($_POST['this_order_by'] == $_POST['last_order_by']) && $_POST['this_order_dir'] == 'desc')
		  {
		    $order_dir = "asc";
		  }
		}
		$order_by = "$this_order_by $order_dir, personal_note_id";
	}
	

	
	// query database
	$note_query = "select * from personal_notes where character_id = $characterId and is_deleted='n' order by $order_by;";
	//echo $note_query."<br>";
	$note_result = mysql_query($note_query) or die(mysql_error());

    require_once('helpers/character_menu.php');
    /* @var array $characterMenu */
    $menu = MenuHelper::GenerateMenu($characterMenu);

	// build list
	$notes_list = <<<EOQ
$menu
<div align="center">
<a href="$_SERVER[PHP_SELF]?action=view&character_id=$characterId&log_npc=$log_npc" onClick="window.open('$_SERVER[PHP_SELF]?action=view&character_id=$characterId&log_npc=$log_npc', 'AddChracterNote$userdata[user_id]', 'width=535,height=335,resizable,scrollbars');return false;">Add Note</a>
&nbsp;&nbsp;&nbsp;&nbsp;
<a href="#" onClick="submitForm();return false;">Delete Notes</a>
<br>
<form name="note_list" id="note_list" method="post" action="$_SERVER[PHP_SELF]?action=character&character_id=$characterId&log_npc=$log_npc">
<table>
  <tr>
    <th>&nbsp</th>
    <th><a href="javascript:doSort('is_favorite')">Favorite</a></th>
    <th><a href="javascript:doSort('title')">Title</a></th>
    <th><a href="javascript:doSort('create_date')">Create Date</a></th>
    <th><a href="javascript:doSort('update_date')">Update Date</a></th>
  </tr>
EOQ;

  $row = 0;
  while ($note_detail = mysql_fetch_array($note_result, MYSQL_ASSOC))
  {
	  $notes_list .= <<<EOQ
	<tr>
    <td>
	    <input type="checkbox" name='delete[]' id='delete[]' value="$note_detail[Personal_Note_ID]">
	  </td>
	  <td align="center">
	  	$note_detail[Is_Favorite]
	  </td>
	  <td>
	    <a href="$_SERVER[PHP_SELF]?action=view&character_id=$characterId&log_npc=$log_npc&personal_note_id=$note_detail[Personal_Note_ID]" onClick="window.open('$_SERVER[PHP_SELF]?action=view&character_id=$characterId&log_npc=$log_npc&personal_note_id=$note_detail[Personal_Note_ID]', 'ViewCharacterNote$note_detail[Personal_Note_ID]', 'width=535,height=335,resizable,scrollbars');return false;">$note_detail[Title]</a>
	  </td>
	  <td>
	    $note_detail[Create_Date]
	  </td>
	  <td>
	    $note_detail[Update_Date]
	  </td>
	</tr>
EOQ;
  }
  
  // close form
  $notes_list .= <<<EOQ
</table>
<input type="hidden" name="action" id="action" value="update">
</form>
</div>
EOQ;

  // add js for the page
  $java_script .= <<<EOQ
<script language="JavaScript">
function submitForm ( )
{
	window.document.note_list.submit();
}
</script>
EOQ;

  $sort_form = buildSortForm($this_order_by, $order_dir, $last_order_by, "$_SERVER[PHP_SELF]?action=character&character_id=$characterId&log_npc=$log_npc");
  
  $page_content = $notes_list . $sort_form;
}