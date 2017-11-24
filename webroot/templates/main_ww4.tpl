<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>{PAGE_TITLE}</title>
    <META NAME="copyright" content="(c) <?php echo date('Y'); ?> Jeff Vandenberg">
    <META NAME="ROBOTS" CONTENT="noimageindex,follow">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link type="text/css" href="/css/app.css" rel="Stylesheet"/>
    <link href="https://fonts.googleapis.com/css?family=Marcellus+SC" rel="stylesheet">
    <script type="text/javascript" src="/js/jquery.min.js"></script>
    <script type="text/javascript" src="/js/jquery.autocomplete.min.js"></script>
    <script type="text/javascript" src="/js/jquery.datetimepicker.full.min.js"></script>
    <script type="text/javascript" src="/js/tinymce/tinymce.min.js"></script>
    <script type="text/javascript" src="/js/server_time.js"></script>
    <script type="text/javascript" src="/js/foundation.min.js"></script>
    <script type="text/javascript" src="/js/wanton.js"></script>
</head>
<body>
<div id="header">
    <div class="title-bar" data-hide-for="medium" data-responsive-toggle="nav-top">
        <button class="menu-icon" type="button" data-toggle></button>
        <div class="title-logo" role="banner">
            <a href="/" title="Wanton Wicked">
                <img src="/img/ww_logo_50x50.png" alt="Wanton Wicked Logo"/>
            </a>
        </div>
    </div>
    <nav id="nav-top" class="top-bar" data-sticky data-options="marginTop:0;" style="width:100%"
         data-top-anchor="main-content">
        <ul class="menu">
            <li class="topbar-title title-logo show-for-medium"
                role="banner">
                <a href="/" title="Wanton Wicked">
                    <img src="/img/ww_logo_50x50.png" alt="Wanton Wicked Logo"/>
                </a>
            </li>
        </ul>
        <div class="top-bar-left">
            {MENU_BAR}
        </div>
        <div class="top-bar-right">
            <span id="server-time"></span>
            <!-- IF USER_INFO.logged_in -->
                <!-- IF USER_INFO.new_request_count -->
                <a href="/request.php?action=st_list" class="button-badge">
                    <i class="fa fi-clipboard storyteller-action" title="ST Request Dashboard"></i>
                    <span class="badge badge-primary warning" title="New Requests">{USER_INFO.new_request_count}</span>
                </a>
                <!-- ENDIF -->
                <a href="/request.php" class="button-badge">
                    <i class="fa fi-clipboard" title="Your Requests"></i>
                    <!-- IF USER_INFO.request_count-->
                    <span class="badge badge-primary warning" title="Open Requests">{USER_INFO.request_count}</span>
                    <!-- ENDIF -->
                </a>
                <button class="button" type="button" data-toggle="user-dropdown">
                    {USER_INFO.username}
                </button>
                <div class="dropdown-pane" id="user-dropdown" data-dropdown>
                    <div><a href="/forum/ucp.php">User Control Panel</a></div>
                    <div><a href="{USER_INFO.logout_link}">Logout</a></div>
                </div>
            <!-- ELSE -->
                <a href="/forum/ucp.php?mode=login&redirect={USER_INFO.redirect}">Login</a>
                <a href="/forum/ucp.php?mode=register&redirect={USER_INFO.redirect}">Register</a>
            <!-- ENDIF -->
        </div>
    </nav>
</div>
<div class="widthsetter" id="main-content">
    <div id="content">
        <div id="pagetitle">
            {CONTENT_HEADER}
        </div>
        <div id="contenta" class="contentbox">
            <!-- BEGIN messages -->
            <div class="flash-message">
                {messages.message}
            </div>
            <!-- END messages -->
            {PAGE_CONTENT}
        </div>
    </div>
</div>
<div id="footer">
    <div class="row">
        <div class="small-12 column text-center">
            <div style="font-size: 9px;">The Storytelling System, Beast the Primordial, Changeling
                the Lost, Chronicles of Darkness, Demon the Descent, Mage the Awakening, Vampire the Requiem,
                and
                Werewolf the Forsaken
                &copy;2014-2016 CCP hf and published by <a href="http://theonyxpath.com/" target="_blank">Onyx
                    Path
                    Publishing</a>.<br>
                Produced by Jeff Vandenberg. Layout and Design by Jill Arden &copy; 2016
                Build # {BUILD_NUMBER}
            </div>
        </div>
    </div>
</div>
<img src="/img/indicator.gif" id="busy-indicator" alt=""/>
{JAVA_SCRIPT}
<script>
    wantonWickedTime.serverTime = {SERVER_TIME};
</script>
</body>
</html>
