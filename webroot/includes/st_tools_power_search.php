<?php

use classes\character\data\CharacterStatus;
use classes\core\helpers\FormHelper;
use classes\core\helpers\MenuHelper;
use classes\core\helpers\Request;
use classes\core\repository\Database;

$contentHeader = $page_title = 'Power and Merit Search';

$powerType = Request::getValue('power_type');
$powerName = Request::getValue('power_name');
$powerNote = Request::getValue('power_note');
$minPowerLevel = Request::getValue('min_power_level', 0);
$maxPowerLevel = Request::getValue('max_power_level', 8);

if (Request::isPost()) {
    $statuses = implode(',', CharacterStatus::SANCTIONED);

    if (in_array($powerType, ['power_stat'])) {
        $sql = <<<SQL
SELECT
	character_name,
	character_type,
	is_npc,
	C.id,
	"$powerType" as power_name,
	$powerType as `power_level`,
	NULL as power_note,
	NULL as extra
FROM
	characters AS C
WHERE
	C.character_status_id IN ($statuses)
	AND C.$powerType >= ?
	AND C.$powerType <= ?
ORDER BY
	character_type,
	character_name
SQL;
        $params = [
            $minPowerLevel,
            $maxPowerLevel
        ];
    } else {
        $sql = <<<EOQ
SELECT
	character_name,
	character_type,
	is_npc,
	C.id,
	power_type,
	power_name,
	power_note,
	power_level,
	CP.extra
FROM
	characters AS C
	LEFT JOIN character_powers AS CP ON C.id = CP.character_id
WHERE
	C.character_status_id IN ($statuses)
	AND CP.power_type = ?
	AND CP.power_name LIKE ?
	AND CP.power_note LIKE ?
	AND CP.power_level >= ?
	AND CP.power_level <= ?
ORDER BY
	character_type,
	character_name
EOQ;
        $params = array($powerType, $powerName . '%', $powerNote . '%', $minPowerLevel, $maxPowerLevel);
    }

    $powers = Database::getInstance()->query($sql)->all($params);

    $powers = array_map(function ($item) {
        $extra = json_decode($item['extra']);
        $text = '';
        if (count($extra)) {
            foreach ($extra as $key => $value) {
                $text .= $key . ': ' . $value . '<br />';
            }
        }
        $item['extra'] = $text;
        return $item;
    }, $powers);
}

$powerTypes = array(
    'Attribute' => 'Attribute',
    'Skill' => 'Skill',
    "Merit" => "Merit",
    "Flaw" => "Flaw",
    "Misc" => "Misc Traits",
    'Equipment' => 'Equipment',
    'power_stat' => 'Power Stat',
    'Vampire' => array(
        "ICDisc" => 'In-Clan Discipline',
        "OOCDisc" => 'Out-of-Clan Discipline',
        "Devotion" => 'Devotion',
    ),
    'Werewolf' => array(
        'moongift' => 'Moon Gifts',
        'shadowgift' => 'Shadow Gifts',
        'wolfgift' => 'Wolf Gifts',
        'Renown' => 'Renown',
    ),
    'Mage' => array(
        'arcana' => 'Arcana',
        'Rote' => 'Rote'
    ),
    'Changeling' => array(
        'contract' => 'Contracts'
    ),
);


$storytellerMenu = require_once('menus/storyteller_menu.php');
$menu = MenuHelper::generateMenu($storytellerMenu);
ob_start();
?>
<?php echo $menu; ?>
    <form method="post" action="/st_tools.php?action=power_search">
        <table>
            <tr>
                <td>
                    Power Type
                </td>
                <td>
                    Name
                </td>
                <td>
                    Note
                </td>
                <td>
                    Min Level
                </td>
                <td>
                    Max Level
                </td>
                <td></td>
            </tr>
            <tr>
                <td>
                    <?php echo FormHelper::select($powerTypes, 'power_type', $powerType); ?>
                </td>
                <td>
                    <?php echo FormHelper::text('power_name', $powerName); ?>
                </td>
                <td>
                    <?php echo FormHelper::text('power_note', $powerNote); ?>
                </td>
                <td>
                    <?php echo FormHelper::text('min_power_level', $minPowerLevel); ?>
                </td>
                <td>
                    <?php echo FormHelper::text('max_power_level', $maxPowerLevel); ?>
                </td>
                <td>
                    <button type="submit" value="Search" class="button">Search</button>
                </td>
            </tr>
        </table>
    </form>
<?php if (isset($powers)): ?>
    <?php if (count($powers) > 0): ?>
        <h3>Search Results</h3>
        <table>
            <tr>
                <th>
                    Character Name
                </th>
                <th>
                    Character Type
                </th>
                <th>
                    NPC
                </th>
                <th>
                    Power Name
                </th>
                <th>
                    Power Note
                </th>
                <th>
                    Power Level
                </th>
                <th>
                    Extra
                </th>
                <th>

                </th>
            </tr>
            <?php foreach ($powers as $row): ?>
                <tr>
                    <td>
                        <?php echo $row['character_name']; ?>
                    </td>
                    <td>
                        <?php echo $row['character_type']; ?>
                    </td>
                    <td>
                        <?php echo $row['is_npc']; ?>
                    </td>
                    <td>
                        <?php echo $row['power_name']; ?>
                    </td>
                    <td>
                        <?php echo $row['power_note']; ?>
                    </td>
                    <td>
                        <?php echo $row['power_level']; ?>
                    </td>
                    <td>
                        <?php echo $row['extra']; ?>
                    </td>
                    <td>
                        <a href="/characters/stView/<?php echo $row['id']; ?>">View</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </table>
    <?php else: ?>
        No Results Found
    <?php endif; ?>
<?php endif; ?>
<?php
$page_content = ob_get_clean();
