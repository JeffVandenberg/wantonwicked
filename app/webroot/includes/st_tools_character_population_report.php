<?php
use classes\core\helpers\MenuHelper;
use classes\core\helpers\Request;
use classes\core\repository\Database;

$page_title = $contentHeader = "Character Population Report";

$query = '';
$params = array();
$headerLinks = array();

if (!Request::GetValue('character_type')) {
    $query = <<<EOQ
SELECT
    character_type as `group`,
    COUNT(*) as `total`
FROM
    characters AS C
WHERE
    is_sanctioned = 'Y'
    AND is_npc = 'N'
    AND is_deleted = 'N'
GROUP BY
    character_type
EOQ;
    $columns = array(
        'Character Type' => array(
            'column' => 'group',
            'link' => '/st_tools.php?action=character_population_report&character_type=@@'
        ),
        'Population' => array(
            'column' => 'total'
        )
    );
} else {
    $characterType = Request::GetValue('character_type');
    $splat = Request::GetValue('splat', 'splat1');
    $query = <<<EOQ
SELECT
    $splat as `group`,
    COUNT(*) as `total`
FROM
    characters AS C
WHERE
    is_sanctioned = 'Y'
    AND is_npc = 'N'
    AND is_deleted = 'N'
    AND character_type = ?
GROUP BY
    $splat
EOQ;

    $params = array(
        $characterType
    );

    $columns = array(
        'Group' => array(
            'column' => 'group',
            'link' => '/st_tools.php?action=character_search&' . $splat . '[]=@@&cities[]=Savannah&only_sanctioned=1'
        ),
        'Population' => array(
            'column' => 'total'
        )
    );

    $headerLinks = array(
        '&lt;&ltBack' => '/st_tools.php?action=character_population_report',
        'Toggle Splat' => '/st_tools.php?action=character_population_report&character_type=' . $characterType
            . '&splat=' . (($splat == 'splat1') ? 'splat2' : 'splat1')
    );

}


$rows = Database::GetInstance()->Query($query)->All($params);
$storytellerMenu = require_once('helpers/storyteller_menu.php');
$menu = MenuHelper::GenerateMenu($storytellerMenu);
ob_start();
?>
<?php echo $menu; ?>
<?php if (count($headerLinks)): ?>
    <h3>Links</h3>
    <?php foreach ($headerLinks as $target => $link): ?>
        <a href="<?php echo $link; ?>"><?php echo $target; ?></a>
    <?php endforeach; ?>
<?php endif; ?>
    <table>
        <thead>
        <tr>
            <?php foreach ($columns as $key => $options): ?>
                <th>
                    <?php echo $key; ?>
                </th>
            <?php endforeach; ?>
        </tr>
        </thead>
        <?php foreach ($rows as $row): ?>
            <tr>
                <?php foreach ($columns as $key => $options): ?>
                    <td>
                        <?php if (isset($options['link'])): ?>
                            <a href="<?php echo str_replace('@@', $row[$options['column']], $options['link']); ?>"><?php echo $row[$options['column']]; ?></a>
                        <?php else: ?>
                            <?php echo $row[$options['column']]; ?>
                        <?php endif; ?>
                    </td>
                <?php endforeach; ?>
            </tr>
        <?php endforeach; ?>
    </table>

<?php
$page_content = ob_get_clean();