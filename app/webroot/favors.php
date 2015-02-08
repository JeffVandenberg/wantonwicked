<?php
use classes\core\helpers\SessionHelper;

ini_set('display_errors', 1);
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
$page_title = "";
$menu_bar = "";
$top_image = "";
$page_content = "";
$java_script = "";
$templateName = 'main_ww4.tpl';
$contentHeader = "";

// build links
include 'user_panel.php';
include 'menu_bar.php';

if (isset($_GET['action'])) {
    switch ($_GET['action']) {
        case 'list':
            include 'includes/favors_list.php';
            break;
        case 'give':
            include 'includes/favors_give.php';
            break;
        case 'add':
            include 'includes/favors_add.php';
            break;
        case 'view':
            include 'includes/favors_view.php';
            $templateName = 'empty.tpl';
            break;
        case 'transfer':
            include 'includes/favors_transfer.php';
            $templateName = 'empty.tpl';
            break;
        case 'transferFavor':
            include 'includes/favors_transferFavor.php';
            $templateName = 'empty.tpl';
            break;
        case 'discharge':
            include 'includes/favors_discharge.php';
            $templateName = 'empty.tpl';
            break;
        case 'break':
            include 'includes/favors_break.php';
            $templateName = 'empty.tpl';
            break;
        default:
            include 'includes/index_default.php';
            break;
    }
} else {
    include 'includes/index_default.php';
}


//print_r($template);
// initialize template

$template->set_custom_template('templates', 'main_ww4');
$template->assign_vars(array(
        "PAGE_TITLE" => $page_title,
        "CSS_URL" => $css_url,
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

$template->set_filenames(array(
        'body' => ($templateName))
);
$template->display('body');