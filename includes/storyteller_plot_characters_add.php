<?
// get plot id
$plot_id = 0;
$plot_id = (isset($_POST['plot_id'])) ? $_POST['plot_id'] +0 : $plot_id;
$plot_id = (isset($_GET['plot_id'])) ? $_GET['plot_id'] +0 : $plot_id;

// build start of page
$page_title = "Add Character to Plot #$plot_id";

// page variables
$page = "";
$alert = "";
$js = "";
$form = "";
$show_form = true;
$character_name = "";
$notes = "";

// test if adding an character to the plot
if(isset($_POST['action']))
{
	// get character name
	$character_name = (!empty($_POST['character_name'])) ? $_POST['character_name'] : "";
	$notes = htmlspecialchars($_POST['notes']);
	// test to make sure we have a valid character id
	$character_query = "select * from wod_characters where character_name='$character_name';";
	//echo $character_query."<br>";
	$character_result = mysql_query($character_query) or die(mysql_error());
	
	if(mysql_num_rows($character_result))
	{
		$character_detail = mysql_fetch_array($character_result, MYSQL_ASSOC);
		
		// insert values
		$insert_query = "insert into l5r_plots_characters values (null, $plot_id, $character_detail[Character_ID], '$notes');";
		$insert_result = mysql_query($insert_query) or die(mysql_error());
		
		// hide form and put in js to refresh character listing and close window.
		$show_form = false;
		$java_script = <<<EOQ
<script language="javascript">
  window.opener.location.reload(true);
  window.opener.focus();
  window.close();
</script>
EOQ;
	}
	else
	{
		$alert = "<span class=\"highlight\">Please put in a valid Character Name.</span>";
	}
}

if($show_form)
{
	// get details of plot
	$plot_query = "select * from l5r_plots where plot_id = $plot_id;";
  $plot_result = mysql_query($plot_query) or die(mysql_error());
	$plot_detail = mysql_fetch_array($plot_result, MYSQL_ASSOC);

	// build form
	$form = <<<EOQ
<form method="post" action="$_SERVER[PHP_SELF]?action=plot_characters_add">
<table border="0" cellpadding="2" cellspacing="2" class="normal_text">
  <tr>
    <td>
      <span class="highlight">Plot Name:</span>
    </td>
    <td>
      $plot_detail[Plot_Name]
    </td>
  </tr>
  <tr valign="top">
    <td>
      <span class="highlight">Character Name:</span><br>
    </td>
    <td>
      <input type="text" name="character_name" id="character_name" size="30" maxlength="30" value="$character_name">
    </td>
  </tr>
  <tr>
    <td>
      <span class="highlight">Notes:</span>
    </td>
    <td>
      &nbsp;
    </td>
  </tr>
  <tr>
    <td colspan="2">
      <textarea name="notes" id="notes" cols="40" rows="5" wrap="physical">$notes</textarea>
    </td>
  </tr>
  <tr>
    <td colspan="2" align="center">
      <input type="hidden" name="action" name="action" value="create">
      <input type="hidden" name="plot_id" id="plot_id" value="$plot_id">
      <input type="submit" value="Submit">
    </td>
  </tr>
</table>
</form>
EOQ;

}

// build page
$page_content = <<<EOQ
$alert
$form
EOQ;
?>