<?php
use classes\core\helpers\Request;
use classes\core\helpers\SessionHelper;
use classes\core\helpers\UserdataHelper;
use phpbb\auth\auth;
use phpbb\template\twig\twig;
use phpbb\user;

include 'cgi-bin/start_of_page.php';

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
$template_file = 'main_ww4.tpl';
$page_title = "";
$menu_bar = "";
$page_image = "";
$page_content = "";
$java_script = "";
$contentHeader = "";

include 'user_panel.php';
include 'menu_bar.php';

if (!UserdataHelper::IsSt($userdata)) {
    include 'includes/index_redirect.php';
}
else {
    if (isset($_GET['action'])) {
        switch ($_GET['action']) {
            case 'permissions_view':
                if (UserdataHelper::IsHead($userdata)) {
                    include 'includes/storyteller_permissions_view.php';
                }
                else {
                    include 'includes/storyteller_index.php';
                }
                break;

            case 'permissions_add':
                if (UserdataHelper::IsHead($userdata)) {
                    include 'includes/storyteller_permissions_add.php';
                }
                else {
                    include 'includes/storyteller_index.php';
                }
                break;

            case 'permissions':
                if (UserdataHelper::IsHead($userdata)) {
                    include 'includes/storyteller_permissions.php';
                }
                else {
                    include 'includes/storyteller_index.php';
                }
                break;

            case 'profile_lookup':
                include 'includes/storyteller_profile_lookup.php';
                break;

            default:
                include 'includes/storyteller_index.php';
                break;
        }
    }
    else {
        include 'includes/storyteller_index.php';
    }
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
