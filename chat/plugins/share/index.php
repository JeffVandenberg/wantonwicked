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
include("../../includes/session.php");
include("../../lang/".$_SESSION['lang']);
include("../../includes/config.php");
include("includes/functions.php");
###########################################
?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd"> 
<html>
<head> 
<title></title>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<link type="text/css" rel="stylesheet" href="../../templates/<?php echo $CONFIG['template'];?>/style.css">

<style>

.table
{
	border: 0px;
}

.header
{
	font-weight: bold;
}

.row
{
	background-color: #F5F5F5;
}

.sbody
{
	margin: 0 0 0 0; 
	padding: 0 0 0 0; 
	background-color: #CCCCCC;	
	font-family: Verdana, Arial;
	font-size: 12px;
	font-style: normal;
}

.sbutton
{
	height: 24px;
	width: 140px;
	border: 1px solid #333333;
	background-color: #666666;
	color: #FFFFFF;
	cursor: pointer;
	
	-moz-border-radius: 5px;
	border-radius: 5px;		
}

.sInput
{
	border: 1px solid #666666; 
	-moz-border-radius: 5px;
	border-radius: 5px;	
}

</style>

</head>
<body class="sbody">

<form style="padding: 0 0 0 3px;" enctype="multipart/form-data" name="upload" action="index.php" method="post">

<?php if(isset($_SESSION['groupShare']) && $_SESSION['groupShare'] < 1)
{
	echo "<div style='padding-left: 5px;'>".C_LANG60."</div>";
}
elseif($_POST){?>
	<span>&nbsp;<?php echo $result;?></span>
	<br><br>
<?php }else{?>
	<table>
    <tr><td>Select who to share files with,</td></tr>
    <tr><td><input type="radio" name="shareID" value="1" checked>Public - Share file with the Room? </td></tr>
    <tr><td><input type="radio" name="shareID" value="2" <?php if($_REQUEST['shareWithUser']){?>CHECKED<?php }?>>Private - Share file with another User?</td></tr>
    <tr><td>Enter User: <input class="sInput" type="text" name="shareWithUser" value="<?php echo $_REQUEST['shareWithUser'];?>"></td></tr>
    <tr><td>&nbsp;</td></tr>
    <tr><td>Select a file to share,</td></tr>
    <tr><td><input class="sInput" type="file" id="uploadedfile" name="uploadedfile" size="15"></td></tr>
    <tr><td><input class="button" type="submit" name="submit" value="Upload File" /></td></tr>
	</table>	
<?php }?>
</form>

<!-- do not edit below -->
<p style="text-align:center;">
	<input class="button" type="button" name="close" value="<?php echo C_LANG128;?>" onclick="parent.closeMdiv('shareFiles');">
</p>

</body>
</html>