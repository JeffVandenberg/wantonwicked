<?php
/**
 * Created by JetBrains PhpStorm.
 * User: JeffVandenberg
 * Date: 8/11/13
 * Time: 9:54 AM
 * To change this template use File | Settings | File Templates.
 */

use classes\core\helpers\MenuHelper;
use classes\core\helpers\Request;
use classes\core\helpers\SessionHelper;
use classes\core\helpers\UserdataHelper;
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
$page_image = "";
$page_content = "";
$java_script = "";
$extra_headers = "";
$template_file = 'main_ww4.tpl';
$contentHeader = "";

require_once 'user_panel.php';
$menu_bar = include 'menu_bar.php';

if (isset($_GET['action'])) {
    switch ($_GET['action']) {
        case 'list':
            include 'includes/request_list.php';
            break;
        case 'create':
            include 'includes/request_create.php';
            break;
        case 'view':
            include 'includes/request_view.php';
            break;
        case 'edit':
            include 'includes/request_edit.php';
            break;
        case 'delete':
            include 'includes/request_delete.php';
            break;
        case 'add_note':
            include 'includes/request_add_note.php';
            break;
        case 'add_character':
            include 'includes/request_add_character.php';
            break;
        case 'character_search':
            include 'includes/request_character_search.php';
            break;
        case 'attach_request':
            include 'includes/request_attach_request.php';
            break;
        case 'attach_bluebook':
            include 'includes/request_attach_bluebook.php';
            break;
        case 'attach_scene':
            include 'includes/request_attach_scene.php';
            break;
        case 'st_list':
            if(UserdataHelper::IsSt($userdata) || UserdataHelper::mayManageRequests($userdata))
            {
                include 'includes/request_st_list.php';
            }
            else
            {
                include 'includes/index_redirect.php';
            }
            break;
        case 'st_view':
            if(UserdataHelper::IsSt($userdata) || UserdataHelper::mayManageRequests($userdata))
            {
                include 'includes/request_st_view.php';
            }
            else
            {
                include 'includes/index_redirect.php';
            }
            break;
        case 'st_add_note':
            if(UserdataHelper::IsSt($userdata) || UserdataHelper::mayManageRequests($userdata))
            {
                include 'includes/request_st_add_note.php';
            }
            else
            {
                include 'includes/index_redirect.php';
            }
            break;
        case 'st_approve':
            if(UserdataHelper::IsSt($userdata) || UserdataHelper::mayManageRequests($userdata))
            {
                include 'includes/request_st_approve.php';
            }
            else
            {
                include 'includes/index_redirect.php';
            }
            break;
        case 'st_return':
            if(UserdataHelper::IsSt($userdata) || UserdataHelper::mayManageRequests($userdata))
            {
                include 'includes/request_st_return.php';
            }
            else
            {
                include 'includes/index_redirect.php';
            }
            break;
        case 'st_deny':
            if(UserdataHelper::IsSt($userdata) || UserdataHelper::mayManageRequests($userdata))
            {
                include 'includes/request_st_deny.php';
            }
            else
            {
                include 'includes/index_redirect.php';
            }
            break;
        case 'admin_time_report':
            if(UserdataHelper::IsHead($userdata))
            {
                include 'includes/request_admin_time_report.php';
            }
            else
            {
                include 'includes/index_redirect.php';
            }
            break;
        case 'admin_status_report':
            if(UserdataHelper::IsHead($userdata))
            {
                include 'includes/request_admin_status_report.php';
            }
            else
            {
                include 'includes/index_redirect.php';
            }
            break;
        case 'admin_activity_report':
            if(UserdataHelper::IsSt($userdata))
            {
                include 'includes/request_admin_activity_report.php';
            }
            else
            {
                include 'includes/index_redirect.php';
            }
            break;
        case 'submit':
            include 'includes/request_submit.php';
            break;
        case 'close':
            include 'includes/request_close.php';
            break;
        case 'forward':
            include 'includes/request_forward.php';
            break;
        case 'st_forward':
            if(UserdataHelper::IsSt($userdata) || UserdataHelper::mayManageRequests($userdata))
            {
                include 'includes/request_st_forward.php';
            }
        else
            {
                include 'includes/index_redirect.php';
            }
            break;
        case 'st_close':
            if(UserdataHelper::IsSt($userdata) || UserdataHelper::mayManageRequests($userdata))
            {
                include 'includes/request_st_close.php';
            }
        else
            {
                include 'includes/index_redirect.php';
            }
            break;
        case 'update_request_character':
            include 'includes/request_update_request_character.php';
            break;
        case 'dashboard':
            include_once 'includes/request_dashboard.php';
            break;
        default:
            include_once 'includes/request_dashboard.php';
            break;
    }
}
else {
    include_once 'includes/request_dashboard.php';
}

$menu_bar = MenuHelper::GenerateMenu($mainMenu);
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
