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
$css_url = "www.wantonwicked.net/wicked.css";
$top_image = "";
$page_content = "";
$java_script = "";
$body_params = "";
$extra_headers = "";
$render_in_template = false;

switch($_GET['action'])
{
  case 'view':
    include 'includes/map_view.php';
    $render_in_template = true;
    break;
    
  case 'view2':
    include 'includes/map_view2.php';
    $render_in_template = true;
    break;
    
  case 'add':
    if($userdata['is_gm'])
    {
      include 'includes/map_add.php';
    }
    break;
    
  case 'modify':
    if($userdata['is_gm'])
    {
      include 'includes/map_modify.php';
    }
    break;
    
  case 'list':
    include 'includes/map_list.php';
    break;
    
  case 'remove':
    if($userdata['is_gm'])
    {
      include 'includes/map_remove.php';
    }
    break;
  default;
    break;
}

if($render_in_template)
{
  $template->set_custom_template('templates', 'main_layout');
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
  $template->set_filenames(array(
  		'body' => 'no_menu_layout.tpl')
  );
  $template->display('body');
}
?>