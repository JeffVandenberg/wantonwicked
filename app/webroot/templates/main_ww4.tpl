<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>{PAGE_TITLE}</title>
    <META NAME="copyright" content="(c) 2013 Jeff Vandenberg">
    <META NAME="ROBOTS" CONTENT="noimageindex,follow">
    <link type="text/css" href="css/ww4_v2.css" rel="Stylesheet"/>
    <link type="text/css" href="css/gaming-sandbox.css" rel="Stylesheet"/>
    <link type="text/css" href="css/wanton/jquery-ui-1.10.3.custom.min.css" rel="stylesheet"/>
    <link type="text/css" href="css/wanton/jquery.ui.menubar.css" rel="stylesheet"/>
    <script type="text/javascript" src="js/jquery-1.9.1.js"></script>
    <script type="text/javascript" src="js/jquery-ui-1.10.3.custom.min.js"></script>
    <script type="text/javascript" src="js/jquery.watermark.min.js"></script>
    <script type="text/javascript" src="js/jquery.ui.menubar.js"></script>
    <script type="text/javascript" src="js/server_time.js"></script>
    <script type="text/javascript" src="js/wanton.js"></script>
    <script type="text/javascript" src="js/gaming-sandbox.js"></script>
</head>
{JAVA_SCRIPT}
<script>
    wantonWicked.serverTime = {SERVER_TIME};
</script>
<body>
<div id="gs-header-bar">
    <div id="gs-header-inner">
        <div id="gs-header-logo">
            <a href="/" id="gs-header-logo-nav">
                Wanton Wicked
            </a>
        </div>
        <div id="gs-header-games">
            <select id="gs-game-selector">
                <option value="www">Home</option>
                <option value="ragnarok">Ragnarok NYC</option>
                <option value="wantonwicked" selected>Wanton Wicked</option>
            </select>
        </div>
    </div>
</div>
<div id="wrapper">
    <div id="login">
        <div id="logina">
        <div id="loginb">
            {USER_PANEL}
        </div>
		</div>
    </div>
    <div id="logo">
    </div>
	<div id="nav">
	{MENU_BAR}
	</div>
    <div id="content">
        <div id="pagetitle">
		{CONTENT_HEADER}
		</div>
		<div id="contenta">
		<div id="contentb" class="contentbox">
            <!-- IF FLASH_MESSAGE -->
            <div class="flash-message">
                {FLASH_MESSAGE}
            </div>
            <!-- ENDIF -->
            {PAGE_CONTENT}
		</div>
        </div>
    </div>
    <div id="content">
		<div id="contenta">
		<div id="contentb" class="contentbox">
            <div style="font-size: 9px;text-align: center;">World of Darkness, Changeling: The Lost, Geist: The Sin-Eaters, Mage: The Awakening,
                Vampire: The Requiem, Werewolf: The Forsaken <br>
                &copy;2013-2014 <a href="http://www.white-wolf.com/fansites/termsofuse.php" target="_blank">White
                    Wolf Publishing, Inc.</a>
            </div>
		</div>
        </div>
    </div>
</div>
<div id="footer-bar">
    <div id="footer-inner">
        Layout and Design by jarden
        Produced by Jeff Vandenberg
        Copyright 2013
    </div>
</div>
</body>
</html>