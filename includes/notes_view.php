<?php
use classes\core\helpers\Request;

/* @var array $userdata */

// grab passed variables
$character_id = Request::GetValue('character_id', 0);
$log_npc = Request::GetValue('log_npc', 'n');
$personal_note_id = Request::GetValue('personal_note_id', 0);

// set up page variables
$title = "";
$body = "";
$is_favorite = "N";
$character_result = "";

// test to see if may look up the note
$may_view_note = false;

// test to see if a character id has been passed
// if not assume it's a personal rather than character note
if($character_id)
{
	if(($userdata['is_asst'] || $userdata['is_gm'] || $userdata['is_head'] || $userdata['is_admin']) && ($log_npc == 'y'))
	{
		$character_query = <<<EOQ
SELECT wod.*, Character_Name, l.Name
FROM (wod_characters as wod INNER JOIN login_character_index as lci ON wod.character_id = lci.character_id) INNER JOIN login as l on lci.login_id = l.id
WHERE wod.character_id = $character_id
	AND is_npc = 'Y';
EOQ;
	}
	else
	{
		$character_query = <<<EOQ
SELECT wod.*, Character_Name, l.Name
FROM (wod_characters as wod INNER JOIN login_character_index as lci ON wod.character_id = lci.character_id) INNER JOIN login as l on lci.login_id = l.id
WHERE wod.character_id = $character_id
	AND lci.login_id = $userdata[user_id]
EOQ;
	}
	
	$character_result = mysql_query($character_query) or die(mysql_error());
	
	if(mysql_num_rows($character_result))
	{
		$may_view_note = true;
	}
}

if(!$character_id)
{
	$may_view_note = true;
}

if($may_view_note)
{
	// page variables
	$error = "";
	$note = "";
	
	// we can process the result
	if((isset($_POST['action'])) && (!empty($_POST['body'])))
	{
		// add note
		$now = date('Y-m-d h:i:s');
		$title = (!empty($_POST['title'])) ? htmlspecialchars($_POST['title']) : "Untitled : $now";
		$body = mysql_real_escape_string(htmlspecialchars($_POST['body']));
		$is_favorite = (isset($_POST['is_favorite'])) ? "Y" : "N";
		
		if($personal_note_id == 0)
		{
			// insert note
			$personal_note_id = getNextID($connection, "personal_notes", "personal_note_id");
			$insert_query = "insert into personal_notes values ($personal_note_id, $userdata[user_id], $character_id, 'N', '$is_favorite', '$title', '$body', '$now', '$now', '', '', '', '', '');";
			$insert_result = mysql_query($insert_query) or die(mysql_error()); 
		}
		else
		{
			// update note
			$update_query = "update personal_notes set is_favorite='$is_favorite', title='$title', body='$body', update_date='$now' where personal_note_id = $personal_note_id;";
			$update_query = mysql_query($update_query) or die(mysql_error());
		}
		
		// update_previous page
		$java_script .= <<<EOQ
<script language="JavaScript">
	window.opener.location.reload(true);
</script>
EOQ;
	}
	
	// check if we are looking up a note
	if(!(isset($_POST['action'])) && ($personal_note_id))
	{
		$note_query = "select * from personal_notes where personal_note_id=$personal_note_id;";
		$note_result = mysql_query($note_query) or die(mysql_error());
		
		if(mysql_num_rows($note_result))
		{
			// found a note
			$note_detail = mysql_fetch_array($note_result, MYSQL_ASSOC);
			
			// test if looking for personal note, compare user_ids
			if((!$character_id) && ($note_detail['Login_ID'] != ($userdata['user_id'])))
			{
				$java_script .= <<<EOQ
<script language="JavaScript">
	window.close();
</script>
EOQ;
			}
			
			$title = $note_detail['Title'];
			$body = $note_detail['Body'];
			$is_favorite = $note_detail['Is_Favorite'];
		}
		else
		{
			// did not find a note, set personal_note_id = 0 to indicate an insert rather than update
			$personal_note_id = 0;
		}
	}
	
	// render the note
	// set title
	if($character_result != "")
	{
		$character_detail = mysql_fetch_array($character_result, MYSQL_ASSOC);
		$page_title = "Character Note for: $character_detail[Character_Name]";
	}
	else
	{
		$page_title = "Personal Note for: $userdata[user_name]";
	}
	$contentHeader = $page_title;
	
	
	// set body
	$is_favorite_checked = ($is_favorite == 'Y') ? "checked" : "";
	$body = stripslashes($body);
	$title = stripslashes($title);
	$note = <<<EOQ
<form method="post" action="$_SERVER[PHP_SELF]?action=view&character_id=$character_id&log_npc=$log_npc">
<table border="0" cellspacing="2" cellpadding="2" class="normal_text" width="100%">
	<tr>
		<td>
			Title:
			<input type="text" name="title" value="$title" size="30" maxlength="50">
		</td>
		<td>
			Is Favorite:
			<input type="checkbox" name="is_favorite" value="y" $is_favorite_checked>
		</td>
	</tr>
	<tr>
		<td colspan="2">
			<textarea name="body" rows="8" cols="60" style="width:100%">$body</textarea>
		</td>
	</tr>
	<tr>
		<td colspan="2" align="center">
			<input type="hidden" name="personal_note_id" value="$personal_note_id">
			<input type="hidden" name="character_id" value="$character_id">
			<input type="hidden" name="log_npc" value="$log_npc">
			<input type="submit" name="action" value="Update Note">
		</td>
	</tr>
</table>
</form>
EOQ;

	$page_content = $error . $note;
}

?>