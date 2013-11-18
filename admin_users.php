<?
$page_title = "Administrate User Permissions";
$required_permissions = array("site_admin");
$add_left = false;
include 'start_of_page.php';

// script variables
$page = "";
$name_form = "";
$permissions_form = "";

// check if updating a user's information
if(!empty($_POST['user_id']))
{
	$temp_user_id = $_POST['user_id']+0;
	// check if updating the users name
	if(!empty($_POST['update_name']))
	{
		// check if updated name is duplicate
		$name_check_query = "select * from login where name='$_POST[update_name]';";
		$name_check_result = $mysqli->query($name_check_query);
		
		if($name_check_result->num_rows)
		{
			$permissions_form = "<span class=\"red_highlight\">That User Name is already in use.</span>";
		}
		else
		{
		  // update login
			$update_query = "update login set name='$_POST[update_name]' where ID=$temp_user_id;";
			$update_result = $mysqli->query($update_query);
			
			// update phpbb_users
			$update_query = "update phpbb_users set username='$_POST[update_name]' where username='$_POST[name]';";
			$update_result = $mysqli->query($update_query);
	  }
	}
	
	// make sure that there wasn't an error on the page before proceding
	if($permissions_form == "")
	{
		$temp_may_login = (!empty($_POST['may_login'])) ? "Y" : "N";
		$temp_site_admin = (!empty($_POST['site_admin'])) ? "Y" : "N";
		$temp_view_full_db = (!empty($_POST['view_full_db'])) ? "Y" : "N";
		$temp_db_mod = (!empty($_POST['db_mod'])) ? "Y" : "N";
		$temp_fiction_mod = (!empty($_POST['fiction_mod'])) ? "Y" : "N";
		$temp_content_mod = (!empty($_POST['content_mod'])) ? "Y" : "N";
		$temp_news_mod = (!empty($_POST['news_mod'])) ? "Y" : "N";
		
		$update_query = "update permissions set may_login = '$temp_may_login', site_admin='$temp_site_admin', view_full_db='$temp_view_full_db', db_mod='$temp_db_mod', news_mod='$temp_news_mod', fiction_mod='$temp_fiction_mod', content_mod='$temp_content_mod' where id=$temp_user_id;";
		$update_result = $mysqli->query($update_query);
		
		$permissions_form = "Updated User Successfully";
	}
	$permissions_form = buildTextBox( $permissions_form, "", $border, $border_bgcolor, $border_background, $inner_background, $inner_bgcolor );
}

// check if looking for a user
if(!empty($_POST['user_name']))
{
	$permissions_query = "select login.*, permissions.* FROM login inner join permissions on login.id = permissions.id where name='$_POST[user_name]';";
	$permissions_result = $mysqli->query($permissions_query);
	
	if($permissions_result->num_rows)
	{
		$permissions_detail = $permissions_result->fetch_array(MYSQLI_ASSOC);
		
		$may_login_check = ($permissions_detail['May_Login'] == 'Y') ? "checked" : "";
		$site_admin_check = ($permissions_detail['Site_Admin'] == 'Y') ? "checked" : "";
		$news_mod_check = ($permissions_detail['News_Mod'] == 'Y') ? "checked" : "";
		$view_full_db_check = ($permissions_detail['View_Full_DB'] == 'Y') ? "checked" : "";
		$db_mod_check = ($permissions_detail['DB_Mod'] == 'Y') ? "checked" : "";
		$fiction_mod_check = ($permissions_detail['Fiction_Mod'] == 'Y') ? "checked" : "";
		$content_mod_check = ($permissions_detail['Content_Mod'] == 'Y') ? "checked" : "";
		
		$permissions_form = <<<EOQ
<form method="post" action="$_SERVER[PHP_SELF]">
<table border="0" cellspacing="2" cellpadding="2" class="normal_text">
  <tr valign="top">
    <td class="highlight">
      User ID:
    </td>
    <td>
      <input type="text" name="user_id" id="user_id" value="$permissions_detail[ID]" size="3" maxlength="5" readonly style="background=$blocked_input_color">
    </td>
    <td class="highlight">
      User Name:
    </td>
    <td>
      <input type="text" name="name" id="name" value="$permissions_detail[Name]" size="30" maxlength="50" readonly style="background=$blocked_input_color">
    </td>
  </tr>
  <tr>
    <td>
      &nbsp;
    </td>
    <td>
      &nbsp;
    </td>
    <td class="highlight">
      Modify User Name:
    </td>
    <td>
      <input type="text" name="update_name" id="update_name" value="" size="30" maxlength="50">
    </td>
  <tr>
    <td class="highlight">
      Birth Date:
    </td>
    <td>
      $permissions_detail[Birthdate]
    </td>
    <td class="highlight">
      Email:
    </td>
    <td>
      $permissions_detail[Email]
    </td>
  </tr>
  <tr>
    <td class="highlight">
      First Login:
    </td>
    <td>
      $permissions_detail[First_Login]
    </td>
    <td class="highlight">
      Last Login:
    </td>
    <td>
      $permissions_detail[Last_Login]
    </td>
  </tr>
  <tr>
    <td class="highlight">
      First IP:
    </td>
    <td>
      $permissions_detail[First_IP]
    </td>
    <td class="highlight">
      Last IP:
    </td>
    <td>
      $permissions_detail[Last_IP]
    </td>
  </tr>
  <tr>
    <td class="highlight">
      May Login:
    </td>
    <td>
      <input type="checkbox" name="may_login" id="may_login" value="Y" $may_login_check>
    </td>
    <td class="highlight">
      Site Admin:
    </td>
    <td>
      <input type="checkbox" name="site_admin" id="site_admin" value="Y" $site_admin_check>
    </td>
  </tr>
  <tr>
    <td class="highlight">
      View Full DB:
    </td>
    <td>
      <input type="checkbox" name="view_full_db" id="view_full_db" value="Y" $view_full_db_check>
    </td>
    <td class="highlight">
      Database Mod:
    </td>
    <td>
      <input type="checkbox" name="db_mod" id="db_mod" value="Y" $db_mod_check>
    </td>
  </tr>
  <tr>
    <td class="highlight">
      News Mod:
    </td>
    <td>
      <input type="checkbox" name="news_mod" id="news_mod" value="Y" $news_mod_check>
    </td>
    <td class="highlight">
      Fiction Mod:
    </td>
    <td>
      <input type="checkbox" name="fiction_mod" id="fiction_mod" value="Y" $fiction_mod_check>
    </td>
  </tr>
  <tr>
    <td class="highlight">
      Content Mod:
    </td>
    <td>
      <input type="checkbox" name="content_mod" id="content_mod" value="Y" $content_mod_check>
    </td>
    <td class="highlight">
    </td>
    <td>
    </td>
  </tr>
  <tr>
    <td colspan="4">
      <input type="submit" value="Update User">
    </td>
  </tr>
</table>
</form>
EOQ;
	}
	else
	{
		$permissions_form = "did not find $_POST[user_name]";
	}
	$permissions_form = buildTextBox( $permissions_form, "", $border, $border_bgcolor, $border_background, $inner_background, $inner_bgcolor );
}

// build input box to ask for a user name
$name_form = <<<EOQ
<form method="post" action="$_SERVER[PHP_SELF]">
User Name: <input type="text" name="user_name" id="user_name" value="" size="40" maxlength="50">
<input type="submit" value="Look Up User">
</form>
EOQ;
$name_form = buildTextBox( $name_form, "", $border, $border_bgcolor, $border_background, $inner_background, $inner_bgcolor );

// build page
$page = <<<EOQ
<div align="center">
$name_form
<br>
$permissions_form
</div>
EOQ;

echo $page;

include 'end_of_page.php';
?>