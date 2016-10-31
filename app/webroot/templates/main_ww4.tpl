<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>{PAGE_TITLE}</title>
    <META NAME="copyright" content="(c) 2016 Jeff Vandenberg">
    <META NAME="ROBOTS" CONTENT="noimageindex,follow">
    <link type="text/css" href="/css/ww5.css" rel="Stylesheet"/>
    <link type="text/css" href="/css/gaming-sandbox.css" rel="Stylesheet"/>
    <link type="text/css" href="/css/wanton/jquery-ui.min.css" rel="stylesheet"/>
    <link type="text/css" href="/css/wanton/jquery.ui.menubar.css" rel="stylesheet"/>
    <link href="https://fonts.googleapis.com/css?family=Special+Elite" rel="stylesheet">
    <script type="text/javascript" src="/js/jquery-1.11.3.min.js"></script>
    <script type="text/javascript" src="/js/jquery-ui.min.js"></script>
    <script type="text/javascript" src="/js/jquery.watermark.min.js"></script>
    <script type="text/javascript" src="/js/jquery.ui.menubar.js"></script>
    <script type="text/javascript" src="/js/tinymce/tinymce.min.js"></script>
    <script type="text/javascript" src="/js/server_time.js"></script>
    <script type="text/javascript" src="/js/wanton.js"></script>
    <script type="text/javascript" src="/js/gaming-sandbox.js"></script>
</head>
{JAVA_SCRIPT}
<script>
    wantonWickedTime.serverTime = {SERVER_TIME};
</script>
<body>
<div id="header">
    <div class="widthsetter">
        <div id="logo"></div>
        <div id="userpanel">{USER_PANEL}</div>
        <div id="nav">
            {MENU_BAR}
        </div>
    </div>
</div>
<div class="widthsetter" id="main-content">
    <div id="content">
        <div id="pagetitle">
            {CONTENT_HEADER}
        </div>
        <div id="contenta" class="contentbox">
            <!-- IF FLASH_MESSAGE -->
            <div class="flash-message">
                {FLASH_MESSAGE}
            </div>
            <!-- ENDIF -->
            {PAGE_CONTENT}
        </div>
    </div>
</div>
<div id="footer">
    <div class="widthsetter">
        <div id="contenta" class="contentbox">
            <div style="font-size: 9px;text-align: center;">The Storytelling System, Beast the Primordial, Changeling
                the Lost, Chronicles of Darkness, Demon the Descent, Mage the Awakening, Vampire the Requiem, and
                Werewolf the Forsaken<br>
                &copy;2014-2016 CCP hf and published by <a href="http://theonyxpath.com/" target="_blank">Onyx Path
                    Publishing</a>.<br>
                Produced by Jeff Vandenberg. Layout and Design by Jill Arden &copy;2016
            </div>
        </div>
    </div>
</div>
<img src="/img/indicator.gif" id="busy-indicator" alt=""/>
</body>
</html>
