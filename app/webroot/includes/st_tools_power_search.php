<?php
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
    $sql    = <<<EOQ
SELECT
	character_name,
	character_type,
	is_npc,
	C.id,
	power_type,
	power_name,
	power_note,
	power_level
FROM
	characters AS C
	LEFT JOIN character_powers AS CP ON C.id = CP.character_id
WHERE
	C.is_sanctioned = 'Y'
	AND C.is_deleted = 'N'
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
    $powers = Database::getInstance()->query($sql)->all($params);
}

$powerTypes = array(
    'Attribute'  => 'Attribute',
    'Skill'      => 'Skill',
    "Merit"      => "Merit",
    "Flaw"       => "Flaw",
    "Misc"       => "Misc Traits",
    'Equipment'  => 'Equipment',
    'Vampire'    => array(
        "ICDisc"   => 'In-Clan Discipline',
        "OOCDisc"  => 'Out-of-Clan Discipline',
        "Devotion" => 'Devotion',
    ),
    'Werewolf'   => array(
        'AffGift'    => 'Affinity Gifts',
        'NonAffGift' => 'Non-Affinity Gifts',
        'Ritual'     => 'Rituals',
        'Ritulas'    => 'Ritual Rating',
        'Cunning'    => 'Cunning',
        'Glory'      => 'Glory',
        'Honor'      => 'Honor',
        'Purity'     => 'Purity',
        'Wisdom'     => 'Wisdom',

    ),
    'Mage'       => array(
        'RulingArcana'   => 'Ruling Arcana',
        'CommonArcana'   => 'Common Arcana',
        'InferiorArcana' => 'Inferior Arcana',
        'Rote'           => 'Rote'
    ),
    'Changeling' => array(
        'AffContract'    => 'Affinity Contracts',
        'NonAffContract' => 'Non-Affiny Contracts',
        'GoblinContract' => 'Goblin Contracts'
    ),
    'Geist'      => array(
        'Key'           => 'Key',
        'Manifestation' => 'Manifestation',
        'Ceremonies'    => 'Ceremony'
    )
);


$storytellerMenu = require_once('helpers/storyteller_menu.php');
$menu = MenuHelper::GenerateMenu($storytellerMenu);
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

                </td>
            </tr>
            <tr>
                <td>
                    <?php echo FormHelper::Select($powerTypes, 'power_type', $powerType); ?>
                </td>
                <td>
                    <?php echo FormHelper::Text('power_name', $powerName); ?>
                </td>
                <td>
                    <?php echo FormHelper::Text('power_note', $powerNote); ?>
                </td>
                <td>
                    <?php echo FormHelper::Text('min_power_level', $minPowerLevel); ?>
                </td>
                <td>
                    <?php echo FormHelper::Text('max_power_level', $maxPowerLevel); ?>
                </td>
                <td>
                    <?php echo FormHelper::Button('action', 'Search'); ?>
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
                        <a href="/view_sheet.php?action=st_view_xp&view_character_id=$row[id]">View</a>
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