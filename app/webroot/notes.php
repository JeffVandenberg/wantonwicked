<?
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
$css_url = "ww4_v2.css";
$menu_bar = "";
$top_image = "";
$page_content = "";
$java_script = "";
$page_template = "main_ww4.tpl";
$contentHeader = "";

include 'user_panel.php';
include 'menu_bar.php';
include 'menu_bar_player_content.php';

if (isset($_GET['action'])) {
    switch ($_GET['action']) {
        case 'character':
            include 'includes/notes_character.php';
            break;
        case 'player':
            break;
        case 'view':
            $page_template = "blank_ww4.tpl";
            include 'includes/notes_view.php';
            break;
        default:
            $page_content = "Player Notes";
    }
} else {
    $page_content = "Player Notes";
}

$template->set_custom_template('templates', substr($page_template, 0, strlen($page_template) - 4));
$template->assign_vars(array(
        "PAGE_TITLE" => $page_title,
        "CSS_URL" => $css_url,
        "JAVA_SCRIPT" => $java_script,
        "USER_PANEL" => $user_panel,
        "MENU_BAR" => $menu_bar,
        "TOP_IMAGE" => $page_image,
        "PAGE_CONTENT" => $page_content,
        "CONTENT_HEADER" => $contentHeader
    )
);

// initialize template
$template->set_filenames(array(
        'body' => $page_template)
);
$template->display('body');
?>

