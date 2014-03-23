<?php
/* @var array $userdata */

use classes\core\helpers\MenuHelper;
use classes\core\helpers\UserdataHelper;

$contentHeader = $page_title = "Storyteller Utilities";

$storytellerMenu = require_once('helpers/storyteller_menu.php');
$menu = MenuHelper::GenerateMenu($storytellerMenu);
ob_start();
?>
    <?php echo $menu; ?>
    <div class="paragraph">
        Welcome to the Storyteller Tools section.
    </div>
<?php
$page_content = ob_get_clean();
