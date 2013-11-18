<?php
$contentHeader = "Home";
$page_title = "Wanton Wicked an Online World of Darkness Roleplaying Game";


$page_content = <<<EOQ
<div class="news">
    <div class="news-title" style="font-size:1.2em;font-weight:bold;">Supporter Package Up</div>
    <div class="news-body">
        <div class="paragraph">
            Site Supporter is starting to go live. You can now see a list of supporters on the left navigation bar.
            From there, you can find a link to contribute if you wish to. <br />
            <a href="http://wantonwicked.gamingsandbox.com/support.php">View Supporters</a> -
            <a href="http://wantonwicked.gamingsandbox.com/support.php?action=contribute">Contribute</a>
        </div>
        <div class="paragraph">
            Once you're payment has been received and processed, you'll be able to select which character to receive
            the bonus.
        </div>
    </div>
</div>
<div class="news">
    <div class="news-title" style="font-size:1.2em;font-weight:bold;">Game Status & New Chat Server</div>
    <div class="news-body">
        <div class="paragraph">
            The game is officially <strong>Live!</strong>
        </div>
        <div class="paragraph">
            Prochat is the final choice for chat platform for WantonWicked. It's PHP/JS based, so it's going to be
            very extensible. Look forward to a lot of new integrated features between chat and site as 4.0 continues
            to be developed.
        </div>
    </div>
</div>
<br />
<br />
-Jeff Vandenberg<br />
<h2>Log In OOC</h2>
<form method="post" action="/chat/index.php">
    Name: <input type="text" name="username" />
    <input type="submit" value="Log in">
</form>
EOQ;
