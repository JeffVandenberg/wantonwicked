<?php
/**
 * Created by JetBrains PhpStorm.
 * User: JeffVandenberg
 * Date: 9/25/13
 * Time: 6:53 PM
 * To change this template use File | Settings | File Templates.
 */

use classes\core\helpers\Request;
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
$template_file = "main_ww4.tpl";
$contentHeader = "";

// build links
include 'user_panel.php';

if (isset($_GET['action'])) {
    switch ($_GET['action']) {
        case 'search':
            include 'includes/users_search.php';
            break;
        case 'assign_groups':
            include 'includes/users_assign_groups.php';
            break;
        default:
            include 'includes/index_default.php';
            break;
    }
} else {
    include 'includes/index_default.php';
}

/* @var $template twig */
$template->set_custom_style('wantonwicked', array(ROOT_PATH . 'templates/'));

$template->assign_vars(array(
        "PAGE_TITLE" => $page_title,
        "JAVA_SCRIPT" => $java_script,
        "TOP_IMAGE" => $page_image,
        "MENU_BAR" => $menu_bar,
        "PAGE_CONTENT" => $page_content,
        "EXTRA_HEADERS" => $extra_headers,
        "USER_PANEL" => $user_panel,
        "CONTENT_HEADER" => $contentHeader,
        "FLASH_MESSAGE" => SessionHelper::GetFlashMessage(),
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
