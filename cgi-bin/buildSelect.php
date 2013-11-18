<?
function buildSelect ( $selected, $values_list, $names_list, $select_name, $extra_tags = "")
{
	if (sizeof($values_list) != sizeof($names_list) )
	{
		return "Error: lists were of different sizes.<br>";
	}
	
	if(empty($values_list))
	{
		return "No Values to Select.";
	}
	
	$select = "<select name=\"$select_name\" id=\"$select_name\" $extra_tags>\n";

	for( $i = 0; $i < sizeof($names_list); $i++)
	{
		if ($selected == $values_list[$i])
		{
			$select .= "<option value=\"$values_list[$i]\" selected>$names_list[$i]</option>\n";
		}
		else
		{
			$select .= "<option value=\"$values_list[$i]\">$names_list[$i]</option>\n";
		}
	}
	$select .= "</select>\n";
	
  return $select;
}
?>