<?php
###########################################
# Author: Pro Chatrooms
# Software: Pro Chatrooms
# Url: http://www.prochatrooms.com
# Support: support@prochatrooms.com
# Copyright 2013 All Rights Reserved
#
# PLUGIN: Share Images Module
#
use classes\core\helpers\FormHelper;

include("../../includes/session.php");
include("../../lang/" . $_SESSION['lang']);
include("../../includes/config.php");
include("includes/functions.php");
/* @var array $CONFIG */
###########################################
?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
    <title></title>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <link type="text/css" rel="stylesheet" href="../../templates/<?php echo $CONFIG['template']; ?>/style.css">

    <style type="text/css">
        .table {
            border: 0;
        }

        .header {
            font-weight: bold;
        }

        .row {
            background-color: #F5F5F5;
        }

        .sbody {
            margin: 0 0 0 0;
            padding: 0 0 0 0;
            background-color: #CCCCCC;
            font-family: Verdana, Arial, sans-serif;
            font-size: 12px;
            font-style: normal;
        }

        .sbutton {
            height: 24px;
            width: 140px;
            border: 1px solid #333333;
            background-color: #666666;
            color: #FFFFFF;
            cursor: pointer;

            -moz-border-radius: 5px;
            border-radius: 5px;
        }

        .sInput {
            border: 1px solid #666666;
            -moz-border-radius: 5px;
            border-radius: 5px;
        }

    </style>

</head>
<body class="sbody">

<form style="padding: 0 0 0 3px;" enctype="multipart/form-data" name="upload" action="index.php" method="post">

    <?php if (isset($_SESSION['groupShare']) && $_SESSION['groupShare'] < 1) {
        echo "<div style='padding-left: 5px;'>" . C_LANG60 . "</div>";
    }
    else if ($_POST):

        ?>
        <span>&nbsp;<?php echo $result; ?></span>
        <br><br>
    <?php else: ?>
        <?php
        // get list of online users
        $sql = "select id, display_name from prochatrooms_users where online = 1 ORDER BY display_name;";
        $dbh = db_connect();
        $action = $dbh->prepare($sql);
        $action->execute();
        $ids = array('0');
        $names = array('All');
        $users = [
            0 => 'All'
        ];
        foreach ($action as $i) {
            $users[$i['id']] = $i['display_name'];
        }

        $users = [];
        require_once(__DIR__ . '../../../../../class/classes/core/helpers/FormHelper.php');
        $userSelect = FormHelper::Select($users, 'shareWithUserId', $_REQUEST['shareWithUserId']);

        $publicChecked = (!($_REQUEST['shareWithUserId'])) ? "checked" : "";
        $privateChecked = (($_REQUEST['shareWithUserId'])) ? "checked" : "";
        ?>

        <table style="margin: 0 auto;">
            <tr>
                <td>Select who to share files with,</td>
            </tr>
            <tr>
                <td>
                    <label>
                        <input type="radio" name="shareID" value="1" <?php echo $publicChecked; ?>>
                        Public - Share file with the Room?
                    </label>
                </td>
            </tr>
            <tr>
                <td>
                    <label>
                        <input type="radio" name="shareID" value="2" <?php echo $privateChecked; ?>>
                        Private - Share file with another User?
                    </label>
                </td>
            </tr>
            <tr>
                <td>
                    <label>
                    Select User:
                    <?php echo $userSelect; ?>
                    </label>
                </td>
            </tr>
            <tr>
                <td>&nbsp;</td>
            </tr>
            <tr>
                <td>Select a file to share,</td>
            </tr>
            <tr>
                <td><input class="sInput" type="file" id="uploadedfile" name="uploadedfile" size="15"></td>
            </tr>
            <tr>
                <td style="text-align: center;">
                    <input class="button" type="submit" name="submit" value="Upload File"/>
                </td>
            </tr>
        </table>
    <?php endif; ?>
</form>

<!-- do not edit below -->
<p style="text-align:center;">
    <input class="button" type="button" name="close" value="<?php echo C_LANG128; ?>"
           onclick="parent.closeMdiv('shareFiles');">
</p>

</body>
</html>