<?php
use classes\core\helpers\SessionHelper;
use classes\core\helpers\UserdataHelper;

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
        case 'create':
            break;
        case 'view':
            include 'includes/encounter_view.php';
            break;
        case 'update':
            break;
        case 'archive':
            break;
        case 'list':
            if(UserdataHelper::IsSt($userdata)) {
                include 'includes/encounter_list.php';
            }
            else {
                include 'includes/index_default.php';
            }
            break;
        case 'st_list':
            break;

    }
} else {
    include 'includes/index_default.php';
}

// initialize template
$template->set_custom_template('templates', 'main_ww4');
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
