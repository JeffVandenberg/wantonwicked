<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>{PAGE_TITLE}</title>
    <META NAME="copyright" content="(c) 2013 Jeff Vandenberg">
    <META NAME="ROBOTS" CONTENT="noimageindex,follow">
    <link type="text/css" href="css/wicked_4.css" rel="Stylesheet"/>
    <link type="text/css" href="css/gaming-sandbox.css" rel="Stylesheet"/>
    <link type="text/css" href="css/wanton/jquery-ui-1.10.3.custom.min.css" rel="stylesheet"/>
    <link type="text/css" href="css/wanton/jquery.ui.menubar.css" rel="stylesheet"/>
    <script type="text/javascript" src="js/jquery-1.9.1.js"></script>
    <script type="text/javascript" src="js/jquery-ui-1.10.3.custom.min.js"></script>
    <script type="text/javascript" src="js/jquery.watermark.min.js"></script>
    <script type="text/javascript" src="js/jquery.ui.menubar.js"></script>
    <script type="text/javascript" src="js/wanton.js"></script>
    <script type="text/javascript" src="js/gaming-sandbox.js"></script>
</head>
{JAVA_SCRIPT}
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
<div id="container">
    <div id="header-bar">
        <div id="header-bar-background">
        </div>
        <div id="header-bar-inner">
            {USER_PANEL}
        </div>
    </div>
    <div id="logo">
    </div>
    <div id="content-wrapper">
        <div id="left-menu" class="content-box">
            <div class="box-header">
                Menu
            </div>
            <div class="box-content">
                <div id="left-menu-content">
                    {MENU_BAR}
                <div align="center" style="font-size:10px; font-style:italic;"><br>World of Darkness,<br> Changeling: the Lost,<br> Geist: The Sin-Eaters,<br> Mage: The Awakening,<br> Vampire: the Requiem,<br> Werewolf: the Forsaken<br>
                © White Wolf Publishing, Inc.</div>
                </div>
            </div>
        </div>
        <div id="content" class="content-box">
            <div class="box-header">
                {CONTENT_HEADER}
            </div>
            <!-- IF FLASH_MESSAGE -->
            <div class="box-content">
                {FLASH_MESSAGE}
            </div>
            <!-- ENDIF -->
            <div class="box-content">
                {PAGE_CONTENT}
            </div>
        </div>
    </div>
</div>
<div id="footer-bar">
    <div id="footer-inner">
        Images by jarden
        Produced by Jeff Vandenberg
        Copyright 2013
    </div>
</div>
</body>
</html>