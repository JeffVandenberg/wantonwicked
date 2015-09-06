<?
echo date('Y-m-d h:i:s A');
phpinfo();
/*include 'cgi-bin/dbconnect.php';

$query = "select * from phpbb_users;";
$result = mysql_query($query) || die(mysql_error());

while($detail = mysql_fetch_array($result, MYSQL_ASSOC))
{
	$login_query = <<<EOQ
insert into login values (
$detail[user_id],
'$detail[username]',
'2001-01-01',
'$detail[user_password]',
0,
'127.0.0.1', 
'127.0.0.1', 
'2005-07-09',
'2005-07-09',
0, 
'Y',
'$detail[user_email]'
);
EOQ;

	$login_result = mysql_query($login_query) || die(mysql_error());
	
	$permissions_query = <<<EOQ
insert into permissions values
(
null,
$detail[user_id],
'Y',
'N',
'N',
'N',
'N',
'N',
'N',
'N',
'N',
'N',
'N'
);
EOQ;
	$permissions_result = mysql_query($permissions_query) || die(mysql_error());
	
	echo "Updated $detail[username].<br>";
}*/
?>
