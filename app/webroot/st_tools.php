<?php
use classes\core\helpers\SessionHelper;
use classes\core\helpers\UserdataHelper;

include 'cgi-bin/start_of_page.php';

// perform required includes
define('IN_PHPBB', true);
$phpbb_root_path = (defined('PHPBB_ROOT_PATH')) ? PHPBB_ROOT_PATH : './forum/';
$phpEx = substr(strrchr(__FILE__, '.'), 1);
include($phpbb_root_path . 'common.' . $phpEx);
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
// check page actions
$page_title = "";
$menu_bar = "";
$top_image = "";
$page_content = "";
$java_script = "";
$contentHeader = "";

$page_template = 'main_ww4.tpl';

// build links
include 'user_panel.php';
include 'menu_bar.php';

if (UserdataHelper::IsSt($userdata) || UserdataHelper::IsWikiManager($userdata)) {
    if (isset($_GET['action'])) {
        switch ($_GET['action']) {
            case 'character_name_lookup':
                include 'includes/st_tools_character_name_lookup.php';
                break;
            case 'character_search':
                include 'includes/st_tools_character_search.php';
                break;
            case 'power_search':
                include 'includes/st_tools_power_search.php';
                break;

            case 'scene_list':
                include 'includes/st_tools_scene_list.php';
                break;

            case 'icons_list':
                if (UserdataHelper::IsWikiManager($userdata) || UserdataHelper::IsHead($userdata)) {
                    include 'includes/st_tools_icons_list.php';
                }
                else {
                    include 'includes/storyteller_index.php';
                }
                break;
            case 'icons_add':
                if (UserdataHelper::IsWikiManager($userdata) || UserdataHelper::IsHead($userdata)) {
                    include 'includes/st_tools_icons_add.php';
                    $page_template = 'blank_layout4.tpl';
                }
                else {
                    include 'includes/storyteller_index.php';
                }
                break;
            case 'icons_view':
                if (UserdataHelper::IsWikiManager($userdata) || UserdataHelper::IsHead($userdata)) {
                    include 'includes/st_tools_icons_view.php';
                    $page_template = 'blank_layout4.tpl';
                }
                else {
                    include 'includes/storyteller_index.php';
                }
                break;
            case 'profile_transfer':
                if (UserdataHelper::IsHead($userdata)) {
                    include 'includes/st_tools_profile_transfer.php';
                }
                else {
                    include 'includes/storyteller_index.php';
                }
                break;
            case 'suspend_venue':
                if (UserdataHelper::IsHead($userdata)) {
                    include 'includes/st_tools_suspend_venue.php';
                }
                else {
                    include 'includes/storyteller_index.php';
                }
                break;
            case 'character_population_report':
                include 'includes/st_tools_character_population_report.php';
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
else {
    include 'includes/index_redirect.php';
}

$template->set_custom_template('templates', 'main_layout4');
$template->assign_vars(array(
        "PAGE_TITLE" => $page_title,
        "JAVA_SCRIPT" => $java_script,
        "USER_PANEL" => $user_panel,
        "MENU_BAR" => $menu_bar,
        "TOP_IMAGE" => $page_image,
        "PAGE_CONTENT" => $page_content,
        "CONTENT_HEADER" => $contentHeader,
        "FLASH_MESSAGE" => SessionHelper::GetFlashMessage(),
        "SERVER_TIME" => (microtime(true) + date('Z'))*1000,
    )
);


// initialize template
$template->set_filenames(array(
        'body' => $page_template)
);
$template->display('body');
