<?php
use classes\character\data\Character;
use classes\character\data\CharacterStatus;
use classes\character\repository\CharacterRepository;
use classes\core\helpers\Request;
use classes\core\helpers\Response;
use classes\request\data\RequestType;

$contentHeader = "Delete Character";
$page_title = "Delete Character Confirmation";

// get character id
$character_id = Request::getValue('character_id', 0);

// get character information
$characterRepository = new CharacterRepository();
$character = $characterRepository->getById($character_id);
/* @var Character $character; */

if(!$character) {
    Response::redirect('chat.php', 'Unable to find character.');
}
if($character->inSanctionedStatus()) {
    Response::redirect('request.php?action=create&request_type_id='.RequestType::Sanction.'&character_id='.$character->Id.'&title=Desanction '.$character->CharacterName);
}

ob_start();
?>

Are you sure you want to delete <?php echo $character->CharacterName; ?>? If so, click the confirm button, otherwise click, Go Back.<br>
<br>
<br>
Think about it.. hard..<br>
<br>
<br>
<form method="post" action="chat.php?action=delete_confirmed" onsubmit="return confirm('Are you REALLY sure about this and this is not just the result of having a bad day?');">
  <input type="hidden" name="character_id" value="<?php echo $character_id; ?>">
  <input type="submit" value="Delete <?php echo $character->CharacterName; ?>">
</form>
<br />
<br />
<form method="post" action="chat.php">
  <input type="submit" value="Go Back">
</form>
<?php
$page_content = ob_get_clean();
