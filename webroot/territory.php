<?php

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
$menu_bar = "";
$top_image = "";
$page_content = "";
$java_script = "";
$template_file = 'main_ww4';
$contentHeader = "";

// check if user is logged in
include 'user_panel.php';
// build links
include 'menu_bar.php';

// TODO: Let's kill these!
include 'includes/classes/tenacy/domain.php';

if (isset($_GET['action'])) {
    switch ($_GET['action']) {
        case 'list':
            if (UserdataHelper::IsSt($userdata)) {
                include 'includes/territory_list.php';
            }
            break;
        case 'update_all':
            if (UserdataHelper::IsSt($userdata)) {
                include 'includes/territory_update_all.php';
            }
            break;
        case 'list_territories':
            include 'includes/territory_list_territories.php';
            break;
        case 'edit':
            include 'includes/territory_edit.php';
            break;
        case 'manage':
            include 'includes/territory_manage.php';
            break;
        case 'add':
            include 'includes/territory_add.php';
            $template_file = 'empty_template.tpl';
            break;
        case 'add_post':
            include 'includes/territory_add_post.php';
            $template_file = 'empty_template.tpl';
            break;
        case 'admin_add_character':
            include 'includes/territory_admin_add_character.php';
            $template_file = 'empty_template.tpl';
            break;
        case 'admin_add_character_post':
            include 'includes/territory_admin_add_character_post.php';
            $template_file = 'empty_template.tpl';
            break;
        case 'poach':
            include 'includes/territory_poach.php';
            $template_file = 'empty_template.tpl';
            break;
        case 'feed':
            include 'includes/territory_feed.php';
            $template_file = 'empty_template.tpl';
            break;
        case 'admin_remove_character':
            include 'includes/territory_admin_remove_character.php';
            $template_file = 'empty_template.tpl';
            break;
        case 'edit_post':
            include 'includes/territory_edit_post.php';
            $template_file = 'empty_template.tpl';
            break;
        case 'get_admin_associated_characters':
            include 'includes/territory_get_admin_associated_characters.php';
            $template_file = 'empty_template.tpl';
            break;
        case 'view':
            include 'includes/territory_view.php';
            $template_file = 'empty_template.tpl';
            break;
        default:
            include 'includes/index_default.php';
            break;
    }
} else {
    include 'includes/index_default.php';
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
