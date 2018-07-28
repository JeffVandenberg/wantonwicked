<?php
use classes\character\repository\CharacterRepository;
use classes\core\helpers\Request;
use classes\core\helpers\Response;
use classes\core\repository\Database;

/* @var array $userdata */

// grab passed variables
$character_id = Request::getValue('character_id', 0);
$log_npc = Request::getValue('log_npc', 'n');
$personal_note_id = Request::getValue('personal_note_id', 0);

// set up page variables
$title = "";
$body = "";
$is_favorite = "N";
$character_result = "";

// test to see if may look up the note
$may_view_note = false;

// test to see if a character id has been passed
// if not assume it's a personal rather than character note
if (!$character_id) {
    Response::redirect('/notes.php', 'No Character Specified');
}

$characterRepository = new CharacterRepository();
$character = $characterRepository->findById($character_id);

if (!$character) {
    Response::redirect('/notes.php', 'Unable to find character');
}

// page variables
$error = "";
$note = "";

// we can process the result
if ((isset($_POST['action'])) && (!empty($_POST['body']))) {
    // add note
    $now = date('Y-m-d h:i:s');
    $title = (!empty($_POST['title'])) ? htmlspecialchars($_POST['title']) : "Untitled : $now";
    $body = htmlspecialchars($_POST['body']);
    $is_favorite = (isset($_POST['is_favorite'])) ? "Y" : "N";

    if ($personal_note_id == 0) {
        // insert note
        $insert_query = <<<SQL
INSERT INTO 
  personal_notes 
  (Login_ID, Character_ID, Is_Deleted, Is_Favorite, Title, Body, Create_Date, Update_Date) 
VALUES (?, ?, ?, ?, ?, ?, ?, ?);
SQL;
        Database::getInstance()->query($insert_query)->execute([
            $userdata['user_id'], $character_id, 'N', '$is_favorite', '$title', '$body', '$now', '$now'
        ]);
    } else {
        // update note
        $update_query = <<<SQL
UPDATE 
  personal_notes 
SET 
  is_favorite = ?,
  title = ?,
  body= ?,
  update_date = ?
WHERE 
  personal_note_id = ?;
SQL;
        Database::getInstance()->query($update_query)->execute([
            $is_favorite, $title, $body, $now, $personal_note_id
        ]);
    }

    // update_previous page
    $java_script .= <<<EOQ
<script language="JavaScript">
	window.opener.location.reload(true);
</script>
EOQ;
}

// check if we are looking up a note
if (!(isset($_POST['action'])) && ($personal_note_id)) {
    $note_query = "SELECT * FROM personal_notes WHERE personal_note_id=?;";
    $note_detail = Database::getInstance()->query($note_query)->single([$personal_note_id]);

    if ($note_detail) {
        $title = $note_detail['Title'];
        $body = $note_detail['Body'];
        $is_favorite = $note_detail['Is_Favorite'];
    } else {
        // did not find a note, set personal_note_id = 0 to indicate an insert rather than update
        $personal_note_id = 0;
    }
}

// render the note
// set title
if ($character) {
    $character_detail = $character;
    $page_title = "Character Note for: $character_detail[character_name]";
} else {
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
