<?
include 'cgi-bin/start_of_page.php';
include 'cgi-bin/makeDots.php';

// perform required includes
define('IN_PHPBB', true);
$phpbb_root_path = './forum/';
include($phpbb_root_path . 'extension.inc');
include($phpbb_root_path . 'common.'.$phpEx);

$template = new Template("/templates/");

// check page actions
$page_title = "";
$css_url = "ww4_v2.css";
$menu_bar = "";
$top_image = "";
$page_content = "";
$java_script = "";

if(isset($_GET['action']))
{
	//echo $_GET['action']."<br>";
	switch($_GET['action'])
	{
		default:
	}
}
else
{
	$page_title = "Wanton Wicked Character Creation";
	
	$page_content = <<<EOQ
EOQ;
}



	$template->assign_vars(array(
	"PAGE_TITLE" => $page_title,
	"CSS_URL" => $css_url, 
	"JAVA_SCRIPT" => $java_script,
	"TOP_IMAGE" => $page_image, 
	"PAGE_CONTENT" => $page_content
	)
);

// initialize template
$template->set_filenames(array(
		'body' => 'templates/main_ww4.tpl')
);
$template->pparse('body');
?>

