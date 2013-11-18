<?
$page_title = "Search Plots";

// build list of GMs who are sponsering events
$gm_query = "select DISTINCT login.Name, login.ID from l5r_plots INNER JOIN login on l5r_plots.submitter_id = login.id order by login.Name;";
$gm_result = mysql_query($gm_query) or die(mysql_error());

$gm_ids = "";
$gm_names = "";

while($gm_detail = mysql_fetch_array($gm_result, MYSQL_ASSOC))
{
	$gm_ids[] = $gm_detail['ID'];
	$gm_names[] = $gm_detail['Name'];
}

$gm_select = buildMultiSelect("", $gm_ids, $gm_names, "submitter_id[]", 5, true);

// build list of plot types
$plot_types_query = "select * from l5r_plots_types order by plot_type_name;";
$plot_types_result = mysql_query($plot_types_query) or die(mysql_error());

$plot_types_ids = "";
$plot_types_names = "";

while($plot_types_detail = mysql_fetch_array($plot_types_result, MYSQL_ASSOC))
{
	$plot_types_ids[] = $plot_types_detail['Plot_Type_ID'];
	$plot_types_names[] = $plot_types_detail['Plot_Type_Name'];
}

$plot_type_select = buildMultiSelect("", $plot_types_ids, $plot_types_names, "plot_type[]", 5, true);

// build list of plot categories
$plot_categories = array("One-Shot", "Adventure", "Setting", "Metaplot", "C/F/S");
$plot_category_select = buildMultiSelect("", $plot_categories, $plot_categories, "plot_category[]", 5, true);

// build list of plot statuses
$statuses = array("Pending", "In Progress", "Suspended", "Completed", "Was Used", "Denied");
$status = buildMultiSelect("", $statuses, $statuses, "status[]", 6, true);

// build form
$page_content = <<<EOQ
Select which criteria you wish to search on in the fields below. It will attempt to search
for each criteria that you select. All values that can be typed in, will have a wild character
appended to the end (i.e. searching for Dark in Plot Title will match &quot;Darkness&quot; and 
&quot;Dark Forest&quot;). If you want to put a wild card in front of a string, you can use * or
%.<br>
<br>
For those criteria it is possible to select multiples of (plot types, sponsers, etc.) the
returned results will return plots that match either criteria. (i.e. looking for Status of
&quot;Completed&quot; and &quot;Was Used&quot;, will return all plots that have either been
used or have been marked as used).<br>
<br>
<form method="post" action="$_SERVER[PHP_SELF]?action=plot_list">
<table border="0" cellpadding="2" cellspacing="2" class="normal_text">
  <tr valign="top">
    <td>
      <span class="highlight">Plot Name:</span>
    </td>
    <td>
      <input type="text" name="plot_name" id="plot_name" size="40" maxlength="100">
    </td>
    <td>
      <span class="highlight">Category:</span>
    </td>
    <td>
      $plot_category_select
    </td>
  </tr>
  <tr valign="top">
    <td>
      <span class="highlight">Sponsor:</span>
    </td>
    <td>
      $gm_select
    </td>
    <td>
      <span class="highlight">Plot Type:</span>
    </td>
    <td>
      $plot_type_select
    </td>
  </tr>
  <tr valign="top">
    <td>
      <span class="highlight">Status:</span>
    </td>
    <td>
      $status
    </td>
    <td>
      &nbsp;
    </td>
    <td>
      &nbsp;
    </td>
  </tr>
  <tr valign="top">
    <td>
      <span class="highlight">Start Date:</span><br>
      Date Format: YYYY-MM-DD
    </td>
    <td>
      Starts On or Before: <input type="text" name="start_date_older_than" id="start_date_older_than" size="10" maxlength="10"><br>
      Starts On or After: <input type="text" name="start_date_newer_than" id="start_date_newer_than" size="10" maxlength="10"><br>
    </td>
    <td>
      <span class="highlight">End Date:</span><br>
      Date Format: YYYY-MM-DD
    </td>
    <td>
      Ends On or Before: <input type="text" name="end_date_older_than" id="end_date_older_than" size="10" maxlength="10"><br>
      Ends On or After: <input type="text" name="end_date_newer_than" id="end_date_newer_than" size="10" maxlength="10"><br>
    </td>
  </tr>
  <tr>
    <td colspan="4" align="center">
      <input type="hidden" name="action" id="action" value="search">
      <input type="submit" value="Look Up">
      &nbsp;&nbsp;
      <input type="reset" value="Clear">
    </td>
  </tr>
</table>
</form>
EOQ;

?>