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

h1 {
	font-family:"Century Gothic",Verdana, Geneva, sans-serif;
	font-size:24px;
	font-weight:normal;
	text-transform:uppercase;
	border-bottom: 3px double #000000;
}

h2 {
	font-family:"Century Gothic",Verdana, Geneva, sans-serif;
	font-size:18px;
	font-weight:normal;
	text-transform:uppercase;
	border-bottom: 1px solid #898989;
}

h3 {
	font-family:"Century Gothic",Verdana, Geneva, sans-serif;
	font-size:16px;
	font-weight:normal;
	font-style:italic;
	text-transform:uppercase;
}

h4 {
	font-family:"Century Gothic",Verdana, Geneva, sans-serif;
	font-size:14px;
	font-weight:normal;
	/*font-style:italic;*/
	text-transform:uppercase;
}

table
{
	font-family: Verdana, Arial;
	font-size: 12px;
	font-style: normal;
	color: #000;
}

a
{
	font-family: Verdana, Arial;
	font-size: 12px;
	font-style: bold;
	color: #000;
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

<div class="chatContainer" style="width:90%; margin-left: 5%; margin-right: 5%;">
<h1>Chat Room - User Help <?php echo $CONFIG['version'];?></h1>
<a name="top"></a>

<table width="100%">
<tr><td width="200" valign="top">

<div>
<div class="roomheader"><b>Basic Chat Help</b></div><br>
&#187; <a href="#sendmessage">Send Message</a><br>
&#187; <a href="#irc">Chat Commands</a><br>
&#187; <a href="#privatechat">Private Chat</a><br>
&#187; <a href="#autoscroll">Auto Scroll</a><br>
&#187; <a href="#servertime">Server Time</a><br>
&#187; <a href="#disconnect">Logout/Swap Characters</a><br>
<br>
<br>
<div class="roomheader"><b>The Tool Bar</b></div><br>
&#187; <a href="#smilies">Smilies/Emoticons</a><br>
&#187; <a href="#ringbell">Alert</a><br>
&#187; <a href="#textstyles">Text Styles</a><br>
&#187; <a href="#avatars">Avatars</a><br>
&#187; <a href="#sfx">Sound Effects</a><br>
&#187; <a href="#clear">Clear Screen</a><br>
&#187; <a href="#options">Options</a><br>
&#187; <a href="#transcripts">Transcripts</a><br>
&#187; <a href="#sharefiles">Share Files</a><br>
<br>
<br>
<div class="roomheader"><b>User List</b></div><br>
&#187; <a href="#roomlist">Create a Room</a><br>
&#187; <a href="#userlist">Users/Room List</a><br>
<br><br>
</div>

</td><td>
<h2>Basic Chat Help</h2>
<a name="sendmessage"></a>
<table border='0' style="border-collapse:collapse;" width="100%">
<tr><td colspan="2"><h3>Send Message</h3></td></tr>
<tr><td width="100px"><img src="images/send.png"></td>
<td valign="top"><p>To send a message to other chat room users, type your message in the message box as shown above and click the 'Send' button or hit Enter.</p></td></tr>
<tr><td colspan="2" align="right">
<a class="nav_top" href="#top">[back to top]</a>
</td></tr>
<a name="irc"></a>
<tr><td colspan="2"><h3>IRC Type Commands</h3></td></tr>
<tr><td width="100px"></td><td>
<ol>
<li><strong>/dice roll</strong>
<p>In the message bar, type: /dice roll "<i>action</i>" <i>dice</i> (be sure to include the quotes around your action). This command allows you to simulate rolling dice in the chat room. For example, type <i>/dice roll "attack monster" 7</i> to generate your results. <br>
You can also add a dice modifier to the roll. For example, Willpower: /dice roll "action" dice <i>WP</i></p></li>
  <ul>
  <li><strong>Modifiers</strong>
  <ul>
  <li>Willpower<br>
      <em>/dice roll &quot;action&quot; dice WP</em></
      <li>Blood (vampire only)<br>
      <em>/dice roll &quot;action&quot; dice blood</em></
    <li>Chance<br>
      <em>/dice roll &quot;action&quot; 1 chance</em></li>
    <li>Rote<br>
    <em>/dice roll &quot;action&quot; dice rote</em></li>
    <li>8-again<br>
    <em>/dice roll &quot;action&quot; dice 8again</em></li>
    <li>9-again<br>
    <em>/dice roll &quot;action&quot; dice 9again</em></li>
    <li>No Reroll<br>
    <em>This feature is only available on the site Dice Roller at this time.</em></li>
    <li>1s Remove<br>
    <em>This feature is only available on the site Dice Roller at this time.</em></li>
    <li>Multiple Modifiers: simply add on to the end<br>
    <em>/dice roll &quot;action&quot; dice rote 8again 9again</em></li>
  </ul>
  </li>
  <li><strong>/dice roll initiative or /dice roll init</strong><br>In the message bar, type: <i>/dice roll initiative</i> (the word, not your initiative modifier). This command sllows you to simulate rolling initiative and will automatically take into account your initiative modifier if you are logged in as a character. If your base initiative modifier has been adjusted by an ability or merit, type: /dice roll initiative +<i>number</i>
  </li>
    <li><strong>/dice list</strong><br>In the message bar, type <i>/dice list</i> to display the most recent dice rolls from the chat.<br><br>/dice and /dice help will pop up a reminder for this syntax.</li></ul>
</li>
<li><strong>/me <i>message</i></strong><p>
In the message bar, type '/me <i>message</i>' (without the quotes). This command allows you to add fun action messages. Example: Type '/action <i>files around the room</i>' (without the quotes) and a message will appear in the main chat window informing other users that '<i>username</i> flies around the room'.</p></li>
<li><strong>/broadcast <i>message</i></strong> (admins only)
<p>In the message bar, type '/broadcast <i>message</i>' (without the quotes). This command allows you to send announcements to all chat users in every chat room (these will be displayed both on screen and by pop up notifications).</p></li>
<li><strong>/play <i>sound</i></strong>
<p>In the message bar, type '/play <i>sound</i>' (without the quotes). This command allows you to send a play a sound available in the sounds list (click the icon speaker icon to see a list of available sounds). For example, type '/play giggle' (without quotes) to hear and send a giggle sound effect to all users in the room.</p></li></ol>
</td></tr>
<tr><td colspan="2" align="right"><a class="nav_top" href="#top">[back to top]</a></td></tr>
<a name="privatechat"></a>
<tr><td colspan="2"><h3>Private Chat</h3></td></tr>
<tr><td width="100" align="center" valign="top"></td><td><p>To send private messages, click a username in the userlist and select the option to 'Private Chat'. This will open a new private chat window.</p></td></tr>
<tr><td colspan="2" align="right">
<a class="nav_top" href="#top">[back to top]</a>
</td></tr>
<a name="autoscroll"></a>
<tr><td colspan="2"><h3>Auto Scroll</h3></td></tr>
<tr><td width="100" align="center" valign="top">&nbsp;</td>
<td>
<p>To enable/disable auto scrolling of the chat screen, click the box next to the text 'Auto Scroll'.</p>
</td></tr>
<tr><td colspan="2" align="right">
<a class="nav_top" href="#top">[back to top]</a>
</td></tr>
<a name="servertime"></a>
<tr><td colspan="2"><h3>Server Time</h3></td></tr>
<tr><td width="100" align="center" valign="top"></td>
<td><p>The chat game takes place on United States Eastern Standard Time, so the server is set to this time zone. Server time will help you schedule scenes since we have players from all over the world.</p></td></tr>
<tr><td colspan="2"align="right"><a class="nav_top" href="#top">[back to top]</a></td></tr>
<a name="disconnect"></a>
<tr><td colspan="2"><h3>Logout/Swap Characters</h3></td></tr>
<tr><td width="100" align="center" valign="top"><img src="images/logout.png"></td><td>
This feature allows you to correctly log out of chat. It's important to use this button instead of simply closing the chat window because your login ID is cookie based and will be remembered next time you try to log in - even if you attempt to log in a different character.<br>
To change characters/login names, click the Logout button. You will be redirected to the home page. Go to Game/Chat Interface under the Tools menu and login the new character or login OOC.
</td></tr>
<tr><td colspan="2"align="right">
<a class="nav_top" href="#top">[back to top]</a>
</td></tr>
</table>

<h2>The Tool Bar</h2>
<a name="smilies"></a>
<table border='0' width="100%">
<tr><td colspan="2"><h3>Smilies/Emoticons</h3></td></tr>
<tr><td width="100" align="center" valign="top"><img src="images/grin_small.png"></td><td>
<p>This feature allows you to add smilies/emoticons to your chat messages. To add a smilie/emoticon to your message, click the smilie/emoticon button and a window will appear with a preset selection of smilies/emoticons. Click the smilie/emoticon you would like to add to your message.</p></td></tr>
<tr><td colspan="2"align="right">
<a class="nav_top" href="#top">[back to top]</a>
</td></tr>
<a name="ringbell"></a>
<tr><td colspan="2"><h3>Alert</h3></td></tr>
<tr><td width="100" align="center" valign="top"><img src="images/bell_small.png"></td><td><p>
This feature allows you to ring a bell to attract the attention of other room users. Please limit the use!</p>
</td></tr>
<tr><td colspan="2"align="right">
<a class="nav_top" href="#top">[back to top]</a>
</td></tr>
<a name="textstyles"></a>
<tr><td colspan="2"><h3>Text Styles</h3></td></tr>
<tr><td width="100" align="center" valign="top"><img src="images/styles_small.png"></td><td><p>
This feature allows you to change your text style (font and colors). Select an option by clicking the icon in the chat room.</p></td></tr>
<tr><td colspan="2" align="right">
<a class="nav_top" href="#top">[back to top]</a>
</td></tr>
</table>

<a name="avatars"></a>
<table border='0' width="100%">
<tr><td colspan="2"><h3>Avatars</h3></td><tr>
<td width="100" align="center" valign="top"><img src="images/avatars_small.png"></td>
<td><p>
This feature allows you to display an avatar next to your name and chat messages. To choose an avatar, click the avatar button and a window will appear with a preset selection of avatars. Click the avatar you would like to use. Each avatar represents a venue, or sub-venue group (such as clan, tribe, court, etc.).</p></td></tr>
<tr><td colspan="2"><p>Some icons you see in chat have specific, limited uses:<br></p></td></tr>
<tr><td width="100" align="center" valign="top"><img src="images/entry.png"></td><td>This avatar shows up only when a member is joining a room.</td></tr>
<tr><td width="100" align="center" valign="top"><img src="images/exit.png"></td><td>This avatar only shows up when a member has left their current room to join another.</td></tr>
<tr><td width="100" align="center" valign="top"><img src="images/loggedout.png"></td><td>This avatar only shows up when a member has logged out of chat.</td></tr>
<tr><td width="100" align="center" valign="top"><img src="images/admin.png"></td><td>This avatar is only worn by users with Admin status.</td></tr>
<tr><td width="100" align="center" valign="top"><img src="images/st.png"></td><td>This avatar is only worn by users with Storyteller status.</td></tr>
<tr><td width="100" align="center" valign="top"><img src="images/wiki.png"></td><td>This avatar is only worn by users with Wiki Manager status.</td></tr>
<tr><td width="100" align="center" valign="top"><img src="images/unsanctioned.png"></td><td>This is the default avatar for a new character that has not been sanctioned.</td></tr>
<tr><td width="100" align="center" valign="top"><img src="images/desanctioned.png"></td><td>This is the default avatar for a character who was desanctioned.</td></tr>
<tr><td width="100" align="center" valign="top"><img src="images/ooc.png"></td><td>This is the default avatar for a user who logs in with their OOC id.</td></tr>
<tr><td colspan="2"align="right">
<a class="nav_top" href="#top">[back to top]</a>
</td></tr>
<a name="sfx"></a>
<tr><td colspan="2"><h3>Sound Effects (SFX)</h3></td></tr>
<tr><td width="100" align="center" valign="top"><img src="images/sound_small.png"></td>
<td><p>This feature allows you use sound effects in the chat room. To play a sound effect, click the 'SFX' icon and click on a sound effect.</p>
</td></tr>
<tr><td colspan="2"align="right">
<a class="nav_top" href="#top">[back to top]</a>
</td></tr>
<a name="clear"></a>
<tr><td colspan="2"><h3>Clear Screen</h3></td></tr>
<tr><td width="100" align="center" valign="top"><img src="images/eraser.png"></td><td><p>
This button allows you to clear the chat window of any text.</p>
</td></tr>
<tr><td colspan="2" align="right">
<a class="nav_top" href="#top">[back to top]</a>
</td></tr>
<a name="options"></a>
<tr><td colspan="2"><h3>Options</h3></td></tr>
<tr><td width="100" align="center" valign="top"><img src="images/edit.png"></td><td>
This button allows you to set your chat room preferences.<br>
<img src="images/options.png"><br>
a) Private Chat - Allow other room users to talk to you in private chat windows<br>
b) Allow Webcam Access - Allow users to view your webcam without asking for permission<br>
c) Play Entry/Exit Sounds - Play a sound when a user enters or leaves the chat room<br>
d) Play New Message Sounds- Play a sound everytime a new message is shown in the chat window<br>
e) Play SFX - Play any SFX in the chat room<br>
</td></tr>
<tr><td colspan="2" align="right">
<a class="nav_top" href="#top">[back to top]</a>
</td></tr>
<a name="transcripts"></a>
<tr><td colspan="2"><h3>Transcripts</h3></td></tr>
<tr><td width="100" align="center" valign="top"><img src="images/transcripts_small.png"></td><td><p>
This feature allows you to view a history of all current chat messages since you entered your current room.</p></td></tr>
<tr><td colspan="2"align="right">
<a class="nav_top" href="#top">[back to top]</a>
</td></tr>
<a name="sharefiles"></a>
<tr><td colspan="2"><h3>Share Files (Admin, Storyteller only)</h3></td></tr>
<tr><td width="100" align="center" valign="top"><img src="images/share.png"></td><td><p>
This feature allows you share photos/images with other chat room users. To share your image, click the share button and a window will appear prompting you to upload your image. You can share your image with all users or privately during conversations.</p>
</td></tr>
<tr><td colspan="2" align="right">
<a class="nav_top" href="#top">[back to top]</a>
</td></tr>
</table>
<h2>User List</h2>
<a name="roomlist"></a>
<table width="100%" border='0'>
<tr><td colspan="2"><h3>Create a Room</h3></td></tr>
<tr>
  <td width="100" align="center" valign="top">&nbsp;</td><td><p>To create your own room, click the green + icon next to the select room list.</p>
</td></tr>
<tr><td colspan="2"align="right">
<a class="nav_top" href="#top">[back to top]</a>
</td></tr>
<a name="userlist"></a>
<tr><td colspan="2"><h3>Users/Room List</h3></td></tr>
<tr><td width="100" align="center" valign="top"></td><td><p>This section displays details about the chat room you are in and all other room users. To enter a different chat room, click on the room select box which appears the userlist and select the room you wish to join. Some rooms may require a password to join.</p></td></tr>
<tr><td colspan="2"><p>Rooms are organized by District initials (in parenthesis) alphabetically and then by other world locations [in brackets] alphabetically. Rooms also have specific icons to identify their purpose.</p></td></tr>
<tr>
  <td width="100" align="center" valign="top"><img src="images/open.png"></td>
  <td><p><strong>Open</strong> - </p>
    <p>This is a general room that is open to anyone who chooses to enter. In some cases, a Storyteller may spontaneously start a scene in a general room. If you see a Storyteller in an Open room, it's generally a good idea to send them a private message to see if there is a scene, and if they're still accepting players.</p></td></tr>
<tr><td width="100" align="center" valign="top"><img src="images/scene.png"></td><td><p><strong>Scene room</strong> - </p>
  <p>This is a room that has been created by a Storyteller specifically for a scene. It is almost always closed to members of that Storyteller's venue. You should send a private message before entering a Scene room if you weren't invited or scheduled to participate.</p></td></tr>
<tr><td width="100" align="center" valign="top"><img src="images/private.png"></td><td><p><strong>Private</strong> - </p>
  <p>This is a room which may be Storyteller or Player created but it is not open to anyone who is not invited.</p></td></tr>
<tr><td width="100" align="center" valign="top"><img src="images/changelingloc.png"></td><td><p><strong>Changeling Location</strong> - </p>
  <p>This is a room location that is exclusive to Changelings. Characters from other venues should not be in this room unless they are able to by the rules of the venue and brought in by a Changeling character.</p></td></tr>
<tr><td width="100" align="center" valign="top"><img src="images/geistloc.png"></td><td><p><strong>Geist Location</strong> - </p>
  <p>This is a room location that is exclusive to Changelings. Characters from other venues should not be in this room unless they are able to by the rules of the venue and brought in by a Geist character.</p></td></tr>
<tr><td width="100" align="center" valign="top"><img src="images/mageloc.png"></td><td><p><strong>Mage Location</strong> - </p>
  <p>This is a room location that is exclusive to Changelings. Characters from other venues should not be in this room unless they are able to by the rules of the venue and brought in by a Mage character.</p></td></tr>
<tr><td width="100" align="center" valign="top"><img src="images/mortalloc.png"></td><td><p><strong>Mortal Location</strong> - </p>
    <p>This is a room location that is exclusive to Changelings. Characters from other venues should not be in this room unless they are able to by the rules of the venue and brought in by a Mortal character.</p></td></tr>
<tr><td width="100" align="center" valign="top"><img src="images/vampireloc.png"></td><td><strong>Vampire Location</strong> - 
<p>This is a room location that is exclusive to Changelings. Characters from other venues should not be in this room unless they are able to by the rules of the venue and brought in by a Changeling character.</p></td></tr>
<tr><td width="100" align="center" valign="top"><img src="images/werewolfloc.png"></td><td><strong>Werewolf Location</strong> - 
<p>This is a room location that is exclusive to Changelings. Characters from other venues should not be in this room unless they are able to by the rules of the venue and brought in by a Changeling character.</p></td></tr>
<tr><td colspan="2" align="right">
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
</div>

</body>
</html>