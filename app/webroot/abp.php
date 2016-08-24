<?php
use classes\core\helpers\SessionHelper;
use classes\core\helpers\UserdataHelper;
use phpbb\auth\auth;
use phpbb\request\request;
use phpbb\template\twig\twig;
use phpbb\user;

include 'cgi-bin/start_of_page.php';
// perform required includes
define('IN_PHPBB', true);
$phpbb_root_path = (defined('PHPBB_ROOT_PATH')) ? PHPBB_ROOT_PATH : './forum/';
$phpEx = substr(strrchr(__FILE__, '.'), 1);
include($phpbb_root_path . 'common.' . $phpEx);
$request = $phpbb_container->get('request');
/* @var request $request */
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
$css_url = "www.wantonwicked.net/wicked.css";
$menu_bar = "";
$top_image = "";
$page_content = "";
$java_script = "";
$extra_tags = "onLoad='showClock();'";
$template_file = "main_ww4";

// build links
include 'user_panel.php';
include 'menu_bar.php';

// common includes
include 'includes/classes/tenacy/abp/abp.php';

if(isset($_GET['action']))
{
	switch($_GET['action'])
	{
		case 'home':
			if(UserdataHelper::IsSt($userdata))
			{
				include 'includes/abp_home.php';
			}
			else
			{
				include 'includes/index_redirect.php';
			}
			break;
		case 'recalculate':
			if(UserdataHelper::IsSt($userdata))
			{
				include 'includes/abp_recalculate.php';
			}
			else
			{
				include 'includes/index_redirect.php';
			}
			break;
		case 'list_rules':
			if(UserdataHelper::IsSt($userdata))
			{
				include 'includes/abp_list_rules.php';
			}
			else
			{
				include 'includes/index_redirect.php';
			}
			break;
		case 'report':
			if(UserdataHelper::IsSt($userdata))
			{
				include 'includes/abp_report.php';
			}
			else
			{
				include 'includes/index_redirect.php';
			}
			break;
		case 'list_rules_player':
			include 'includes/abp_list_rules_player.php';
			break;
		case 'character_report':
			if(UserdataHelper::IsSt($userdata))
			{
				include 'includes/abp_character_report.php';
			}
			else
			{
				include 'includes/index_redirect.php';
			}
			break;
		case 'show_modifiers':	
			include 'includes/abp_show_modifiers.php';
			break;
		case 'add_rule':
			if(UserdataHelper::IsSt($userdata))
			{
				include 'includes/abp_add_rule.php';
				$template_file = 'empty_template';
			}
			else
			{
				include 'includes/index_redirect.php';
			}
			break;
		case 'add_rule_post':
			if(UserdataHelper::IsSt($userdata))
			{
				include 'includes/abp_add_rule_post.php';
				$template_file = 'empty_template';
			}
			else
			{
				include 'includes/index_redirect.php';
			}
			break;
		case 'delete_rule':
			if(UserdataHelper::IsSt($userdata))
			{
				include 'includes/abp_delete_rule.php';
				$template_file = 'empty_template';
			}
			else
			{
				include 'includes/index_redirect.php';
			}
			break;
		case 'edit_rule':
			if(UserdataHelper::IsSt($userdata))
			{
				include 'includes/abp_edit_rule.php';
				$template_file = 'empty_template';
			}
			else
			{
				include 'includes/index_redirect.php';
			}
			break;
		case 'edit_rule_post':
			if(UserdataHelper::IsSt($userdata))
			{
				include 'includes/abp_edit_rule_post.php';
				$template_file = 'empty_template';
			}
			else
			{
				include 'includes/index_redirect.php';
			}
			break;
		case 'get_abp_rule_list':
			if(UserdataHelper::IsSt($userdata))
			{
				include 'includes/abp_get_abp_rule_list.php';
				$template_file = 'empty_template';
			}
			else
			{
				include 'includes/index_redirect.php';
			}
			break;
		default:
			if(UserdataHelper::IsSt($userdata))
			{
				include 'includes/abp_home.php';
			}
			else
			{
				include 'includes/index_redirect.php';
			}
			break;
	}
}
else
{
	if(UserdataHelper::IsSt($userdata))
	{
		include 'includes/abp_home.php';
	}
}

/* @var twig $template */
$template->assign_vars(array(
	"PAGE_TITLE" => $page_title,
	"CSS_URL" => $css_url,
	"JAVA_SCRIPT" => $java_script,
	"USER_PANEL" => $user_panel,
	"MENU_BAR" => $menu_bar,
	"TOP_IMAGE" => $page_image,
	"PAGE_CONTENT" => $page_content,
	"EXTRA_TAGS" => $extra_tags,
    "FLASH_MESSAGE" => SessionHelper::GetFlashMessage(),
    "SERVER_TIME" => (microtime(true) + date('Z'))*1000,
	)
);

$template->set_custom_style('wantonwicked', array(ROOT_PATH . 'templates/'));
$template_name = $template_file . '.tpl';
// Output page
page_header($page_title, true);

$template->set_filenames(
    array(
        'body' => $template_name
    )
);

page_footer();
