<?php
use classes\core\helpers\Request;

$contentHeader = "Delete Character";
$page_title = "Delete Character Confirmation";

// get character id
$character_id = Request::GetValue('character_id', 0);

// get character information
$characterQuery = <<<EOQ
SELECT
    wod.Character_ID,
    Character_Name
FROM
    wod_characters as wod
    INNER JOIN login_character_index as lci
        ON wod.character_id = lci.character_id
WHERE
    lci.login_id = $userdata[user_id]
    AND wod.is_deleted = 'N'
    AND wod.character_id = $character_id;
EOQ;
$character = ExecuteQueryItem($characterQuery);

if ($character) {
    $page_content = <<<EOQ
Are you sure you want to delete $character[Character_Name]? If so, click the confirm button, otherwise click, Go Back.<br>
<br>
<br>
Think about it.. hard..<br>
<br>
<br>
<form method="post" action="$_SERVER[PHP_SELF]?action=delete_confirmed" onsubmit="return confirm('Are you REALLY sure about this and this is not just the result of having a bad day?');">
  <input type="hidden" name="character_id" value="$character_id">
  <input type="submit" value="Delete $character[Character_Name]">
</form>
<br />
<br />
<form method="post" action="$_SERVER[PHP_SELF]">
  <input type="submit" value="Go Back">
</form>
EOQ;
} else {
    // didn't find the PC
    $page_content = "Unable to find Character to Delete.";
}
