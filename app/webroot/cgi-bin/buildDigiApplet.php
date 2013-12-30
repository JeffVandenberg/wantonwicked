<?
function buildDigiApplet ( $login_name, $site, $description, $exit_line, $icon, $age, $sex, $url, $realname = "", $buddies = "", $menu_locations = "", $menu_items = "" )
{
	$sex = strtolower($sex);
	if(strlen($login_name) > 35)
	{
  	$login_name = substr($login_name, 0, 35);
	}
	
$applet_code = <<<EOQ
<APPLET NAME='DigiChat' 
  CODEBASE='http://fiveringsonline.com/DigiChat/DigiClasses/' 
  CODE='com.diginet.digichat.client.DigiChatApplet' 
  HEIGHT=4 WIDTH=4 ALIGN='MIDDLE'
  ARCHIVE="Client.jar" MAYSCRIPT>
  <param name="SiteID" value="$site">
  <param name="realname" value="$realname">
  <param name="nickname" value="$login_name">
  <param name="age" value="$age">
  <param name="gender" value="$sex">
  <param name="url" value="$url">
  <param name="exitmessage" value="$exit_line">
  <param name="comments" value="$description">
  <param name="iconID" value="$icon">
	<param name="ports" value="8396,58396,110,443">
  <PARAM NAME=signed VALUE=true>
</APPLET>
EOQ;

$extra = <<<EOQ
EOQ;

return $applet_code;
}
?>