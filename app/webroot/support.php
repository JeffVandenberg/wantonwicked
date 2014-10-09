<?php
use classes\core\helpers\SessionHelper;

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
$contentHeader = "";

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

// initialize template
$template->set_custom_template('templates', 'main_ww4');
$template->assign_vars(array(
        "PAGE_TITLE" => $page_title,
        "JAVA_SCRIPT" => $java_script,
        "USER_PANEL" => $user_panel,
        "MENU_BAR" => $menu_bar,
        "TOP_IMAGE" => $page_image,
        "PAGE_CONTENT" => $page_content,
        "CONTENT_HEADER" => $contentHeader,
        'FLASH_MESSAGE' => SessionHelper::GetFlashMessage()
    )
);

$template->set_filenames(array(
        'body' => 'main_ww4.tpl')
);
$template->display('body');
