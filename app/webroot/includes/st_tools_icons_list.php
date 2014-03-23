<?php
// check page actions
use classes\core\helpers\MenuHelper;
use classes\core\repository\Database;

$contentHeader = $page_title = "Icon List";

// test if updating/delete
if (isset($_POST['action'])) {
    if ($_POST['action'] == 'update') {
        $skill_list = $_POST['delete'];
        while (list($key, $value) = each($skill_list)) {
            //echo "delete: $key: $value<br>";
            $delete_query = "delete from icons where id=$value;";
            //echo $delete_query."<br>";
            $delete_result = mysql_query($delete_query);
        }
    }
}
$icon_query = "select * from icons order by Icon_Name";
$icons = Database::GetInstance()->Query($icon_query)->All();

$storytellerMenu = require_once('helpers/storyteller_menu.php');
$menu = MenuHelper::GenerateMenu($storytellerMenu);

ob_start();
?>
    <script language="JavaScript">
        function submitForm() {
            window.document.icon_list.submit();
        }
    </script>
    <?php echo $menu; ?>
    <form name="icon_list" id="icon_list" method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>?action=icons_list">
        <a href="icons_add.php"
           onClick="window.open('$_SERVER[PHP_SELF]?action=icons_add', 'addIcon', 'width=300,height=300,resizable,scrollbars');return false;">Add
            Icon</a>
        &nbsp;&nbsp;&nbsp;&nbsp;
        <a href="#" onclick="submitForm();">Delete Icon(s)</a>
        <input type="hidden" name="action" id="action" value="update">
        <br>
        <table>
            <tr>
                <th>
                </th>
                <th>
                    Name
                </th>
                <th>
                    File
                </th>
                <th>
                    Player Viewable
                </th>
                <th>
                    ST Viewable
                </th>
                <th>
                    Head ST Viewable
                </th>
            </tr>
            <?php foreach ($icons as $icon_detail): ?>
                <tr>
                    <td>
                        <input type="checkbox" name="delete[]" id="delete[]" value="<?php echo $icon_detail['ID']; ?>">
                    </td>
                    <td>
                        <a href="#"
                           onClick="window.open('<?php echo $_SERVER['PHP_SELF']; ?>?action=icons_view&id=<?php echo $icon_detail['ID']; ?>', 'icon', 'width=300,height=300,resizable,scrollbars');return false;"><?php echo $icon_detail['Icon_Name']; ?></a>
                    </td>
                    <td>
                        <?php echo $icon_detail['Icon_ID']; ?>
                    </td>
                    <td>
                        <?php echo $icon_detail['Player_Viewable']; ?>
                    </td>
                    <td>
                        <?php echo $icon_detail['GM_Viewable']; ?>
                    </td>
                    <td>
                        <?php echo $icon_detail['Admin_Viewable']; ?>
                    </td>
                </tr>
            <?php endforeach; ?>
        </table>
    </form>
<?php
$page_content = ob_get_clean();