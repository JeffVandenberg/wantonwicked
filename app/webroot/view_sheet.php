<?php
use classes\core\helpers\MenuHelper;
use classes\core\helpers\Response;
use classes\core\helpers\SessionHelper;
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
$top_image = "";
$page_content = "";
$java_script = "";
$body_params = "";
$extra_headers = "";
$template_file = 'main_ww4';
$contentHeader = "";

require_once('user_panel.php');
// build links
$menu_bar = include 'menu_bar.php';

if (isset($_GET['action'])) {
    //echo $_GET['action']."<br>";
    switch ($_GET['action']) {
        case 'st_view_xp':
            Response::redirect('/characters/stView/'. \classes\core\helpers\Request::getValue('view_character_id', 0));
            break;
        default:
            include 'includes/index_redirect.php';
            break;
    }
}

/* @var twig $template */
$template->assign_vars(array(
        "PAGE_TITLE" => $page_title,
        "JAVA_SCRIPT" => $java_script,
        "EXTRA_HEADERS" => $extra_headers,
        "USER_PANEL" => $user_panel,
		"MENU_BAR" => MenuHelper::GenerateMenu($mainMenu),
        "PAGE_CONTENT" => $page_content,
        "CONTENT_HEADER" => $contentHeader,
        "FLASH_MESSAGE" => SessionHelper::GetFlashMessage(),
        "SERVER_TIME" => (microtime(true) + date('Z'))*1000,
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
