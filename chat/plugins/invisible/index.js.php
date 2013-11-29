<?php
###########################################
# Author: Pro Chatrooms
# Software: Pro Chatrooms
# Url: http://www.prochatrooms.com
# Support: support@prochatrooms.com
# Copyright - All Rights Reserved
#
# PLUGIN: Invisible Admins
#
###########################################

/*
* include files
*
*/
include("../../includes/ini.php");
include("../../includes/session.php");
include("../../includes/db.php");
include("../../includes/config.php");
include("../../includes/functions.php");
header("content-type: application/x-javascript");
/* @var array $CONFIG */

/*
* assign hidden status
*
*/

$id = 0;

if(($CONFIG['invisibleAdminsPlugin']) && ($_SESSION['is_invisible']))
{
	$id = getAdmin($_SESSION['user_id']);
}

/*
* declare content header
*
*/

if($_SESSION['is_invisible']) {
    echo " var invisibleOn = ".$CONFIG['invisibleAdminsPlugin']."; ";
    echo " var hide = ".$id."; ";
}