<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<HTML>
<HEAD>
<TITLE>OOC Wanton Wicked Login</TITLE>
</HEAD>
<BODY>
<?
$name=trim(stripslashes($_POST['name']));
$name=str_replace(" ", "", $name);
if((strtolower($name) == "jeffv") && ($_SERVER['HTTP_X_CLUSTER_CLIENT_IP'] != "12.217.210.232"))
{
	$name = "I am a Douche. Kick Me!";
}
$name = ($name == "") ? "Guest " . mt_rand(10000, 99999) : $name;
$name .= " OOC";

$applet = <<<EOQ
<script type="text/javascript">/*<![CDATA[*/
   var addonchat = { 
      server:16, 
			id:464562, 
			width:"800",
      height:"600", 
			language:"en"
		}
		var addonchat_param = {
			username:"$name",
			password:"PlanetStrike0912",
			autologin:1
		}
   /* ]]> */</script>
   <script type="text/javascript"
   src="http://client16.addonchat.com/chat.js"></script><noscript>
   To enter this chat room, please enable JavaScript in your web
   browser. This <a href="http://www.addonchat.com/">Chat
   Software</a> requires Java: <a href="http://www.java.com/">Get
   Java Now</a>
</noscript>
EOQ;

Echo "If you have any problems logging in, particularly the applet not appearing, please send an email to jeffvandenberg@gmail.com<br>";
Echo "You're logging in as $name<br>\n";
?>
<?=$applet?>
</BODY>
</HTML>