<?php
$page_title = "Zombie Login";

$login_name = "Zombie " . mt_rand(10000,99999);

$page_content .= <<<EOQ
<br>
<input type="button" value="Respawn" onClick="window.document.location.reload();">
EOQ;
