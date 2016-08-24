<?php
use classes\core\helpers\Request;
use classes\core\helpers\SessionHelper;
use phpbb\auth\auth;
use phpbb\template\twig\twig;
use phpbb\user;

/**
 * Created by PhpStorm.
 * User: jvandenberg
 * Date: 9/25/13
 * Time: 3:46 PM
 */

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
$contentHeader = "";
$template_file = 'main_ww4.tpl';

// build links
include 'user_panel.php';
include 'menu_bar.php';

if(isset($_GET['action']))
{
    switch($_GET['action'])
    {
        case 'list':
            include 'includes/support_list.php';
            // redo the userpanel and menu_bar
            break;

        case 'contribute':
            include 'includes/support_contribute.php';
            break;

        case 'manage':
            include 'includes/support_manage.php';
            break;
        case 'add':
            include 'includes/support_add.php';
            break;
        case 'edit':
            include 'includes/support_edit.php';
            break;
        case 'setCharacters':
            include 'includes/support_set_characters.php';
            break;
        default:
            include 'includes/support_list.php';
    }
}
else
{
    include 'includes/support_list.php';
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
