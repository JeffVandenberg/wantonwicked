<?php
global $userdata;
// check if they are an ST
$gamemaster_link = "";

$supporter = "";
if($userdata['is_admin']) {
    $supporter = <<<EOQ
<a href="support.php?action=manage">Manage Support</a><br />
EOQ;

}

if ($userdata['is_gm'] || $userdata['is_asst'] || $userdata['is_head']) {
    $gamemaster_link = <<<EOQ
<a href="/storyteller_index.php" target="_blank">Storyteller Tools</a><br>
EOQ;
}


// check if user is logged in
if ($user->data['user_id'] != ANONYMOUS) {
    $menu_bar = <<<EOQ
<div class="menu-header">Tools</div>
<a href="/">Home</a><br />
<a href="/wiki/">Wiki</a><br>
<a href="/forum/index.php">Forums</a><br>
<a href="/chat.php">Game/Chat Interface</a><br>
<!--<a href="/events.php">Event Calendar</a><br />-->
$gamemaster_link
$supporter
<a href="/support.php">Supporters</a><br />
<a href="/index.php?action=storytellers">Storytellers</a><br>
EOQ;
}
else {
    $menu_bar = <<<EOQ
<div class="menu-header">Tools</div>
<a href="/">Home</a><br />
<a href="/wiki/">Wiki</a><br>
<a href="/forum/index.php">Forums</a><br>
<!--<a href="/events.php">Event Calendar</a><br />-->
<a href="/support.php">Supporters</a><br />
<a href="/index.php?action=storytellers">Storytellers</a><br>
EOQ;
}