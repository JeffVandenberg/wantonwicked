<?
$page_title = "GM Permissions Page";
$add_bars = false;
include 'start_of_page_gm.php';

$sheet = "";

if ($_SESSION['is_head'] || $_SESSION['is_admin'])
{
  // update permissions
  if (!empty($_POST['action']))
  {
	  if ($_POST['action'] == "update")
    {
	    $num_of_rows = $_POST['num_of_rows'];

	    for ($i = 1; $i <= $num_of_rows; $i++)
	    {
		    // is the character id field not blank
		    if (!empty($_POST["login_id$i"]))
		    {
			    $lookup_login_id = $_POST["login_id".$i];
			    if ($_POST['remove_permissions'.$i] == "Y")
			    {
				    // delete their permissions
				    //echo "Deleting Permissions for $lookup_login_id<br>";
				    $delete_query = "delete from gm_permissions where id=$lookup_login_id and site_ID='$_SESSION[site_id]';";
				    $delete_result = $mysqli->query($delete_query);

				    $update_login_query = "update login set default_site='' where id=$lookup_login_id;";
				    $update_login_result = $mysqli->query($update_login_query);
			    }
			    else
			    {
				    // test if they are new or existing
				    $gm_check_query = "select * from gm_permissions where ID=$lookup_login_id and site_id='$_SESSION[site_id]';";
				    $gm_check_result = $mysqli->query($gm_check_query);

				    if ($gm_check_result->num_rows)
				    {
					    // update their permissions
					    $update_is_asst = $_POST["is_asst$i"];
					    $update_is_gm = $_POST["is_gm$i"];
					    $update_is_head = $_POST["is_head$i"];
					    $update_letters_moderator = $_POST["letters_moderator$i"];
					    
					    $update_is_admin = "";
					    if(!empty($_POST['is_admin']))
					    {
						    $temp_is_admin = $_POST['is_admin'];
						    $update_is_admin = ", is_admin = '$temp_admin'";
					    }

					    $update_query = "update gm_permissions set is_asst = '$update_is_asst', is_gm='$update_is_gm', is_head='$update_is_head', letters_moderator='$update_letters_moderator' $update_is_admin where ID=$lookup_login_id and site_id='$_SESSION[site_id]';";
					    //echo $update_query."<br>";
					    $update_result = $mysqli->query($update_query);
				    }
				    else
				    {
					    // insert them into the table
					    $insert_is_asst = $_POST["is_asst$i"];
					    $insert_is_gm = $_POST["is_gm$i"];
					    $insert_is_head = $_POST["is_head$i"];
					    $insert_letters_moderator = $_POST["letters_moderator$i"];
					    $insert_is_admin = $_POST["is_admin$i"];

					    $insert_query = "insert into gm_permissions values ($lookup_login_id, '$_SESSION[site_id]', '$insert_is_asst', '$insert_is_gm', '$insert_is_head', '$insert_letters_moderator', '$insert_is_admin');";
					    //echo $insert_query."<br>";
					    $insert_result = $mysqli->query($insert_query);

					    $update_login_query = "update login set default_site='$_SESSION[site_id]' where id=$lookup_login_id;";
				    	$update_login_result = $mysqli->query($update_login_query);
				    }
		      }
		    }
	    }
    }
  }

  // show permissions
  $num_of_rows = 0;

  $permissions_query = <<<EOQ
SELECT Login.ID, Login.Name, GM_Permissions.*
  FROM Login INNER JOIN GM_Permissions ON Login.ID = GM_Permissions.ID
  WHERE GM_Permissions.Site_ID = '$_SESSION[site_id]'
  ORDER BY Login.Name;
EOQ;
  $permissions_result = $mysqli->query($permissions_query) or die(mysl_error());

  // starts to build up table
  $permissions_table = <<<EOQ
<form name="permissions_table" method="post" action="$_SERVER[PHP_SELF]">
<table width="100%" border="1" cellspacing="2" cellpadding="2" class="normal_text">
  <tr>
    <th>
      Remove
    </th>
    <th>
      Login Name
    </th>
    <th>
      Is Asst
    </th>
    <th>
      Is GM
    </th>
    <th>
      Is Head
    </th>
    <th>
      Letters<br>
      Moderator
    </th>
    <th>
      Is Admin
    </th>
  </tr>\n
EOQ;

  while ( $permissions_details = $permissions_result->fetch_array(MYSQLI_ASSOC) )
  {
	  $num_of_rows++;

	  $may_remove = ($permissions_details['Is_Admin'] == 'Y' && !$_SESSION['is_admin']) ? "disabled" : "";

	  $is_asst_yes_check = ($permissions_details['Is_Asst'] == 'Y') ? "checked" : "";
	  $is_asst_no_check = ($permissions_details['Is_Asst'] == 'N') ? "checked" : "";
	  $is_gm_yes_check = ($permissions_details['Is_GM'] == 'Y') ? "checked" : "";
	  $is_gm_no_check = ($permissions_details['Is_GM'] == 'N') ? "checked" : "";
	  $is_head_yes_check = ($permissions_details['Is_Head'] == 'Y') ? "checked" : "";
	  $is_head_no_check = ($permissions_details['Is_Head'] == 'N') ? "checked" : "";
	  $letters_mod_yes_check = ($permissions_details['Letters_Moderator'] == 'Y') ? "checked" : "";
	  $letters_mod_no_check = ($permissions_details['Letters_Moderator'] == 'N') ? "checked" : "";
	  $is_admin_yes_check = ($permissions_details['Is_Admin'] == 'Y') ? "checked" : "";
	  $is_admin_no_check = ($permissions_details['Is_Admin'] == 'N') ? "checked" : "";
	  $enable_admin_check = ($_SESSION['is_admin']) ? "" : "disabled";

	  $permissions_table .= <<<EOQ
  <tr valign="top">
    <td align="center">
      Yes:
      <input type="radio" name="remove_permissions$num_of_rows" value="Y" $may_remove><br>
      No:
      <input type="radio" name="remove_permissions$num_of_rows" value="N" checked $may_remove>
    </td>
    <td>
      $permissions_details[Name]
      <input type="hidden" name="login_id$num_of_rows" value="$permissions_details[ID]">
    </td>
    <td align="center">
      Yes:
      <input type="radio" name="is_asst$num_of_rows" value="Y" $is_asst_yes_check><br>
      No:
      <input type="radio" name="is_asst$num_of_rows" value="N" $is_asst_no_check>
    </td>
    <td align="center">
      Yes:
      <input type="radio" name="is_gm$num_of_rows" value="Y" $is_gm_yes_check><br>
      No:
      <input type="radio" name="is_gm$num_of_rows" value="N" $is_gm_no_check>
    </td>
    <td align="center">
      Yes:
      <input type="radio" name="is_head$num_of_rows" value="Y" $is_head_yes_check><br>
      No:
      <input type="radio" name="is_head$num_of_rows" value="N" $is_head_no_check>
    </td>
    <td align="center">
      Yes:
      <input type="radio" name="letters_moderator$num_of_rows" value="Y" $letters_mod_yes_check><br>
      No:
      <input type="radio" name="letters_moderator$num_of_rows" value="N" $letters_mod_no_check>
    </td>
    <td align="center">
      Yes:
      <input type="radio" name="is_admin$num_of_rows" value="Y" $is_admin_yes_check $enable_admin_check><br>
      No:
      <input type="radio" name="is_admin$num_of_rows" value="N" $is_admin_no_check $enable_admin_check>
    </td>
  </tr>\n
EOQ;
  }

  // add 3 blank lines for new GMs
  $login_names_query = "select * from login order by Name";
  $login_names_result = $mysqli->query($login_names_query);

  $login_select = "";
  while ($login_names_detail = $login_names_result->fetch_array(MYSQLI_ASSOC))
  {
	  $login_select .= "<option value=\"$login_names_detail[ID]\">$login_names_detail[Name]</option>";
  }

  for ($i = 0; $i < 3; $i++)
  {
	  $enable_admin_check = ($_SESSION['is_admin']) ? "" : "disabled";

	  $num_of_rows++;

    $local_select = "<select name=\"login_id$num_of_rows\"><option value=\"\"></option>" . $login_select . "</select>";

	  $permissions_table .= <<<EOQ
  <tr>
    <td align="center">
      Yes:
      <input type="radio" name="remove_permissions$num_of_rows" value="Y"><br>
      No:
      <input type="radio" name="remove_permissions$num_of_rows" value="N" checked>
    </td>
    <td>
      $local_select
    </td>
    <td align="center">
      Yes:
      <input type="radio" name="is_asst$num_of_rows" value="Y"><br>
      No:
      <input type="radio" name="is_asst$num_of_rows" value="N" checked>
    </td>
    <td align="center">
      Yes:
      <input type="radio" name="is_gm$num_of_rows" value="Y"><br>
      No:
      <input type="radio" name="is_gm$num_of_rows" value="N" checked>
    </td>
    <td align="center">
      Yes:
      <input type="radio" name="is_head$num_of_rows" value="Y"><br>
      No:
      <input type="radio" name="is_head$num_of_rows" value="N" checked>
    </td>
    <td align="center">
      Yes:
      <input type="radio" name="letters_moderator$num_of_rows" value="Y"><br>
      No:
      <input type="radio" name="letters_moderator$num_of_rows" value="N" checked>
    </td>
    <td align="center">
      Yes:
      <input type="radio" name="is_admin$num_of_rows" value="Y" $enable_admin_check><br>
      No:
      <input type="radio" name="is_admin$num_of_rows" value="N" checked $enable_admin_check>
    </td>
  </tr>\n
EOQ;
  }

  $permissions_table .= <<<EOQ
  <tr>
    <td colspan="7">
      <input type="hidden" name="action" value="update">
      <input type="hidden" name="num_of_rows" value="$num_of_rows">
      <input type="submit" value="Update">
    </td>
  </tr>
</table>
</form>\n
EOQ;

  $sheet = buildTextBox( $permissions_table, "100%", $border, $border_bgcolor, $border_background, $inner_background, $inner_bgcolor );

}
else
{
	$message = <<<EOQ
$_SESSION[user_name] at $_SERVER[REMOTE_ADDR] has recently tried to access $_SERVER[PHP_SELF], and did not have the proper permissions to access the page.  If this error persists, you investigate this error, extensively.
EOQ;

  logError("Critical", $message, $_SERVER['REMOTE_ADDR']);

  mail("admin@fiveringsonline.com", "Error on Site", $message);

	$sheet = <<<EOQ
<span class="red highlight">You are in a section of the site that you do not have permissions to. Please do not try again. Your attempt has been logged, and the administration will be made aware of it.
EOQ;
  $sheet = buildTextBox( $sheet, "", $border, $border_bgcolor, $border_background, $inner_background, $inner_bgcolor );
}

echo $sheet;
include('end_of_page.php');
?>