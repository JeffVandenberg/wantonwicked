<?php 
#############################################
# Author: Pro Chatrooms
# Software: Pro Chatrooms
# Url: http://www.prochatrooms.com
# Support: support@prochatrooms.com
include("../includes/config.php");
#############################################
?>
<html>
<head>
<title>Wanton Wicked - Chat Help <?php echo $CONFIG['version'];?></title>
<link rel='stylesheet' type='text/css' href='../templates/<?php echo $CONFIG['template'];?>/style.css' />

<style>

h2
{
	font-family: Verdana, Arial;
	font-size: 20px;
	font-weight: bold;
	color: #fff;
}

table
{
	font-family: Verdana, Arial;
	font-size: 12px;
	font-style: normal;
	color: #fff;
}

a
{
	font-family: Verdana, Arial;
	font-size: 12px;
	font-style: bold;
	color: #fff;
}

a:link {text-decoration: none}
a:visited {text-decoration: none}
a:active {text-decoration: none}
a:hover {text-decoration: underline;}

</style>

</head>
<body class='body'>

<div id="logoContainer" class="logoContainer"></div>

<div style="height: 64px;"></div>

<div class="roomheader">
	Chat Room - User Help <?php echo $CONFIG['version'];?>
</div>

<a name="top"></a>

<table width="100%">
<tr><td width="150" valign="top">

<div>
<b>Basic Chat Help</b><br>
&#187; <a href="#sendmessage">Send Message</a><br>
&#187; <a href="#irc">Chat Commands</a><br>
&#187; <a href="#privatechat">Private Chat</a><br>
&#187; <a href="#autoscroll">Auto Scroll</a><br>
&#187; <a href="#servertime">Server Time</a><br>
&#187; <a href="#disconnect">Logout/Swap Characters</a><br>
<b>The Tool Bar</b><br>
&#187; <a href="#smilies">Smilies/Emoticons</a><br>
&#187; <a href="#ringbell">Alert</a><br>
&#187; <a href="#textstyles">Text Styles</a><br>
&#187; <a href="#avatars">Avatars</a><br>
&#187; <a href="#sfx">Sound Effects</a><br>
&#187; <a href="#clear">Clear Screen</a><br>
&#187; <a href="#options">Options</a><br>
&#187; <a href="#transcripts">Transcripts</a><br>
&#187; <a href="#sharefiles">Share Files</a><br>
<b>User List</b><br>
&#187; <a href="#roomlist">Create a Room</a><br>
&#187; <a href="#userlist">Users/Room List</a><br>
</div>

</td><td>

<a name="sendmessage"></a>
<table border='0' width="100%">
<tr><td>
<div class="roomheader"><b>:: Send Message</b><br></div>
<img src="images/send.png"><br>
To send a message to other chat room users, type your message in the message box as shown above and click the 'Send' button.
</td></tr>
</table>

<table width="100%" border='0'>
<tr><td align="right">
<a class="nav_top" href="#top">[back to top]</a>
</td></tr>
</table>

<a name="irc"></a>
<table border='0' width="100%">
<tr><td>
<div class="roomheader"><b>:: IRC Type Commands</b><br><br></div>

1) /dice roll<br>
In the message bar, type: /dice roll "<i>action</i>" <i>dice</i> (be sure to include the quotes around your action). This command allows you to simulate rolling dice in the chat room. For example, type <i>/dice roll "attack monster" 7</i> to generate your results. <br>
You can also add a dice modifier to the roll. For example, Willpower: /dice roll "action" dice <i>WP</i><br><br>
1a) /dice roll initiative<br>
In the message bar, type: <i>/dice roll initiative</i> (the word, not your initiative modifier). This command sllows you to simulate rolling initiative and will automatically take into account your initiative modifier if you are logged in as a character.<br><br>
1b) /dice list<br>
In the message bar, type <i>/dice list</i> to display the most recent dice rolls from the chat.<br><br>/dice and /dice help will pop up a reminder for this syntax.<br><br>
2) /me <i>message</i><br>
In the message bar, type '/me <i>message</i>' (without the quotes). This command allows you to add fun action messages. Example: Type '/action <i>files around the room</i>' (without the quotes) and a message will appear in the main chat window informing other users that '<i>username</i> flies around the room'. <br><br>
3) /broadcast <i>message</i> (admins only)<br>
In the message bar, type '/broadcast <i>message</i>' (without the quotes). This command allows you to send announcements to all chat users in every chat room (these will be displayed both on screen and by pop up notifications).<br><br>
4) /play <i>sound</i><br>
In the message bar, type '/play <i>sound</i>' (without the quotes). This command allows you to send a play a sound available in the sounds list (click the icon speaker icon to see a list of available sounds). For example, type '/play giggle' (without quotes) to hear and send a giggle sound effect to all users in the room.<br><br>
</td></tr>
</table>

<table width="100%" border='0'>
<tr><td align="right">
<a class="nav_top" href="#top">[back to top]</a>
</td></tr>
</table>

<a name="privatechat"></a>
<table border='0' width="100%">
<tr><td>
<div class="roomheader"><b>:: Private Chat</b><br></div>
To send private messages, click a username in the userlist and select the option to 'Private Chat'. This will open a new private chat window.</td></tr>
</table>

<table width="100%" border='0'>
<tr><td align="right">
<a class="nav_top" href="#top">[back to top]</a>
</td></tr>
</table>

<a name="autoscroll"></a>
<table border='0' width="100%">
<tr><td>
<div class="roomheader"><b>:: Auto Scroll</b><br></div>
<img src="images/autoscroll.png"><br>
To enable/disable auto scrolling of the chat screen, click the box next to the text 'Auto Scroll'.
</td></tr>
</table>

<table width="100%" border='0'>
<tr><td align="right">
<a class="nav_top" href="#top">[back to top]</a>
</td></tr>
</table>

<a name="servertime"></a>
<table border='0' width="100%">
<tr><td>
<div class="roomheader"><b>:: Server Time</b><br><br></div>
The chat game takes place on United States Eastern Standard Time, so the server is set to this time zone. Server time will help you schedule scenes since we have players from all over the world.<br><br>
</td></tr>
</table>

<table width="100%" border='0'>
<tr><td align="right">
<a class="nav_top" href="#top">[back to top]</a>
</td></tr>
</table>

<a name="disconnect"></a>
<table border='0' width="100%">
<tr><td>
<div class="roomheader"><b>:: Logout/Swap Characters</b><br></div>
<img src="images/logout.png"><br>
This feature allows you to correctly log out of chat. It's important to use this button instead of simply closing the chat window because your login ID is cookie based and will be remembered next time you try to log in - even if you attempt to log in a different character.<br>
To change characters/login names, click the Logout button. You will be redirected to the home page. Go to Game/Chat Interface under the Tools menu and login the new character or login OOC.
</td></tr>
</table>

<table width="100%" border='0'>
<tr><td align="right">
<a class="nav_top" href="#top">[back to top]</a>
</td></tr>
</table>

<a name="smilies"></a>
<table border='0' width="100%">
<tr><td>
<div class="roomheader"><b>:: Smilies/Emoticons</b><br></div>
<img src="images/grin_small.png"><br>
This feature allows you to add smilies/emoticons to your chat messages. To add a smilie/emoticon to your message, click the smilie/emoticon button and a window will appear with a preset selection of smilies/emoticons. Click the smilie/emoticon you would like to add to your message.</td></tr>
</table>

<table width="100%" border='0'>
<tr><td align="right">
<a class="nav_top" href="#top">[back to top]</a>
</td></tr>
</table>

<a name="ringbell"></a>
<table border='0' width="100%">
<tr><td>
<div class="roomheader"><b>:: Alert</b><br></div>
<img src="images/bell_small.png"><br>
This feature allows you to ring a bell to attract the attention of other room users. Please limit the use!
</td></tr>
</table>

<table width="100%" border='0'>
<tr><td align="right">
<a class="nav_top" href="#top">[back to top]</a>
</td></tr>
</table>

<a name="textstyles"></a>
<table border='0' width="100%">
<tr><td>
<div class="roomheader"><b>:: Text Styles</b><br></div>
<img src="images/styles_small.png"><br>
This feature allows you to change your text style (font and colors). Select an option by clicking the icon in the chat room.</td></tr>
</table>

<table width="100%" border='0'>
<tr><td align="right">
<a class="nav_top" href="#top">[back to top]</a>
</td></tr>
</table>

<a name="avatars"></a>
<table border='0' width="100%">
<tr><td>
<div class="roomheader"><b>:: Avatars</b><br></div>
<img src="images/avatars_small.png"><br>
This feature allows you to display an avatar next to your name and chat messages. To choose an avatar, click the avatar button and a window will appear with a preset selection of avatars. Click the avatar you would like to use. Each avatar represents a venue, or sub-venue group (such as clan, tribe, court, etc.).<br>
Some icons you see in chat have specific, limited uses:<br>
<table border='1'>
<tr><td bgcolor="#dddddd"><img src="images/entry.png"></td><td>This avatar shows up only when a member is joining a room.</td></tr>
<tr><td bgcolor="#dddddd"><img src="images/exit.png"></td><td>This avatar only shows up when a member has left their current room to join another.</td></tr>
<tr><td bgcolor="#dddddd"><img src="images/loggedout.png"></td><td>This avatar only shows up when a member has logged out of chat.</td></tr>
<tr><td bgcolor="#dddddd"><img src="images/admin.png"></td><td>This avatar is only worn by users with Admin status.</td></tr>
<tr><td bgcolor="#dddddd"><img src="images/st.png"></td><td>This avatar is only worn by users with Storyteller status.</td></tr>
<tr><td bgcolor="#dddddd"><img src="images/wiki.png"></td><td>This avatar is only worn by users with Wiki Manager status.</td></tr>
<tr><td bgcolor="#dddddd"><img src="images/unsanctioned.png"></td><td>This is the default avatar for a new character that has not been sanctioned.</td></tr>
<tr><td bgcolor="#dddddd"><img src="images/desanctioned.png"></td><td>This is the default avatar for a character who was desanctioned.</td></tr>
<tr><td bgcolor="#dddddd"><img src="images/ooc.png"></td><td>This is the default avatar for a user who logs in with their OOC id.</td></tr>
</table>
<br><br>
</table>

<table width="100%" border='0'>
<tr><td align="right">
<a class="nav_top" href="#top">[back to top]</a>
</td></tr>
</table>

<a name="sfx"></a>
<table border='0' width="100%">
<tr><td>
<div class="roomheader"><b>:: Sound Effects (SFX)</b><br></div>
<img src="images/sound_small.png"><br>
This feature allows you use sound effects in the chat room. To play a sound effect, click the 'SFX' icon and click on a sound effect.
</td></tr>
</table>

<table width="100%" border='0'>
<tr><td align="right">
<a class="nav_top" href="#top">[back to top]</a>
</td></tr>
</table>

<a name="clear"></a>
<table border='0' width="100%">
<tr><td>
<div class="roomheader"><b>:: Clear Screen</b><br></div>
<img src="images/eraser.png"><br>
This button allows you to clear the chat window of any text.
</td></tr>
</table>

<table width="100%" border='0'>
<tr><td align="right">
<a class="nav_top" href="#top">[back to top]</a>
</td></tr>
</table>

<a name="options"></a>
<table border='0' width="100%">
<tr><td>
<div class="roomheader"><b>:: Options</b><br></div>
<img src="images/edit.png"><br>
This button allows you to set your chat room preferences.<br>
<img src="images/options.png"><br>
a) Private Chat - Allow other room users to talk to you in private chat windows<br>
b) Allow Webcam Access - Allow users to view your webcam without asking for permission<br>
c) Play Entry/Exit Sounds - Play a sound when a user enters or leaves the chat room<br>
d) Play New Message Sounds- Play a sound everytime a new message is shown in the chat window<br>
e) Play SFX - Play any SFX in the chat room<br>
</td></tr>
</table>

<table width="100%" border='0'>
<tr><td align="right">
<a class="nav_top" href="#top">[back to top]</a>
</td></tr>
</table>

<a name="transcripts"></a>
<table border='0' width="100%">
<tr><td>
<div class="roomheader"><b>:: Transcripts</b><br></div>
<img src="images/transcripts_small.png"><br>
This feature allows you to view a history of all current chat messages since you entered your current room.</td></tr>
</table>

<table width="100%" border='0'>
<tr><td align="right">
<a class="nav_top" href="#top">[back to top]</a>
</td></tr>
</table>

<a name="sharefiles"></a>
<table border='0' width="100%">
<tr><td>
<div class="roomheader"><b>:: Share Files (Admin, Storyteller only)</b><br></div>
<img src="images/share.png"><br>
This feature allows you share photos/images with other chat room users. To share your image, click the share button and a window will appear prompting you to upload your image. You can share your image with all users or privately during conversations.
<br><br>
</td></tr>
</table>

<table width="100%" border='0'>
<tr><td align="right">
<a class="nav_top" href="#top">[back to top]</a>
</td></tr>
</table>

<a name="roomlist"></a>
<table border='0' width="100%">
<tr><td>
<div class="roomheader"><b>:: Create a Room</b><br></div>
<img src="images/rooms.png"><br>
To create your own room, click the green + icon next to the select room list.
</td></tr>
</table>

<table width="100%" border='0'>
<tr><td align="right">
<a class="nav_top" href="#top">[back to top]</a>
</td></tr>
</table>

<a name="userlist"></a>
<table border='0' width="100%">
<tr><td>
<div class="roomheader"><b>:: Users/Room List</b><br></div>
This section displays details about the chat room you are in and all other room users. To enter a different chat room, click on the room select box which appears the userlist and select the room you wish to join. Some rooms may require a password to join.
<br><br>
</td></tr>
</table>

<table width="100%" border='0'>
<tr><td align="right">
<a class="nav_top" href="#top">[back to top]</a>
</td></tr>
</table>


<!--
DO NOT REMOVE THE COPYRIGHT NOTICE LINE BELOW UNLESS YOUR LICENCE TYPE PERMITS THIS.
REMOVAL OF THE COPYRIGHT INFORMATION WITHOUT PERMISSION WILL TERMINATE YOUR LICENCE.
-->

<?php if(!file_exists("../rembrand/index.php")){?>
	<div style="text-align:center;">
		&copy;<a class="copyright" href="http://www.prochatrooms.com" target="_blank" alt="Powered By ProChatRooms.com" title="Powered By ProChatRooms.com">Pro Chat Rooms</a> <?php echo $CONFIG['version'];?> 
	</div>
<?php }?>

</td></tr>
</table>


</body>
</html>