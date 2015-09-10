<?php
use classes\core\helpers\FormHelper;
use classes\core\helpers\MenuHelper;
use classes\core\helpers\Request;
use classes\core\repository\Database;

$page_title = "Profile -&gt; Character Lookup";
$profileName = Request::getValue('profile_name');

if ($profileName !== null) {

    $sql    = <<<EOQ
SELECT
    *
FROM
    phpbb_users
WHERE
    username=?;
EOQ;
    $params = array($profileName);
    $user   = Database::getInstance()->query($sql)->single($params);

    $sql           = <<<EOQ
SELECT
    C.id,
    C.character_name,
    C.is_sanctioned
FROM
    phpbb_users AS U
    INNER JOIN characters AS C ON C.user_id = U.user_id
WHERE
    U.username = ?
    and C.is_deleted='N'
ORDER BY
    C.is_sanctioned ASC,
    C.character_name;
EOQ;
    $characterList = Database::getInstance()->query($sql)->all($params);
    $characters    = array();
    foreach ($characterList as $character) {
        switch ($character['is_sanctioned']) {
            case 'Y':
                $sanctionStatus = 'Sanctioned';
                break;
            case 'N':
                $sanctionStatus = 'Desanctioned';
                break;
            default:
                $sanctionStatus = 'New';
        }
        $characters[$character['id']] = $character['character_name'] . ' (' . $sanctionStatus . ')';
    }
}

$storytellerMenu = require_once('menus/storyteller_menu.php');
$menu = MenuHelper::GenerateMenu($storytellerMenu);
ob_start();
?>
    <?php echo $menu; ?>
    <table>
        <tr style="vertical-align: top;">
            <td style="width: 45%;">
                <form name="lookup" method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>?action=profile_lookup">
                    <?php echo FormHelper::Text('profile_name', $profileName, array('label' => true)); ?><br />
                    <input type="submit" name="Lookup Profile" value="Lookup Profile">
                </form>
            </td>
            <td style="width:55%;text-align: center;">
                <?php if (isset($user) && isset($characters)): ?>
                    The Characters attached to <?php echo $user['username']; ?>'s profile are:<br/>
                    <form name="sanction" method="get" action="view_sheet.php" target="_blank">
                        <?php echo FormHelper::Hidden('action', 'st_view_xp'); ?>
                        <?php echo FormHelper::Select($characters, 'view_character_id'); ?>
                        <?php echo FormHelper::Button('button', 'View Character Sheet'); ?>
                    </form>
                    <br/>
                    <div class="paragraph">
                        ID: <?php echo $user['user_id']; ?><br/>
                        Email: <?php echo $user['user_email']; ?><br/>
                        Reg Date: <?php echo date('m/d/Y h:i:sA', $user['user_regdate']); ?><br/>
                        Last Visit: <?php echo date('m/d/Y h:i:sA', $user['user_lastvisit']); ?><br/>
                        IP: <?php echo $user['user_ip']; ?><br/>
                    </div>
                <?php else: ?>
                    No Profile Looked up
                <?php endif; ?>
            </td>
        </tr>
    </table>
<?php
$page_content = ob_get_clean();