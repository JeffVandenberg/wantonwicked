<?php
use classes\core\helpers\MenuHelper;
use classes\core\repository\Database;

$page_title   = "Look up Character Names";
$page_content = "Look up Character";

$character_name = "";
$result_set     = "";

// do search
if (isset($_GET['character_name'])) {
    $character_name = str_replace("*", "%", $_GET['character_name']);

    $character_query = "select * from characters where character_name like ? and is_deleted='n';";
    $params = array(
        $character_name
    );
    $character = Database::getInstance()->query($character_query)->all($params);

    if (count($character) > 0) {
        $result_set = <<<EOQ
<br><br>
<table border="0" cellpadding="2" cellspacing="2" class="normal_text">
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
        foreach($character as $character_detail) {
            $row_color = (($i++) % 2) ? "#443f33" : "";

            $result_set .= <<<EOQ
  <tr bgcolor="$row_color">
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
    }
    else {
        $result_set = "<br><br>No characters found.";
    }
}

$storytellerMenu = require_once('menus/storyteller_menu.php');
$menu = MenuHelper::GenerateMenu($storytellerMenu);
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
