<?
function buildMultiSelect ( $selected, $values_list, $names_list, $select_name = "default", $size = 3, $select_multiple = false)
{
	if (sizeof($values_list) != sizeof($names_list) )
	{
		return "Error: lists were of different sizes.<br>";
	}
	
	if(empty($values_list))
	{
		return "No Values to Select.";
	}
	
	if(empty($selected))
	{
		$selected = array("---------");
	}
	
	if($size+0 < 3)
	{
		$size = 3;
	}
	
	$is_multiple = ($select_multiple) ? "multiple size=\"$size\"" : "";
	
	$select = "<select name=\"$select_name\" id=\"$select_name\" $is_multiple>\n";

	for( $i = 0; $i < sizeof($names_list); $i++)
	{
		if (in_array($values_list[$i], $selected))
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