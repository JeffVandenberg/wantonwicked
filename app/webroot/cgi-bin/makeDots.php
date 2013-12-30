<?
function makeDots($element_name, $character_type = "mortal", $number_of_dots = 7, $value = 0, $edit = false, $update_traits = false)
{
	$return_value = "";
	$character_type = strtolower($character_type);
	
	for($i = 1; $i <= $number_of_dots; $i++)
	{
		$js = "";
		if($edit)
		{
			$js .= "changeDots('${element_name}',${i},$number_of_dots, true);";
		}
		
		if($update_traits)
		{
			$js .= "updateTraits();";
		}
		
		if($js != "")
		{
			$js = "onClick=\"$js\"";
		}
		
		
		if($i <= $value)
		{
			$return_value .= <<<EOQ
<img src="img/{$character_type}_filled.gif" name="${element_name}${i}" id="${element_name}${i}" border="0" hspace="0" vspace="0" $js />
EOQ;
		}
		else
		{
			$return_value .= <<<EOQ
<img src="img/empty.gif" name="${element_name}${i}" id="${element_name}${i}" border="0" hspace="0" vspace="0" $js />
EOQ;
		}
		
		if(($i%10) == 0)
		{
			$return_value .= "<br>";
		}
	}
	
	$return_value .= <<<EOQ
<input type="hidden" name="$element_name" id="$element_name" value="$value">
EOQ;

	return $return_value;
}
?>