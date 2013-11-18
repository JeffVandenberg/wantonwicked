<?
// get plot id
$plot_character_id = 0;
$plot_character_id = (isset($_POST['plot_character_id'])) ? $_POST['plot_character_id'] +0 : $plot_character_id;
$plot_character_id = (isset($_GET['plot_character_id'])) ? $_GET['plot_character_id'] +0 : $plot_character_id;

// build start of page
$page_title = "View Character Note #$plot_character_id";

// page variables
$page = "";
$alert = "";
$js = "";
$form = "";
$show_form = true;
$may_edit = false;

// form variables
$notes_readonly = "readonly";
$submit = "";

// test if adding an character to the plot
if(isset($_POST['action']))
{
	$update_query = "update l5r_plots_characters set notes='$_POST[notes]' where plot_character_id=$plot_character_id;";
	$update_result = mysql_query($update_query) or die(mysql_error());
}

// get details of character for plot
$plot_character_query = "select wod_characters.Character_Name, l5r_plots.*, l5r_plots_characters.Notes from (l5r_plots_characters left join l5r_plots on l5r_plots_characters.plot_id = l5r_plots.plot_id) left join wod_characters on l5r_plots_characters.character_id = wod_characters.character_id where l5r_plots_characters.plot_character_id = $plot_character_id;";
$plot_character_result = mysql_query($plot_character_query) or die(mysql_error());
$plot_character_detail = mysql_fetch_array($plot_character_result, MYSQL_ASSOC);

// test if may edit this
if($plot_character_detail['Submitter_ID'] == $userdata['user_id'] || $userdata['is_head'] || $userdata['is_admin'])
{
	$submit = "<input type=\"submit\" value=\"Submit\">";
	$notes_readonly = "";
}

// build form on page
$form = <<<EOQ
<form method="post" action="$_SERVER[PHP_SELF]?action=plot_characters_view">
<table border="0" cellpadding="2" cellspacing="2" class="normal_text">
  <tr>
    <td>
      <span class="highlight">Plot Name:</span>
    </td>
    <td>
      $plot_character_detail[Plot_Name]
    </td>
  </tr>
  <tr>
    <td>
      <span class="highlight">Character Name:</span>
    </td>
    <td>
      $plot_character_detail[Character_Name]
    </td>
  </tr>
  <tr>
    <td colspan="2">
      <span class="highlight">Notes:</span>
    </td>
  </tr>
  <tr>
    <td colspan="2">
      <textarea name="notes" id="notes" cols="40" rows="7" wrap="physical" $notes_readonly>$plot_character_detail[Notes]</textarea>
    </td>
  </tr>
  <tr>
    <td colspan="2" align="center">
      <input type="hidden" name="plot_character_id" id="plot_character_id" value="$plot_character_id">
      <input type="hidden" name="action" id="action" value="update">
      $submit
    </td>
  </tr>
</form>
EOQ;

// build page
$page_content = <<<EOQ
$alert
$form
EOQ;
?>