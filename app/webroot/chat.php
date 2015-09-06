<?php
use classes\core\helpers\SessionHelper;

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
$template_layout = 'main_ww4.tpl';
$contentHeader = "";

// check if user is logged in
include 'user_panel.php';
// build links
include 'menu_bar.php';

if (isset($_GET['action'])) {
    switch ($_GET['action']) {
        case 'login':
            $template_layout = 'empty_template.tpl';
            include 'includes/chat_login.php';
            break;
        case 'ooc_login':
            $template_layout = "blank_layout4.tpl";
            include 'includes/chat_ooc_login.php';
            break;
        case 'delete':
            include 'includes/chat_delete.php';
            break;
        case 'delete_confirmed':
            include 'includes/chat_delete_confirmed.php';
            break;
        case 'remoteaccess':
            $template_layout = 'empty_template.tpl';
            include 'includes/chat_remoteaccess.php';
            break;
        default:
            include 'includes/chat_index.php';
            break;
    }
} else {
    include 'includes/chat_index.php';
}



$template->set_custom_template('templates', substr($template_layout, 0, strlen($template_layout) - 4));
$template->assign_vars(array(
        "PAGE_TITLE" => $page_title,
        "JAVA_SCRIPT" => $java_script,
        "USER_PANEL" => $user_panel,
        "MENU_BAR" => $menu_bar,
        "TOP_IMAGE" => $page_image,
        "PAGE_CONTENT" => $page_content,
        "EXTRA_TAGS" => $extra_tags,
        "CONTENT_HEADER" => $contentHeader,
        "FLASH_MESSAGE" => SessionHelper::GetFlashMessage(),
        "SERVER_TIME" => (microtime(true) + date('Z'))*1000,
    )
);

// initialize template
$template->set_filenames(array(
        'body' => $template_layout)
);
$template->display('body');
