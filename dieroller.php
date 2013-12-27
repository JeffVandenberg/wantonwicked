<?
use classes\core\helpers\Request;

include 'cgi-bin/start_of_page.php';
include 'cgi-bin/rollWoDDice.php';

// perform required includes
define('IN_PHPBB', true);
$phpbb_root_path = (defined('PHPBB_ROOT_PATH')) ? PHPBB_ROOT_PATH : './forum/';
$phpEx = substr(strrchr(__FILE__, '.'), 1);
/** @noinspection PhpIncludeInspection */
include($phpbb_root_path . 'common.' . $phpEx);
/** @noinspection PhpIncludeInspection */
include($phpbb_root_path . 'includes/functions_display.' . $phpEx);

//
// Start session management
//
$user->session_begin();
$auth->acl($user->data);
$userdata = $user->data;
//
// End session management
//

$template = new Template("/templates/");

// check page actions
$page_title = "";
$menu_bar = "";
$top_image = "";
$page_content = "";
$java_script = "";
$contentHeader = "";
$template_layout = "main_ww4.tpl";
include 'user_panel.php';
// build links
include 'menu_bar.php';
include 'menu_bar_player_content.php';

if (isset($_GET['action'])) {
    switch ($_GET['action']) {
        case 'list':
            include 'includes/dieroller_list.php';
            break;
        case 'character':
            include 'includes/dieroller_character.php';
            break;
        case 'test':
            include 'includes/dieroller_test.php';
            break;
        case 'view_roll':
            include 'includes/dieroller_view_roll.php';
            break;
        case 'ooc':
            $page_content = "OOC Die Roller";
            include 'includes/dieroller_ooc.php';
            break;
        case 'st':
            $page_content = "Storyteller Die Roller";
            break;
        case 'custom':
            include 'includes/dieroller_custom.php';
            break;
        default:
            $page_content = "OOC Die Roller";
    }
} else {
    $page_content = "OOC Die Roller";
}

$template->set_custom_template('templates', 'main_ww4');
$template->assign_vars(array(
        "PAGE_TITLE" => $page_title,
        "JAVA_SCRIPT" => $java_script,
        "USER_PANEL" => $user_panel,
        "MENU_BAR" => $menu_bar,
        "TOP_IMAGE" => $page_image,
        "PAGE_CONTENT" => $page_content,
        "CONTENT_HEADER" => $contentHeader
    )
);

if(Request::IsAjax())
{
    $template_layout = 'empty.tpl';
}
// initialize template
$template->set_filenames(array(
        'body' => $template_layout)
);
$template->display('body');
