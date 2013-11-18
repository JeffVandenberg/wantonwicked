<?
$plot_id = 0;
$plot_id = (isset($_POST['plot_id'])) ? $_POST['plot_id'] +0 : $plot_id;
$plot_id = (isset($_GET['plot_id'])) ? $_GET['plot_id'] +0 : $plot_id;

// build start of page
$page_title = "Important Characters for Plot #$plot_id";

// page variables
$page = "";
$alert = "";
$js = "";
$character_list = "";
$may_edit = false;

// test if deleting plots
if(isset($_POST['action']))
{
	$character_list = $_POST['delete'];
	while(list($key, $value) = each($character_list))
	{
		//echo "delete: $key: $value<br>";
		$delete_query = "delete from l5r_plots_characters where plot_character_id=$value;";
		$delete_result = mysql_query($delete_query) or die(mysql_error());
	}
}

// query db to verify using appropriate plot id
$plot_query = "select * from l5r_plots where plot_id = $plot_id;";
$plot_result = mysql_query($plot_query) or die(mysql_error());

if(mysql_num_rows($plot_result))
{
	// build contents of page
	$plot_detail = mysql_fetch_array($plot_result, MYSQL_ASSOC);
	
	// determine if may edit the list or not
	if(($plot_detail['Submitter_ID'] == $userdata['user_id']) || $userdata['is_admin'] || $userdata['is_head'])
	{
		$may_edit = true;
	}
	
	// determine if may add/delete characters
	if($may_edit)
	{
    $java_script = <<<EOQ
<script language="JavaScript">
function submitForm ( )
{
	window.document.character_list.submit();
}
</script>
EOQ;

    $character_list = <<<EOQ
<a href="$_SERVER[PHP_SELF]?action=plot_characters_add" onClick="window.open('$_SERVER[PHP_SELF]?action=plot_characters_add&plot_id=$plot_id', 'plot_character$plot_id', 'width=400,height=300,resizable,scrollbars');return false;">Add character</a>&nbsp;&nbsp;&nbsp;&nbsp;
<a href="#" onClick="submitForm();return false;">Remove Character(s)</a>
EOQ;
  }
  
  // build list of characters
	$plot_character_query = "select l5r_plots_characters.*, wod_characters.Character_Name from l5r_plots_characters left join wod_characters on l5r_plots_characters.character_id = wod_characters.character_id where l5r_plots_characters.plot_id = $plot_id;";
	$plot_character_result = mysql_query($plot_character_query) or die(mysql_error());

	$character_list .= <<<EOQ
<form name="character_list" id="character_list" method="post" action="$_SERVER[PHP_SELF]?action=plot_characters">
<table border="0" cellpadding="2" cellspacing="2" class="normal_text">
  <tr bgcolor="#000000">
EOQ;

  if($may_edit)
  {
	  $character_list .= "<th>Delete</th>";
  }
  $character_list .= "<th>Character Name</th><th>&nbsp;</th></tr>";
  
  $row = 0;
	while($plot_character_detail = mysql_fetch_array($plot_character_result, MYSQL_ASSOC))
	{
		$row_color = (($row++)%2) ? "#443a33" : "";
		$delete_cell = ($may_edit) ? "<td><input type=\"checkbox\" name=\"delete[]\" id=\"delete[]\" value=\"$plot_character_detail[Plot_Character_ID]\"></td>" : "";
		$character_list .= <<<EOQ
  <tr bgcolor="$row_color">
    $delete_cell
    <td>
      <a href="view_sheet.php?action=st_view&view_character_id=$plot_character_detail[Character_ID]" target="_blank">$plot_character_detail[Character_Name]</a>
    </td>
    <td>
      <a href="$_SERVER[PHP_SELF]?action=plot_characters_view&plot_character_id=$plot_character_detail[Plot_Character_ID]" onClick="window.open('$_SERVER[PHP_SELF]?action=plot_characters_view&plot_character_id=$plot_character_detail[Plot_Character_ID]', 'view_plot_character$plot_character_detail[Plot_Character_ID]', 'width=400,height=300,resizable,scrollbars');return false;">View Note</a>
    </td>
  </tr>
EOQ;
	}
	$character_list .= <<<EOQ
</table>
<input type="hidden" name="plot_id" id="plot_id" value="$plot_id">
<input type="hidden" name="action" id="action" value="delete">
</form>
EOQ;
}
else
{
	$alert = <<<EOQ
<span class="highlight">That is an invalid Plot ID: $plot_id</span>
EOQ;
}

// build page
$page_content = <<<EOQ
$alert
$character_list
EOQ;

?>