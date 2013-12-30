<?
function buildSortForm ( $this_sort_col = "", $this_sort_dir = "asc", $last_sort_col = "", $action = "", $object_id_name = "", $object_id_value = "" )
{
  $sort_form = <<<EOQ
<form name="sort_form" id="sort_form" method="post" action="$action">
  <input type="hidden" name="action" id="action" value="sort">
  <input type="hidden" name="this_order_by" id="this_order_by" value="$this_sort_col">
  <input type="hidden" name="this_order_dir" id="this_sort_dir" value="$this_sort_dir">
  <input type="hidden" name="last_order_by" id="last_order_by" value="$last_sort_col">
EOQ;
  if($object_id_name != "" && $object_id_value != "")
  {
	  if(is_array($object_id_name) && (sizeof($object_id_name) == sizeof($object_id_value)))
	  {
		  for($i = 0; $i < sizeof($object_id_name); $i++)
		  {
			  $sort_form .= <<<EOQ
  <input type="hidden" name="$object_id_name[$i]" id="$object_id_name[$i]" value="$object_id_value[$i]">
EOQ;
		  }
	  }
	  else
	  {
		  // just passed a single value for each
			$sort_form .= <<<EOQ
  <input type="hidden" name="$object_id_name" id="$object_id_name" value="$object_id_value">
EOQ;
		}
  }
  $sort_form .= "</form>";
  return $sort_form;
}
?>