<?php

use classes\core\helpers\Response;
use classes\core\helpers\SessionHelper;

require_once 'cgi-bin/start_of_page.php';
?>
<html>
<head>
    <link type="text/css" href="/css/wanton/jquery-ui.min.css" rel="stylesheet"/>
    <link type="text/css" href="/css/wanton/jquery.ui.menubar.css" rel="stylesheet"/>
    <script type="text/javascript" src="/js/jquery-1.11.3.min.js"></script>
    <script type="text/javascript" src="/js/jquery-ui.min.js"></script>
    <script type="text/javascript" src="/js/jquery.watermark.min.js"></script>
    <script type="text/javascript" src="/js/jquery.ui.menubar.js"></script>
    <style>
        .ui-menu { width: 150px; }
    </style>
</head>
<body>
<ul id="menu">
    <li><a href="http://www.google.com" target="_blank">Aberdeen</a></li>
    <li><a href="#">Ada</a></li>
    <li><a href="#">Adamsville</a></li>
    <li><a href="#">Addyston</a></li>
    <li>
        <a href="#">Delphi</a>
        <ul>
            <li >
                <a href="#">Ada</a>
                <ul>
                    <li>
                        <a href="#">ankita</a>
                    </li>
                </ul>
            <li><a href="http://www.google.com">Saarland</a></li>
            <li><a href="#">Salzburg</a></li>
        </ul>
    </li>
    <li><a href="#">Saarland</a></li>
    <li>
        <a href="#">Salzburg</a>
        <ul>
            <li>
                <a href="http://www.google.com" target="_blank">Delphi</a>
                <ul>
                    <li><a href="#">Ada</a></li>
                    <li><a href="#">Saarland</a></li>
                    <li><a href="#">Salzburg</a></li>
                </ul>
            </li>
            <li>
                <a href="#">Delphi</a>
                <ul>
                    <li><a href="#">Ada</a></li>
                    <li><a href="#">Saarland</a></li>
                    <li><a href="#">Salzburg</a></li>
                </ul>
            </li>
            <li><a href="#">Perch</a></li>
        </ul>
    </li>
    <li><a href="#">Amesville</a></li>
</ul>
<script>
</script>
</body>
</html>
