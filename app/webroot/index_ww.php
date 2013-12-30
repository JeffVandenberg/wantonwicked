<?php
use classes\core\helpers\SessionHelper;

ini_set('display_errors', 1);
include 'cgi-bin/start_of_page.php';
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

// check page actions
$page_title = "";
$menu_bar = "";
$top_image = "";
$page_content = "";
$java_script = "";
$contentHeader = "";

// build links
include 'user_panel.php';
include 'menu_bar.php';
include 'menu_bar_player_content.php';

$page_template = 'main_ww4.tpl';

if (isset($_GET['action'])) {
    switch ($_GET['action']) {
        case 'login':
            include 'cgi-bin/authenticate.php';
            include 'cgi-bin/doLogin.php';
            include 'includes/index_login.php';
            // redo the userpanel and menu_bar
            break;

        case 'logout':
            include 'includes/index_logout.php';
            break;

        case 'storytellers':
            include 'includes/index_storytellers.php';
            break;

        default:
            include 'includes/index_default.php';
    }
} else {
    include 'includes/index_default.php';
}

// initialize template
$template->set_custom_template('templates', 'main_layout');
$template->assign_vars(array(
        "PAGE_TITLE" => $page_title,
        "JAVA_SCRIPT" => $java_script,
        "USER_PANEL" => $user_panel,
        "MENU_BAR" => $menu_bar,
        "TOP_IMAGE" => $page_image,
        "PAGE_CONTENT" => $page_content,
        "CONTENT_HEADER" => $contentHeader,
        "FLASH_MESSAGE" => SessionHelper::GetFlashMessage()
    )
);

$template->set_filenames(array(
        'body' => $page_template)
);
$template->display('body');
