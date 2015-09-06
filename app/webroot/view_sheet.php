<?php
use classes\core\helpers\SessionHelper;
use classes\core\helpers\UserdataHelper;


include 'cgi-bin/start_of_page.php';
include 'cgi-bin/buildWoDSheet.php';
include 'cgi-bin/buildWoDSheetXP.php';
include 'cgi-bin/makeDotsXP.php';
include 'cgi-bin/updateWoDSheet.php';
include 'cgi-bin/updateWoDSheetXP.php';
include 'cgi-bin/charSheetConstants.php';
include 'cgi-bin/submitPost.php';

// perform required includes
define('IN_PHPBB', true);
$phpbb_root_path = (defined('PHPBB_ROOT_PATH')) ? PHPBB_ROOT_PATH : './forum/';
$phpEx = substr(strrchr(__FILE__, '.'), 1);
/** @noinspection PhpIncludeInspection */
include($phpbb_root_path . 'common.' . $phpEx);
/** @noinspection PhpIncludeInspection */
include($phpbb_root_path . 'includes/functions_display.' . $phpEx);
/** @noinspection PhpIncludeInspection */
include($phpbb_root_path . 'includes/functions_posting.' . $phpEx);
/** @noinspection PhpIncludeInspection */
include($phpbb_root_path . 'includes/message_parser.' . $phpEx);

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
$top_image = "";
$page_content = "";
$java_script = "";
$body_params = "";
$extra_headers = "";
$template_name = 'main_ww4.tpl';
$contentHeader = "";

require_once('user_panel.php');
// build links
include 'menu_bar.php';

if (isset($_GET['action'])) {
    //echo $_GET['action']."<br>";
    switch ($_GET['action']) {
        case 'create':
            // Obsolete -MarcD 6th Oct 2009
            //include 'includes/view_sheet_create.php';
            include 'includes/index_redirect.php';
            break;
        case 'create_xp':
            include 'includes/view_sheet_create_xp.php';
            break;
        case 'create_xp2':
            include 'includes/view_sheet_create_xp2.php';
            break;
        case 'get_fragment':
            $template_name = 'empty_template.tpl';
            include 'includes/view_sheet_get_fragment.php';
            break;
        case 'fragment':
            include 'includes/view_sheet_fragment.php';
            break;
        case 'get':
            include 'includes/view_sheet_get.php';
            break;
        case 'view_own_xp':
            include 'includes/view_sheet_view_own_xp.php';
            break;
        case 'update_own_xp':
            include 'includes/view_sheet_update_own_xp.php';
            break;
        case 'view_other':
            include 'includes/view_sheet_view_other.php';
            break;
        case 'view_other_xp':
            include 'includes/view_sheet_view_other_xp.php';
            break;
        case 'st_view':
            include 'includes/index_redirect.php';
            break;
        case 'st_view_xp':
            if(UserdataHelper::IsSt($userdata)) {
                include 'includes/view_sheet_st_view_xp.php';
            } else {
                include 'includes/index_redirect.php';
            }

            break;
        case 'profile':
            include 'includes/view_sheet_profile.php';
            break;
        default:
            include 'includes/index_redirect.php';
            break;
    }
}

$template->set_custom_template('templates', 'main_ww4');
$template->assign_vars(array(
        "PAGE_TITLE" => $page_title,
        "JAVA_SCRIPT" => $java_script,
        "EXTRA_HEADERS" => $extra_headers,
        "USER_PANEL" => $user_panel,
		"MENU_BAR" => $menu_bar,
        "PAGE_CONTENT" => $page_content,
        "CONTENT_HEADER" => $contentHeader,
        "FLASH_MESSAGE" => SessionHelper::GetFlashMessage(),
        "SERVER_TIME" => (microtime(true) + date('Z'))*1000,
    )
);

// initialize template
$template->set_filenames(array(
        'body' => $template_name)
);
$template->display('body');

