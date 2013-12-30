<?php
$page_title = "OOC Login";
$contentHeader = $page_title;

$login_name = (!empty($_GET['ooc_login_name'])) ? htmlspecialchars($_GET['ooc_login_name']) . " OOC": "Guest " . mt_rand(10000,99999);


//$applet = buildDigiApplet($login_name, 1001, "Guest to Wanton Wicked", "I Logged in OOC", 1006, 0, "", "", $userdata['username'], $buddy_list);
$applet = buildAddOnChatApplet($login_name);
$page_content .= <<<EOQ
$applet
<br>
<input type="button" value="Relogin as $login_name" onClick="window.document.location.reload();">
EOQ;
