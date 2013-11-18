<?
$page_title = "Add/Edit Plot Types";

// page variables
$page = "";
$plot_type_lookup_form = "";
$plot_type_form = "";
$alert = "";

// form variables
$plot_type_id = "";
$action = "create";
$plot_type_name = "";
$plot_type_description = "";

// test if updating
if(isset($_POST['action']))
{
  $plot_type_name = (!empty($_POST['plot_type_name'])) ? htmlspecialchars($_POST['plot_type_name']) : "Empty Plot Name";
  $plot_type_description = (!empty($_POST['plot_type_description'])) ? str_replace("\n", "<br>", htmlspecialchars($_POST['plot_type_description'])) : "Empty Plot Description";

  if($_POST['action'] == 'create')
  {
    $lock_query = "lock table l5r_plots_types write;";
    $lock_result = mysql_query($lock_query) or die(mysql_error());

    $plot_type_id = getNextID($connection, "l5r_plots_types", "plot_type_id");
    $insert_query = "insert into l5r_plots_types values ($plot_type_id, '$plot_type_name', '$plot_type_description');";
    $insert_result = mysql_query($insert_query) or die(mysql_error());

    $unlock_query = "unlock tables;";
    $unlock_result = mysql_query($unlock_query) or die(mysql_error());
  }
  if($_POST['action'] == 'update')
  {
    $plot_type_id = $_POST['plot_type_id'] +0;
    $update_query = "update l5r_plots_types set plot_type_name='$plot_type_name', plot_type_description='$plot_type_description' where plot_type_id = $plot_type_id;";
    $update_result = mysql_query($update_query) or die(mysql_error());
  }
  $plot_type_id = "";
	$plot_type_name = "";
	$plot_type_description = "";
}

// test if looking up a plot type
if(isset($_POST['lookup_plot_id']))
{
  $plot_type_id = $_POST['lookup_plot_id'] +0;
  $plot_type_query = "select * from l5r_plots_types where plot_type_id = $plot_type_id;";
  $plot_type_result = mysql_query($plot_type_query) or die(mysql_error());
  $plot_type_detail = mysql_fetch_array($plot_type_result, MYSQL_ASSOC);

  $plot_type_name = $plot_type_detail['Plot_Type_Name'];
  $plot_type_description = str_replace("<br>", "\n", $plot_type_detail['Plot_Type_Description']);
  $action = "update";
}

// build up drop down select of plot types
$plot_types_query = "select * from l5r_plots_types order by plot_type_name";
$plot_types_result = mysql_query($plot_types_query) or die(mysql_error());

$plot_types_ids = "";
$plot_types_names = "";

while($plot_types_detail = mysql_fetch_array($plot_types_result, MYSQL_ASSOC))
{
  $plot_types_ids[] = $plot_types_detail['Plot_Type_ID'];
  $plot_types_names[] = $plot_types_detail['Plot_Type_Name'];
}

$plot_type_select = buildSelect("", $plot_types_ids, $plot_types_names, "lookup_plot_id");

$plot_type_lookup_form = <<<EOQ
<form method="post" action="$_SERVER[PHP_SELF]?action=plot_types_admin">
<span class="highlight">View Details of:</span> $plot_type_select 
<input type="submit" value="Lookup">
</form>
EOQ;

// build form to display
$plot_type_form = <<<EOQ
<form method="post" action="$_SERVER[PHP_SELF]?action=plot_types_admin">
<table border="0" cellpadding="2" cellspacing="2" class="normal_text">
  <tr>
    <td>
      <span class="highlight">Plot Type ID: </span>
    </td>
    <td>
      <input type="text" name="plot_type_id" id="plot_type_id" size="3" maxlength="3" readonly style="background-color:$blocked_input_color" value="$plot_type_id">
    </td>
  </tr>
  <tr>
    <td>
      <span class="highlight">Plot Type Name: </span>
    </td>
    <td>
      <input type="text" name="plot_type_name" id="plot_type_name" size="20" maxlength="30" value="$plot_type_name">
    </td>
  </tr>
  <tr>
    <td>
      <span class="highlight">Plot Type Description: </span>
    </td>
    <td>
      <textarea name="plot_type_description" id="plot_type_description" rows="5" cols="30">$plot_type_description</textarea>
    </td>
  </tr>
  <tr>
    <td colspan="2" align="center">
      <input type="hidden" name="action" id="action" value="$action">
      <input type="submit" value="Submit">
    </td>
  </tr>
</table>
</form>
EOQ;


$page_content = <<<EOQ
$alert
$plot_type_lookup_form
<br>
$plot_type_form
EOQ;

?>