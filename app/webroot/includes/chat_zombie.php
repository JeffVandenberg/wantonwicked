<?php
$page_title = "Zombie Login";

$login_name = "Zombie " . mt_rand(10000,99999);

$applet = buildAddOnChatApplet($login_name);
$page_content .= <<<EOQ
$applet
<br>
<input type="button" value="Respawn" onClick="window.document.location.reload();">
EOQ;
?>