<?php
/* @var array $userdata */
use classes\character\data\CharacterStatus;
use classes\character\repository\CharacterRepository;
use classes\core\repository\Database;
use classes\request\repository\RequestRepository;

$page_title    = "Character Deleted";
$contentHeader = $page_title;

// get character id
$character_id = $_POST['character_id'] + 0;

// get character information
$character_query = <<<EOQ
SELECT
    C.id,
    character_name,
    slug
FROM
    characters AS C
WHERE
    C.user_id = ?
    AND C.id = ?
EOQ;

$params    = array($userdata['user_id'], $character_id);
$character = Database::getInstance()->query($character_query)->single($params);;
$characterRepository = new CharacterRepository();
if ($characterRepository->MayViewCharacter($character_id, $userdata['user_id'])) {
    if ($character) {
        // get # of characters with the same name
        $temp_name = $character['character_name'];
        $slug = $character['slug'];
        $id_query  = "select count(*) from characters where character_name like ?;";
        $params = array($temp_name .'%');
        $id = Database::getInstance()->query($id_query)->value($params);

        // mark the character as deleted
        $update_query  = "update characters set character_status_id = ?, character_name = ?, slug = ? where id = ?;";
        $params = array(
            CharacterStatus::Deleted,
            $temp_name . '_' . $id,
            $slug . '_' . $id,
            $character_id
        );
        $update_result = Database::getInstance()->query($update_query)->execute($params);

        $requestRepository = new RequestRepository();
        $requestRepository->CloseRequestsForCharacter($character_id);

    $page_content = <<<EOQ
$character[character_name] has been deleted. This is a permanent action. It can not and will not be undone.<br>
<br>
<a href="/chat.php">Return to Chat Interface</a>
EOQ;
    }
}
