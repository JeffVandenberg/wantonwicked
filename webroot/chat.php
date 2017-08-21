<?php

use classes\core\helpers\Request;
use classes\core\helpers\Response;
use classes\core\helpers\SessionHelper;
use phpbb\auth\auth;
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
/* @var \phpbb\request\request $request */
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
$template_file = 'main_ww4.tpl';
$contentHeader = "";

// check if user is logged in
include 'user_panel.php';
// build links
include 'menu_bar.php';

if (isset($_GET['action'])) {
    switch ($_GET['action']) {
        case 'ooc_login':
            $template_file = "blank_layout4.tpl";
            include 'includes/chat_ooc_login.php';
            break;
        case 'delete':
            include 'includes/chat_delete.php';
            break;
        case 'delete_confirmed':
            include 'includes/chat_delete_confirmed.php';
            break;
        case 'jeff':
            SessionHelper::SetFlashMessage('test');
            Response::redirect('/');
            break;
        default:
            include 'includes/chat_index.php';
            break;
    }
} else {
    include 'includes/chat_index.php';
}

/* @var twig $template */
$template->set_custom_style('wantonwicked', array(ROOT_PATH . 'templates/'));
$template->assign_block_vars_array('messages', SessionHelper::GetFlashMessage());
$template->assign_vars(array(
        "PAGE_TITLE" => $page_title,
        "JAVA_SCRIPT" => $java_script,
        "TOP_IMAGE" => $page_image ?? '',
        "MENU_BAR" => $menu_bar,
        "PAGE_CONTENT" => $page_content,
        "EXTRA_HEADERS" => $extra_headers ?? '',
        "USER_PANEL" => $user_panel,
        "USER_INFO" => $userInfo,
        "CONTENT_HEADER" => $contentHeader,
        "SERVER_TIME" => (microtime(true) + date('Z'))*1000,
        "BUILD_NUMBER" => file_get_contents(ROOT_PATH . '../build_number'),
    )
);

if(Request::isAjax())
{
    $template_file = 'empty.tpl';
}
// initialize template
$template->set_filenames(array(
        'body' => $template_file)
);
$template->display('body');
