<?php
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
$page_template = "main_ww4.tpl";
$contentHeader = "";


// build links
include 'user_panel.php';
include 'menu_bar.php';
include 'menu_bar_player_content.php';

if (isset($_GET['action'])) {
    switch ($_GET['action']) {
        case 'login':
            $page_template = "main_ww4.tpl";
            include 'includes/character_login.php';
            break;
        case 'interface':
            include 'includes/character_interface.php';
            break;
        case 'log':
            include 'includes/character_log.php';
            break;
        default:
    }
} else {
    include 'includes/chat_index.php';
}

$template->set_custom_template('templates', $page_template);
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



// initialize template
$template->set_filenames(array(
        'body' => $page_template)
);
$template->display('body');