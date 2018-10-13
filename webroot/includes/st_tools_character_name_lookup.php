<?php
use classes\character\data\CharacterStatus;
use classes\core\helpers\MenuHelper;
use classes\core\repository\Database;

$page_title = "Look up Character Names";
$page_content = "Look up Character";

$character_name = "";
$result_set = "";

// do search
if (isset($_GET['character_name'])) {
    $character_name = str_replace("*", "%", $_GET['character_name']);

    $character_query = <<<SQL
SELECT 
  id,
  character_name,
  city,
  character_type,
  slug
FROM 
  characters AS C
WHERE 
  character_name LIKE ? 
  AND C.character_status_id != ?;
SQL;
    $params = [
        $character_name,
        CharacterStatus::DELETED
    ];

    $character = Database::getInstance()->query($character_query)->all($params);

    if (count($character) > 0) {
        $result_set = <<<EOQ
<br><br>
<table>
  <tr>
    <th>
      Character Name
    </th>
    <th>
      City
    </th>
    <th>
      Character Type
    </th>
    <th>
    </th>
  </tr>
EOQ;

        $i = 0;
        foreach ($character as $character_detail) {
            $result_set .= <<<EOQ
  <tr>
    <td>
      $character_detail[character_name]
    </td>
    <td>
      $character_detail[city]
    </td>
    <td>
      $character_detail[character_type]
    </td>
    <td>
      <a href="/characters/stView/$character_detail[id]">View Character</a>
    </td>
  </tr>
EOQ;
        }

        $result_set .= "</table>";
        $character_name = stripslashes($character_name);
    } else {
        $result_set = "<br><br>No characters found.";
    }
}

$storytellerMenu = require_once('menus/storyteller_menu.php');
$menu = MenuHelper::generateMenu($storytellerMenu);
// detail form
$search_form = <<<EOQ
$menu
<form method="get" action="$_SERVER[PHP_SELF]">
  Partial Character Name: <input type="text" name="character_name" value="$character_name" size="20" maxlength="35">
  <input type="submit" value="Find Characters"><br>
  <input type="hidden" name="action" value="character_name_lookup" />
</form>
Valid wildcards are * or %
EOQ;

$page_content = $search_form . $result_set;
