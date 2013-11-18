<?
function authenticate($auth_user, $auth_pass)
{
  global $connection;
  
	$query = <<<EOQ
SELECT permissions.*, gm_permissions.* ,login.*
  FROM ( login INNER JOIN permissions on login.id = permissions.id)
       left join gm_permissions on (login.id = gm_permissions.id)
 WHERE login.name="$auth_user"
        AND login.password="$auth_pass"
        AND ( login.default_site = gm_permissions.site_id OR gm_permissions.site_id is null)
        AND permissions.May_Login='Y';
EOQ;

  //echo "getting login information<br>";
	$result = mysql_query($query) or die(mysql_error());
  //echo "got login information<br>";
	$ret_val = "";

	if (mysql_num_rows($result))
	{
		$ret_val['authenticated'] = true;
    $details = mysql_fetch_array($result, MYSQL_ASSOC);
    while (list($key, $value) = each($details))
    {
	    //echo "$key: $value<br>";
	    $ret_val["$key"] = $value;
	    //echo "$key: $value<br>";
    }
	}
	else
	{
		$ret_val['authenticated'] = false;
	}
	return $ret_val;
}
?>