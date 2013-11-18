<?
function getNextID ($connection, $table, $column)
{
	$id_query = "select $column from $table order by $column desc limit 1;";
	$id_result = mysql_query($id_query) or die(mysql_error());
	$id_detail = mysql_fetch_array($id_result, MYSQL_ASSOC);
	
	return $id_detail["$column"]+1;
}
?>