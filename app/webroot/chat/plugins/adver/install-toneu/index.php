<?php
/********************************************************************************************
*
*  Software: Pro Chat Rooms
*  Developer: Pro Chat Rooms
*  Url: http://prochatrooms.com
*  Support: http://community.prochatrooms.com
* 
*  Pro Chat Rooms is NOT free software - For more details visit, http://www.prochatrooms.com
*  This software and all of its source code/files are protected by Copyright Laws. 
*  The software license permits you to install this software on one domain only. Additional
*  installations require additional licences (one software licence per installation).
*  Pro Chat Rooms is unable to provide support if this software is modified by the end user.
*
********************************************************************************************/

/*
* check for software licence
*
*/

if(!file_exists("../../../software_licence.txt"))
{
	die("Please upload the <b>software_licence.txt</b> file");
}

/*
* include files
*
*/

include("../../../includes/config.php");
include("../../../includes/functions.php");

/*
* show html
*
*/

?>

<html>
<title>Pro Chat Rooms - Version <?php echo $CONFIG['version'];?> - Adverts Installation</title>
<head>
<link type="text/css" rel="stylesheet" href="../../../templates/default/style.css">
<style>
.installTable
{
	border: 1px solid #999999;
	background-color: #666666;
	font-family: Arial, Verdana;
	font-size: 12px;
	font-style: normal;
	color: #FFFFFF;
}
</style>
</head>
<body class="body">

<div style="padding-top:20px;padding-bottom:20px;width:100%;text-align:center;font-family: Arial, Verdana;font-size: 14px;">
	<b>Pro Chat Rooms - Version <?php echo $CONFIG['version'];?> - Adverts Installation</b>
</div>

<!-- begin install -->

<?php if (!$_POST){?>

	<script language="JavaScript">
	<!--
	function formCheck(form) 
	{
		if (!(install_licence.licence.checked))
		{
			alert( "Please agree to the software licence. ");
			return false ;
		}
	}
	// -->
	</script>

	<br>

	<table width="100%" align="center">
	<tr>
	<td align=center>

		<table cellpadding="10" width="400" border=0 class="installTable">
		<tr><td align=center width="60">
		<img src="images/help.gif" align="absmiddle">
		</td><td align="center">

		<form OnSubmit="return formCheck(this)" action="index.php" method="post" name="install_licence">
		<br>
		<br>
		<b>Welcome to the Pro Chat Rooms installation.</b>
		<br>
		<br>
		To begin the installation, please confirm you have read and agree to the <a style="color:#FFFFFF;" href="http://prochatrooms.com/software_licence.php" target="_blank">software licence</a>.
		<br>
		<br>
		<input type="checkbox" name="licence" onClick="document.install_licence['submit'].disabled =(document.install_licence['submit'].disabled)? false : true">
		I have read and agree to the <a style="color:#FFFFFF;" href="http://prochatrooms.com/software_licence.php" target="_blank">software licence</a>.
		<br>
		<br>
		<input type="hidden" name="i" value="2">
		<input type="submit" id="submit" name="submitthis" value="Continue ..." class="user_buttons_large" disabled>
		</form>
		<br>
		<br>
		</td></tr>
		</table>

	</td>
	</tr>
	</table>

<?php }?>

<!-- install - step 2 -->

<?php if ($_POST && $_POST['i']=='2'){?>

	<br>
	<table width="100%" align="center">
	<tr><td align=center>

		<table cellpadding="10" width="400" class="installTable">
		<tr><td align=center width="60">
		<img src="images/help.gif" align="absmiddle">
		</td><td align="center">
		<br>
		<br>
		<b>Install Adverts Plugin</b>
		<br>
		<br>
		Click on the button below to install the MySQL tables and complete the installation.
		<br>
		<br>
		<form action="index.php" method="post" name="cont_4">
		<input type="hidden" name="i" value="3">
		<input type="submit" name="submit" value="Continue ... " class="user_buttons_large">
		</form>
		<br>
		<br>
		</td></tr>
		</table>

	</td></tr></table>

<?php }?>

<!-- install - step 3 -->

<?php if ($_POST && $_POST['i']=='3'){?>

	<br>
	<table width="100%" align="center"><tr><td align=center>
	<table cellpadding="10" width="500" class="installTable">
	<tr><td align=center width="60">
	<img src="images/help.gif" align="absmiddle">
	</td><td align="center">
	<table width=500 border=0 border=0 style="font-family: Arial, Verdana;font-size: 12px;font-style: normal;color:#FFFFFF">
	<tr><td><b>Congratulations, you have completed the Pro Chat Rooms installation.<br><br>Below is your MySQL Table Installation Report.</b><br><br></td></tr>
	<tr><td><b>Install Results</b></td></tr>
	<tr><td>

	<?php

	/*
	* Table structure for table `prochatrooms_adverts`
	*
	*/

	try {
		$dbh = db_connect();
		$params = array('');
		$query = "CREATE TABLE IF NOT EXISTS `prochatrooms_adverts` (
			     `id` int(11) NOT NULL auto_increment,
			     `text` text NOT NULL,
			     `displays` varchar(500) NOT NULL default '0',
			     `clicks` varchar(500) NOT NULL default '0',
			     PRIMARY KEY  (`id`)
			     ) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=latin1 AUTO_INCREMENT=1
				 ";							
		$action = $dbh->prepare($query);
		if($action->execute($params)) 
		{
			echo "&#187; prochatrooms_adverts - <font color=\"#33FF00\"><b>INSTALLED</b></font>";
		}	
	
		$dbh = null;
	}
	catch(PDOException $e) 
	{
		$error  = "Function: ".__FUNCTION__."\n";
		$error .= "File: ".basename(__FILE__)."\n";	
		$error .= 'PDOException: '.$e->getCode(). '-'. $e->getMessage()."\n\n";

		debugError($error);
	}		

	?>

	</td></tr>
	<tr><td>&nbsp;</td></tr>
	<tr><td><b>Important!</b></td></tr>
	<tr><td>
	If all tables have successfully installed, please delete the folder '<b>plugins/adverts/install</b>'. 
	</td></tr>
	<tr><td>&nbsp;</td></tr>
	<tr><td>&nbsp;</td></tr>
	<tr><td><b>Thank you for choosing Pro Chat Rooms software.</b></td></tr>
	</table>
	</td></tr></table>

	</td></tr></table>

<?php }?>

<br>
<div style="width:100%;text-align:center;font-family: Arial, Verdana;font-size: 12px;font-style: normal;color:#666666;">
	If you require support during the installation process, please <a style="color:#666666;" href="http://www.prochatrooms.com/contact.php" target="_blank">contact us</a>.
	<br>
	&copy;<?php echo date("Y");?> <a style="color:#666666;" href="http://www.prochatrooms.com/" target="_blank">Pro Chat Rooms</a> All Rights Reserved.
<div>

</body>
</html>