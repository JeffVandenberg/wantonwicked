<?php
/**
 * Created by JetBrains PhpStorm.
 * User: JeffVandenberg
 * Date: 9/25/13
 * Time: 6:53 PM
 * To change this template use File | Settings | File Templates.
 */

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
$page_template = "no_menu_layout4.tpl";
$contentHeader = "";

// build links
include 'user_panel.php';

if (isset($_GET['action'])) {
    switch ($_GET['action']) {
        case 'search':
            $page_template = "blank_layout4.tpl";
            include 'includes/users_search.php';
            break;
        default:
            include 'includes/index_default.php';
            break;
    }
} else {
    include 'includes/index_default.php';
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