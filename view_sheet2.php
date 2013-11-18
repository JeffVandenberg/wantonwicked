<?
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
include($phpbb_root_path . 'common.' . $phpEx);
include($phpbb_root_path . 'includes/functions_display.' . $phpEx);
include($phpbb_root_path . 'includes/functions_posting.' . $phpEx);
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
$css_url = "www.wantonwicked.net/wicked.css";
$top_image = "";
$page_content = "";
$java_script = "";
$body_params = "";
$extra_headers = "";

if(isset($_GET['action']))
{
	//echo $_GET['action']."<br>";
	switch($_GET['action'])
	{
		case 'create':
			include 'includes/view_sheet_create.php';
			break;
		case 'create_xp':
			include 'includes/view_sheet_create_xp.php';
			break;
	  case 'fragment':
	    include 'includes/view_sheet_fragment.php';
	    break;
		case 'get':
		  include 'includes/view_sheet_get.php';
		  break;
		case 'view_own':
			include 'includes/view_sheet_view_own.php';
			break;
		case 'view_own_xp':
			include 'includes/view_sheet_view_own_xp.php';
			break;
		case 'view_other':
			include 'includes/view_sheet_view_other.php';
			break;
		case 'view_other_xp':
			include 'includes/view_sheet_view_other_xp.php';
			break;
		case 'st_view':
			if(($userdata['is_asst'] && ($userdata['Cell_ID'] == $characterSheet['Cell_ID'])) ||
				($userdata['is_gm']   && ($userdata['Cell_ID'] == $characterSheet['Cell_ID'])) ||
				$userdata['is_head'] || $userdata['is_admin'])
			{
				include 'includes/view_sheet_st_view.php';
			}
			else
			{
				include 'includes/index_redirect.php';
			}
			
			break;
		case 'st_view_xp':
			if($userdata['is_asst'] || $userdata['is_gm'] || $userdata['is_head'] || $userdata['is_admin'])
			{
				include 'includes/view_sheet_st_view_xp.php';
			}
			else
			{
				include 'includes/index_redirect.php';
			}
			
			break;
		default:
		  include 'includes/index_redirect.php';
		  break;
	}
}

	$template->assign_vars(array(
	"PAGE_TITLE" => $page_title,
	"CSS_URL" => $css_url, 
	"JAVA_SCRIPT" => $java_script,
	"TOP_IMAGE" => $page_image, 
	"PAGE_CONTENT" => $page_content,
	"BODY_PARAMS" => $body_params,
	"EXTRA_HEADERS" => $extra_headers
	)
);

// initialize template
$template->set_custom_template('templates', 'main_layout');
$template->set_filenames(array(
		'body' => 'no_menu_layout.tpl')
);
$template->display('body');
?>

