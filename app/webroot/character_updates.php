<?php
/**
 * Created by JetBrains PhpStorm.
 * User: JeffVandenberg
 * Date: 5/11/13
 * Time: 1:05 PM
 * To change this template use File | Settings | File Templates.
 */

use classes\core\helpers\Response;

ini_set('display_errors', 1);
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
$css_url = "ww4_v2.css";
$menu_bar = "";
$top_image = "";
$page_content = "";
$java_script = "";
$extra_tags = "onLoad='showClock();'";
$template_file = 'main_ww4.tpl';

// build links
include 'user_panel.php';
include 'menu_bar.php';
include 'menu_bar_city_book.php';

if(isset($_GET['action']))
{
    //echo $_GET['action']."<br>";
    switch($_GET['action'])
    {
        case 'list':
            include 'includes/character_updates_list.php';
            break;
        case 'page':
            break;
        case 'create':
            include 'includes/character_updates_create.php';
            break;
        case 'edit':
            include 'includes/character_updates_edit.php';
            break;
        case 'view':
            break;
        case 'st_list':
            break;
        case 'st_page':
            break;
        case 'st_view':
            break;
        case 'st_edit':
            break;
    }
}
else
{
    Response::Redirect('/');
}


//print_r($template);
// initialize template

$template->set_custom_template('templates', $template_file);
$template->assign_vars(array(
        "PAGE_TITLE" => $page_title,
        "CSS_URL" => $css_url,
        "JAVA_SCRIPT" => $java_script,
        "USER_PANEL" => $user_panel,
        "MENU_BAR" => $menu_bar,
        "TOP_IMAGE" => $page_image,
        "PAGE_CONTENT" => $page_content,
        "EXTRA_TAGS" => $extra_tags
    )
);

$template->set_filenames(array(
        'body' => ($template_file . '.tpl'))
);
$template->display('body');
//page_footer();
