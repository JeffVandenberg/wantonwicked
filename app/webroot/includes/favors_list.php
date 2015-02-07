<?php
/* @var array $userdata */

// get character id
use classes\character\data\Character;
use classes\character\repository\CharacterRepository;
use classes\core\helpers\MenuHelper;
use classes\core\helpers\Request;
use classes\core\helpers\Response;
use classes\core\helpers\SessionHelper;
use classes\core\repository\RepositoryManager;

$characterId = Request::GetValue('character_id', 0);

$characterRepository = RepositoryManager::GetRepository('classes\character\data\Character');
/* @var CharacterRepository $characterRepository */

if (!$characterRepository->MayViewCharacter($characterId, $userdata['user_id'])) {
    SessionHelper::SetFlashMessage('Not a valid character to view!');
    Response::Redirect('');
}

$character = $characterRepository->FindByIdObj($characterId);
/* @var Character $character */

$contentHeader = $page_title = "Favors for: " . $character->CharacterName;


$sql = <<<EOQ
SELECT
	favors.*,
	from_character.character_name AS from_character_name,
	to_character.character_name AS to_character_name,
	favor_types.name AS favor_type_name
FROM
	favors
		LEFT JOIN characters AS from_character ON favors.source_id = from_character.id
		LEFT JOIN characters AS to_character ON favors.target_id = to_character.id
		LEFT JOIN favor_types ON favors.favor_type_id = favor_types.id
WHERE
	favors.target_id = $characterId
	AND is_broken = 0
	AND date_discharged IS NULL
ORDER BY
	favor_type_id,
	from_character.character_name
EOQ;
$result = mysql_query($sql) or die(mysql_error());

$favorsToCharacter = array();
while ($row = mysql_fetch_assoc($result)) {
    $favorsToCharacter[] = $row;
}

$sql = <<<EOQ
SELECT
	favors.*,
	from_character.character_name AS from_character_name,
	to_character.character_name AS to_character_name,
	favor_types.name AS favor_type_name
FROM
	favors
		LEFT JOIN characters AS from_character ON favors.source_id = from_character.id
		LEFT JOIN characters AS to_character ON favors.target_id = to_character.id
		LEFT JOIN favor_types ON favors.favor_type_id = favor_types.id
WHERE
	favors.source_id = $characterId
	AND is_broken = 0
	AND date_discharged IS NULL
ORDER BY
	favor_type_id,
	to_character.character_name
EOQ;
$result = mysql_query($sql) or die(mysql_error());

$favorsFromCharacter = array();
while ($row = mysql_fetch_assoc($result)) {
    $favorsFromCharacter[] = $row;
}

require_once('helpers/character_menu.php');
/* @var array $characterMenu */
$menu = MenuHelper::GenerateMenu($characterMenu);

ob_start();
?>

<?php echo $menu; ?>
    <div id="favorPane" style="display:none;">
    </div>
    <div class="paragraph">
        <a href="favors.php?action=give&character_id=<?php echo $characterId; ?>">Give Favor to another Character</a>
    </div>
    <h3 style="clear:both;">
        Favors Owed to <?php echo $character->CharacterName; ?>
    </h3>
    <table>
        <tr>
            <th>
                From
            </th>
            <th>
                Type
            </th>
            <th>
                Description
            </th>
            <th>
                Given On
            </th>
            <th>
                Actions
            </th>
        </tr>

        <?php if (count($favorsToCharacter) > 0): ?>
            <?php foreach ($favorsToCharacter as $row): ?>
                <tr>
                    <td>
                        <?php echo $row['from_character_name']; ?>
                    </td>
                    <td>
                        <?php echo $row['favor_type_name']; ?>
                    </td>
                    <td>
                        <?php echo $row['description']; ?>
                    </td>
                    <td>
                        <?php echo $row['date_given']; ?>
                    </td>
                    <td>
                        <a href="#" onclick="return viewFavor(<?php echo $row['favor_id']; ?>);">View</a>
                        <a href="#" onclick="return transferFavor(<?php echo $row['favor_id']; ?>);">Transfer</a>
                        <a href="#" onclick="return dischargeFavor(<?php echo $row['favor_id']; ?>);">Discharge</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        <?php else: ?>
            <tr>
                <td colspan="5">
                    No favors are owed to <?php echo $character->CharacterName; ?>
                </td>
            </tr>
        <?php endif; ?>
    </table>

<br />
<br />
    <h3>
        Favors Owed by <?php echo $character->CharacterName; ?>
    </h3>
    <table>
        <tr>
            <th>
                To
            </th>
            <th>
                Type
            </th>
            <th>
                Description
            </th>
            <th>
                Given On
            </th>
            <th>
                Actions
            </th>
        </tr>

        <?php if (count($favorsFromCharacter) > 0): ?>
            <?php foreach ($favorsFromCharacter as $row): ?>
                <tr>
                    <td>
                        <?php echo $row['from_character_name']; ?>
                    </td>
                    <td>
                        <?php echo $row['favor_type_name']; ?>
                    </td>
                    <td>
                        <?php echo $row['description']; ?>
                    </td>
                    <td>
                        <?php echo $row['date_given']; ?>
                    </td>
                    <td>
                        <a href="#" onclick="return viewFavor(<?php echo $row['favor_id']; ?>);">View</a>
                        <a href="#" onclick="return transferFavor(<?php echo $row['favor_id']; ?>);">Transfer</a>
                        <a href="#" onclick="return breakFavor(<?php echo $row['favor_id']; ?>);">Break</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        <?php else: ?>
            <tr>
                <td colspan="5">
                    No favors are owed by <?php echo $character->CharacterName; ?>
                </td>
            </tr>
        <?php endif; ?>
    </table>

    <script type="text/javascript">
        $(document).ready(function () {
//            $("#favorPaneClose").click(function () {
//                $("#favorPane").css("display", "none");
//            });
//            $(document).keypress(function (e) {
//                if (e.keyCode == 27) {
//                    $("#favorPane").css("display", "none");
//                }
//            });
//            $(document).keydown(function (e) {
//                if (e.keyCode == 27) {
//                    $("#favorPane").css("display", "none");
//                }
//            });
        });
    </script>
<?php
$page_content = ob_get_clean();