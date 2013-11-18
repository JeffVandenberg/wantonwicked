<?php
$isDebug = true;

function ExecuteNonQuery($sql = null, $connection = null)
{
	global $isDebug;
	if($sql == null)
	{
		return false;
	}
	
	if($connection == null)
	{
		global $connection;
	}
	
	$result = mysql_query($sql, $connection);
	
	if(!$result)
	{
		if($isDebug)
		{
			echo mysql_error() . "\r\n" . $sql;
		}
		else
		{
			echo "There was an error with the database. Please inform Jeff V of what you were attempting to do.";
		}
		return false;
	}
	return true;
}

function ExecuteQuery($sql = null, $connection = null)
{
	global $isDebug;
	if($sql == null)
	{
		return false;
	}
	
	if($connection == null)
	{
		global $connection;
	}
	
	$result = mysql_query($sql, $connection);
	
	if(!$result)
	{
		if($isDebug)
		{
			echo mysql_error() . ' ERROR sql: ' . $sql . ' conn: ' . $connection;
		}
		else
		{
			echo "There was an error with the database. Please inform Jeff V of what you were attempting to do.";
		}
		return false;
	}
	return $result;
}

function ExecuteQueryData($sql = null, $connection = null)
{
	$result = ExecuteQuery($sql, $connection);
	
	$list = array();
    while($item = mysql_fetch_assoc($result))
    {
        $list[] = $item;
    }
	return $list;
}

function ExecuteQueryItem($sql, $connection = null) {
    $result = ExecuteQuery($sql, $connection);

    $return = null;
    while($item = mysql_fetch_assoc($result))
    {
        $return = $item;
    }
    return $return;
}