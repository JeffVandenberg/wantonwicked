<?php
/* @var array $userdata */

use classes\core\helpers\MenuHelper;

$contentHeader = $page_title = "Storyteller Utilities";

$storytellerMenu = require_once('menus/storyteller_menu.php');
$menu = MenuHelper::GenerateMenu($storytellerMenu);
ob_start();
?>
    <?php echo $menu; ?>
    <div class="paragraph">
        Welcome to the Storyteller Tools section.
    </div>
<?php
$page_content = ob_get_clean();
