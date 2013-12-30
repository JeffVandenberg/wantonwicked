<?
$page_title = "View City Book Entries";

// page variables
$header = "";
$entry_list = "";
$page = "";
$view_pending = (isset($_GET['view_pending']) || isset($_POST['view_pending'])) ? true : false;
$view_own = ((isset($_GET['view_own'])) || isset($_POST['view_own'])) ? true : false;

// test if deleting entrys
if(isset($_POST['delete']))
{
	$entry_list = $_POST['delete'];
	while(list($key, $value) = each($entry_list))
	{
		//echo "delete: $key: $value<br>";
		$delete_query = "update city_book set is_deleted='Y' where entry_id=$value;";
		//echo $delete_query."<br>";
		$delete_result = mysql_query($delete_query) or die(mysql_error());
	}
}

$toggle_link = ($view_pending) ? "<a href=\"$_SERVER[PHP_SELF]?action=st_list\" class=\"linkmain\">View Approved Entries</a>" : "<a href=\"$_SERVER[PHP_SELF]?action=st_list&view_pending=y\" class=\"linkmain\">View Pending Entries</a>";

// build header
$header = <<<EOQ
Welcome to the Storyteller version of the City Book for WantonWicked. Feel free to browse through any parts that interest you. If you define something more in the game, please submit an entry about it so that all STs are aware of the change.<br>
<br>
<a href="$_SERVER[PHP_SELF]?action=submit">Submit New Entry</a>
&nbsp;&nbsp;&nbsp;&nbsp;
<a href="$_SERVER[PHP_SELF]?action=st_list&view_own=y">View your Pending entries</a>
&nbsp;&nbsp;&nbsp;&nbsp;
$toggle_link
EOQ;

// test if is head_gm/admin, if so, give them the link to update/delete entries
if($userdata['is_head'] || $userdata['is_admin'])
{
	// add java_script for deleting entries
  $java_script = <<<EOQ
<script language="JavaScript">
function submitForm ( )
{
	window.document.entry_list.submit();
}
</script>
EOQ;
	
	
	// build extra links
	$header .= <<<EOQ
&nbsp;&nbsp;&nbsp;&nbsp;
<a href="$_SERVER[PHP_SELF]?action=st_list" onClick="submitForm();return false;">Delete Entries</a>
EOQ;
}


// list entries sorted by category then name then by ID
$entry_query = ""; // initialize
if(!$view_pending)
{
	if($view_own)
	{
		$entry_query = "select login.Name, city_book.* from city_book left join login on city_book.submitted_by = login.id where is_approved = '' and is_deleted='N' and submitted_by=$userdata[user_id] and site_id=$userdata[site_id] order by city, entry_category, entry_name, entry_id";
	}
	else
	{
		// view only approved
		$entry_query = "select login.Name, city_book.* from city_book left join login on city_book.submitted_by = login.id where is_approved='Y' and is_deleted='N' and site_id=$userdata[site_id] order by city, entry_category, entry_name, entry_id;";
	}
}
else
{
	// view those not approved
	$entry_query = "select login.Name, city_book.* from city_book left join login on city_book.submitted_by = login.id where is_approved !='Y' and is_deleted='N' and site_id=$userdata[site_id] order by is_approved desc, city, entry_category, entry_name, entry_id;";
}

$entry_result = mysql_query($entry_query) or die(mysql_error());

$col_span = ($userdata['is_head'] || $userdata['is_admin']) ? 7 : 5;

$admin_col = ($userdata['is_head'] || $userdata['is_admin']) ? "<th>&nbsp;</th>" : "";

$status_col =  ($view_pending) ? "<th>Status</th>" : "";
$hidden_view_pending = ($view_pending) ? '<input type="hidden" name="view_pending" id="view_pending" value="y">' : ""; // used to allow the user to keep on the pending page when deleting entries

$entry_list = <<<EOQ
<form name="entry_list" id="entry_list" method="post" action="$_SERVER[PHP_SELF]?action=st_view">
<table border="0" cellspacing="2" cellpadding="2" class="normal_text">
  <tr>
    <th class="contentheading" colspan="$col_span">
      AnT City Book
      $hidden_view_pending
    </th>
  </tr>
  <tr bgcolor="#000000">
    $admin_col
    <th>
    	City
    </th>
    <th>
      Category
    </th>
    <th>
      Name
    </th>
    <th>
      Submitted By
    </td>
    <th>
      Submitted On
    </th>
    $status_col
  </tr>
EOQ;

// cycle through rows
$row = 0;
while($entry_detail = mysql_fetch_array($entry_result, MYSQL_ASSOC))
{
	// set color
	$row_color = (($row++)%2) ? "#443a33" : "";
	
	// add delete box if headgm or better
	$admin_col = "";
	if($userdata['is_head'] || $userdata['is_admin'])
	{
		$admin_col = <<<EOQ
	<td>
	  <input type="checkbox" name="delete[]" id="delete[]" value="$entry_detail[Entry_ID]">
	</td>
EOQ;
	}
	
	$status_col = "";
	if($view_pending)
	{
		$status_col = <<<EOQ
		<td>
		  $entry_detail[Is_Approved]
		</td>
EOQ;
	}
	
	$entry_list .= <<<EOQ
	<tr bgcolor="$row_color">
	  $admin_col
	  <td>
	  	$entry_detail[City]
	  </td>
	  <td>
	    $entry_detail[Entry_Category]
	  </td>
	  <td>
	    <a href="$_SERVER[PHP_SELF]?action=st_view&entry_id=$entry_detail[Entry_ID]">$entry_detail[Entry_Name]</a>
	  </td>
	  <td>
	    $entry_detail[Name]
	  </td>
	  <td>
	    $entry_detail[Submitted_On]
	  </td>
	  $status_col
	</tr>
EOQ;
}

$entry_list .= "</table></form>";

$page_content = <<<EOQ
$java_script
$header
<br>
<br>
$entry_list
EOQ;
?>

