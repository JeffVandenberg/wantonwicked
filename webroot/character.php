<?php
use phpbb\auth\auth;
use phpbb\request\request;
use phpbb\template\twig\twig;
use phpbb\user;

include 'cgi-bin/start_of_page.php';

// perform required includes
define('IN_PHPBB', true);
$phpbb_root_path = (defined('PHPBB_ROOT_PATH')) ? PHPBB_ROOT_PATH : './forum/';
$phpEx = substr(strrchr(__FILE__, '.'), 1);
/** @noinspection PhpIncludeInspection */
include($phpbb_root_path . 'common.' . $phpEx);
$request = $phpbb_container->get('request');
/* @var request $request */
$request->enable_super_globals();

//
// Start session management
//
/* @var user $user */
/* @var auth $auth */
$user->session_begin();
$auth->acl($user->data);
$userdata = $user->data;
$user->setup('');
//
// End session management
//

// check page actions
$page_title = "";
$menu_bar = "";
$top_image = "";
$page_content = "";
$java_script = "";
$template_file = "main_ww4";
$contentHeader = "";


// build links
include 'user_panel.php';
include 'menu_bar.php';

if (isset($_GET['action'])) {
    switch ($_GET['action']) {
        case 'interface':
            include 'includes/character_interface.php';
            break;
        case 'log':
            include 'includes/character_log.php';
            break;
        case 'search':
            include 'includes/character_search.php';
            break;
        default:
    }
} else {
    include 'includes/chat_index.php';
}

/* @var twig $template */
$template->assign_vars(array(
        "PAGE_TITLE" => $page_title,
        "JAVA_SCRIPT" => $java_script,
        "USER_PANEL" => $user_panel,
        "MENU_BAR" => $menu_bar,
        "TOP_IMAGE" => $page_image,
        "PAGE_CONTENT" => $page_content,
        "CONTENT_HEADER" => $contentHeader,
        "SERVER_TIME" => (microtime(true) + date('Z'))*1000,
        "BUILD_NUMBER" => file_get_contents(ROOT_PATH . '../build_number'),
    )
);

$template->set_custom_style('wantonwicked', array(ROOT_PATH . 'templates/'));
$template_file = $template_file . '.tpl';
// Output page
page_header($page_title, true);

$template->set_filenames(
    array(
        'body' => $template_file
    )
);

page_footer();
