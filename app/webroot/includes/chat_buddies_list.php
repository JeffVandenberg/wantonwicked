<?
$page_title = "Buddies for for $userdata[user_name]";

// test if the list is being updated
if(isset($_POST['action']))
{
	if($_POST['action'] == 'update')
	{
		$note_list = $_POST['delete'];
		while(list($key, $value) = each($note_list))
		{
			//echo "delete: $key: $value<br>";
			// delete note from system
			$delete_query = "delete from buddies where buddy_id=$value;";
			//echo "$delete_query<br>";
			$delete_result = mysql_query($delete_query) or die(mysql_error());
		}
	}
}

// display the list
// query database
$buddy_query = "select * from buddies where login_id = $userdata[user_id] order by Character_Name;";
//echo $note_query."<br>";
$buddy_result = mysql_query($buddy_query) or die(mysql_error());

// build list
$buddies_list = <<<EOQ
This buddy list will be shared across all of your characters on Wanton Wicked.<br>
<br>
<a href="$_SERVER[PHP_SELF]?action=buddies_add" onClick="window.open('$_SERVER[PHP_SELF]?action=buddies_add', '', 'width=300,height=130,resizable,scrollbars');return false;">Add Buddy</a>
&nbsp;&nbsp;&nbsp;&nbsp;
<a href="#" onClick="submitForm();return false;">Delete Buddies</a>
<br>
<form name="buddy_list" id="buddy_list" method="post" action="$_SERVER[PHP_SELF]?action=buddies_list">
<table border="0" cellpadding="2" cellspacing="2" class="normal_text">
  <tr bgcolor="#000000">
    <th>&nbsp</th>
    <th>Character Name</th>
  </tr>
EOQ;

$row = 0;
while ($buddy_detail = mysql_fetch_array($buddy_result, MYSQL_ASSOC))
{
  $row_color = (($row++)%2) ? "#443a33" : "";
  
  $buddies_list .= <<<EOQ
	<tr bgcolor="$row_color">
    <td>
	    <input type="checkbox" name='delete[]' id='delete[]' value="$buddy_detail[Buddy_ID]">
	  </td>
	  <td>
	  	$buddy_detail[Character_Name]
	  </td>
	</tr>
EOQ;
}
  
// close form
$buddies_list .= <<<EOQ
</table>
<input type="hidden" name="action" id="action" value="update">
</form>
</div>
EOQ;

// add js for the page
$java_script .= <<<EOQ
<script language="JavaScript">
function submitForm ( )
{
	window.document.buddy_list.submit();
}
</script>
EOQ;

$page_content = $buddies_list;

?>