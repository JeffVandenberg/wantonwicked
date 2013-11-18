<?
$page_title = "List Plots";

// page variables
$page = "";
$plot_list = "";
$message = "";
$admin_utilities = "";

// form variables
$start_date = date('Y-m-d');
$end_date = date ('Y-m-d', mktime (0, 0, 0, date('m')+3, date('d'), date('Y')));
$plot_name = "";
$status = array("In Progress", "Pending");
$start_date_older_than = "";
$start_date_newer_than = "";
$end_date_older_than = "";
$end_date_newer_than = "";
$submitter_id = "";
$plot_category = array("One-Shot", "Adventure", "Setting", "Metaplot");
$plot_type = "";

// test if updating what one is searching for
if(isset($_POST['action']))
{
	// set search variables based on what has been selected
	if($_POST['action'] == 'search')
	{
		$plot_name = (!empty($_POST['plot_name'])) ? $_POST['plot_name'] : "";
		$status = (!empty($_POST['status'])) ? $_POST['status'] : "";
		$plot_type = (!empty($_POST['plot_type'])) ? $_POST['plot_type'] : "";
		$plot_category = (!empty($_POST['plot_category'])) ? $_POST['plot_category'] : "";
		$submitter_id = (!empty($_POST['submitter_id'])) ? $_POST['submitter_id'] : "";
		$start_date_newer_than = (!empty($_POST['start_date_newer_than'])) ? $_POST['start_date_newer_than'] : "";
		$start_date_older_than = (!empty($_POST['start_date_older_than'])) ? $_POST['start_date_older_than'] : "";
		$end_date_newer_than = (!empty($_POST['end_date_newer_than'])) ? $_POST['end_date_newer_than'] : "";
		$end_date_older_than = (!empty($_POST['end_date_older_than'])) ? $_POST['end_date_older_than'] : "";
	}
}

// build message
$message = <<<EOQ
This page and it's supporting pages are designed to help the Storytellers keep track
of what plots are currently pending, active, and have been completed in one fashion
or another.<br>
<br>
<a href="$_SERVER[PHP_SELF]?action=plot_add">Add Plot</a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
<a href="$_SERVER[PHP_SELF]?action=plot_search">Search Plots</a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
<a href="$_SERVER[PHP_SELF]?action=plot_type_descriptions" target="_blank">View Plot Type Descriptions</a><br>
EOQ;

// build admin utilities and set other admin only values
if($userdata['is_head'] || $userdata['is_admin'])
{
	// build utilities
	$admin_utilities = "<a href=\"$_SERVER[PHP_SELF]?action=plot_types_admin\" onClick=\"window.open('$_SERVER[PHP_SELF]?action=plot_types_admin', 'plot_types_admin', 'width=400,height=300,scrolling,resizable');return false;\" class=\"linkmain\">Add/Edit Plot Types</a><br><br>";
}

// build plot list
$plot_list_query = <<<EOQ
SELECT DISTINCT l5r_plots.*, submitter.Name
  FROM ((l5r_plots LEFT JOIN login AS submitter ON l5r_plots.Submitter_ID = submitter.ID)
  LEFT JOIN l5r_plots_types_index ON l5r_plots.plot_id = l5r_plots_types_index.plot_id)
  LEFT JOIN l5r_plots_types ON l5r_plots_types.plot_type_id = l5r_plots_types_index.plot_type_id
 WHERE l5r_plots.plot_id > 0
EOQ;

if($plot_name != "")
{
	$plot_list_query .= " AND plot_name LIKE '$plot_name%'";
}

if($start_date_newer_than != "")
{
	$plot_list_query .= " AND start_date >= '$start_date_newer_than'";
}

if($start_date_older_than != "")
{
	$plot_list_query .= " AND start_date <= '$start_date_older_than'";
}

if($end_date_newer_than != "")
{
	$plot_list_query .= " AND end_date >= '$end_date_newer_than'";
}

if($end_date_older_than != "")
{
	$plot_list_query .= " AND end_date <= '$end_date_older_than'";
}

if($status != "")
{
	$plot_list_query .= " and ( ";
	while(list($key, $value) = each($status))
	{
		$plot_list_query .= " status = '$value' or ";
	}
	$plot_list_query = substr($plot_list_query, 0, strlen($plot_list_query) - 3);
	$plot_list_query .= " )";
}

if($submitter_id != "")
{
	$plot_list_query .= " and ( ";
	while(list($key, $value) = each($submitter_id))
	{
		$plot_list_query .= " submitter_id = '$value' or ";
	}
	$plot_list_query = substr($plot_list_query, 0, strlen($plot_list_query) - 3);
	$plot_list_query .= " )";
}

if($plot_category != "")
{
	$plot_list_query .= " and ( ";
	while(list($key, $value) = each($plot_category))
	{
		$plot_list_query .= " plot_category = '$value' or ";
	}
	$plot_list_query = substr($plot_list_query, 0, strlen($plot_list_query) - 3);
	$plot_list_query .= " )";
}

if($plot_type != "")
{
	$plot_list_query .= " and ( ";
	while(list($key, $value) = each($plot_type))
	{
		$plot_list_query .= " l5r_plots_types.plot_type_id = $value or ";
	}
	$plot_list_query = substr($plot_list_query, 0, strlen($plot_list_query) - 3);
	$plot_list_query .= " )";
}

$plot_list_query .= " ORDER BY l5r_plots.status, l5r_plots.Plot_Name;";

//echo $plot_list_query."<br>";

$plot_list_result = mysql_query($plot_list_query) or die(mysql_error());

if(mysql_num_rows($plot_list_result))
{
	// add header row
	$row = 0;
	$plot_list = <<<EOQ
<table border="0" cellpadding="2" cellspacing="2" class="normal_text">
  <tr bgcolor="#000000">
    <th>
      Name
    </td>
    <th>
      Sponser
    </th>
    <th>
      Plot Type(s)
    </th>
    <th>
      Category
    </td>
    <th>
      Status
    </th>
    <th>
      Start Date
    </th>
    <th>
      End Date
    </th>
  </tr>
EOQ;

  // add each row
  while($plot_list_detail = mysql_fetch_array($plot_list_result, MYSQL_ASSOC))
  {
	  // generate any needed information for row
	  // color of row
	  $row_color = (($row++)%2) ? "#554a44" : "";
	  
	  // list of plot types
	  $plot_type_list = "";
	  $plot_type_list_query = "select l5r_plots_types.Plot_Type_Name from l5r_plots_types left join l5r_plots_types_index on l5r_plots_types.plot_type_id = l5r_plots_types_index.plot_type_id where l5r_plots_types_index.plot_id = $plot_list_detail[Plot_ID];";
	  $plot_type_list_result = mysql_query($plot_type_list_query);
	  
	  while($plot_type_list_detail = mysql_fetch_array($plot_type_list_result, MYSQL_ASSOC))
	  {
		  $plot_type_list .= "$plot_type_list_detail[Plot_Type_Name], ";
	  }
	  $plot_type_list = substr($plot_type_list, 0, strlen($plot_type_list)-2);
	  
	  $plot_list .= <<<EOQ
	<tr bgcolor="$row_color">
	  <td>
	    <a href="$_SERVER[PHP_SELF]?action=plot_view&plot_id=$plot_list_detail[Plot_ID]">$plot_list_detail[Plot_Name]</a>
	  </td>
	  <td>
	    $plot_list_detail[Name]
	  </td>
	  <td>
	    $plot_type_list
	  </td>
	  <td>
	    $plot_list_detail[Plot_Category]
	  </td>
	  <td>
	    $plot_list_detail[Status]
	  </td>
	  <td>
	    $plot_list_detail[Start_Date]
	  </td>
	  <td>
	    $plot_list_detail[End_Date]
	  </td>
	</tr>
	<tr bgcolor="$row_color">
	  <td colspan="7">
	    Synopsis: $plot_list_detail[Synopsis]
	  </td>
	</tr>
EOQ;
  }
  $plot_list .= "</table>";
}
else
{
	$plot_list = <<<EOQ
<span class="highlight">No Plots were found that matched that criteria.</span>
EOQ;
}

$page_content = <<<EOQ
$message
<br>
$admin_utilities
$plot_list
EOQ;

?>