<?
$page_title = "Character Profile Transfer";

$result = "";

// test if information is provided
if(isset($_POST['character_name']) && isset($_POST['profile_name']))
{
  // make sure that the character exists 
  $character_name = htmlspecialchars($_POST['character_name']);
  $character_query = "select * from characters where character_name = '$character_name';";
  $character_result = mysql_query($character_query) or die(mysql_error());
  
  // make sure that the profile exists
  $profile_name = htmlspecialchars($_POST['profile_name']);
  $login_query = "select * from login where name = '$profile_name';";
  $login_result = mysql_query($login_query) or die(mysql_error());
  
  if(mysql_num_rows($character_result) && mysql_num_rows($login_result))
  {
    $character_detail = mysql_fetch_array($character_result, MYSQL_ASSOC);
    $login_detail = mysql_fetch_array($login_result, MYSQL_ASSOC);
    
    // delete the reference in login_character_index to the prior login id
    $lci_delete_query = "delete from login_character_index where login_id = $character_detail[Primary_Login_ID] and character_id = $character_detail[id];";
    //echo $lci_delete_query ."<br>";
    $lci_delete_result = mysql_query($lci_delete_query) or die(mysql_error());
    
    // set the login's id as the primary login id
    $char_update_query = "update characters set primary_login_id = $login_detail[ID] where character_id = $character_detail[id];";
    //echo $char_update_query ."<br>";
    $char_update_result = mysql_query($char_update_query) or die(mysql_error());
    
    // add the new reference to login_character_index
    $lci_insert_query = "insert into login_character_index values (null, $login_detail[ID], $character_detail[id]);";
    //echo $lci_insert_query ."<br>";
    $lci_insert_result = mysql_query($lci_insert_query) or die(mysql_error());
    
    $result .= "$character_name has been moved to $profile_name.<br>";
  }
  else
  {
    if(!mysql_num_rows($character_result))
    {
      $result .= "$character_name wasn't found.<br>";
    }
    
    if(!mysql_num_rows($login_result))
    {
      $result .= "$profile_name wasn't found.<br>";
    }
  }
}

// create form
$form = <<<EOQ
<form name="name change" method="post" action="$_SERVER[PHP_SELF]?action=profile_transfer">
  <table width="100%" border="0" cellspacing="2" cellpadding="2" class="normal_text">
    <tr>
      <td>Character Name:
        <input type="text" name="character_name" size="25" maxlength="30">
      </td>
      <td>Attach to:
        <input type="text" name="profile_name" size="25" maxlength="30">
      </td>
    </tr>
    <tr>
      <td colspan="2">
        <input type="submit" name="Submit" value="Move Character">
      </td>
    </tr>    
  </table>
</form>
EOQ;

$page_content = $form . $result;

?>