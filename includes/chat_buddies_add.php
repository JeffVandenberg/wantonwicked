<?
$page_title = "Add Buddy";
$display_form = true;
$form = "";
$java_script = "";
$extra_headers = <<<EOQ
<script src="js/xmlHTTP.js" type="text/javascript"></script>
EOQ;

// test if saving
if(isset($_POST['action']))
{
  $character_name = htmlspecialchars($_POST['character_name']);
  
  // test if the character name is already a budy
  $validated = true;
  
  $validate_query = "select buddy_id from buddies where character_name='$character_name' and login_id=$userdata[user_id];";
  $validate_result = mysql_query($validate_query) or die(mysql_error());
  
  if(mysql_num_rows($validate_result))
  {
    $validated = false;
    $alert_text = "You've already added " . stripslashes($character_name);
  }
  
  if($validated)
  {
    $insert_query = "insert into buddies (login_id, character_name) values ($userdata[user_id], '$character_name');";
    $insert_result = mysql_query($insert_query) or die(mysql_error());
    $alert_text = stripslashes($character_name) . " has been successfully added.";
    $java_script .= <<<EOQ
<script language="JavaScript">
  window.opener.location.reload();
</script>
EOQ;

  }
  
  $java_script .= <<<EOQ
<script language="JavaScript">
  alert("$alert_text");
</script>
EOQ;
}

// display
if($display_form)
{
  $java_script .= <<<EOQ
<script language="javascript">
  function checkName()
  {
    var http_request = getXmlHttpObject();
    if(http_request)
    {
      var url = "/chat.php?action=validate_name&character_name="+document.getElementById('character_name').value;
      http_request.onreadystatechange = function() { showCheckResult(http_request); };
      http_request.open('GET', url, true);
      http_request.send(null);
    }
  }
  
  function showCheckResult(http_request)
  {
    if (http_request.readyState == 4)
    {
      if (http_request.status == 200)
      {
        var result = http_request.responseText;
        
        if(result == 'valid')
        {
          alert('The name is valid');
        }
        else
        {
          alert('The name is not valid');
        }
      }
    }
  }
  
  
</script>
EOQ;

  $form = <<<EOQ
<form name="add_buddy" method="post" action="$_SERVER[PHP_SELF]?action=buddies_add">
<table border="0" cellpadding="2" cellspacing="2" class="normal_text">
  <tr>
    <td>
      Character Name:
    </td>
    <td>
      <input type="text" name="character_name" id="character_name" value="" maxlength="30" size="20">
    </td>
  </tr>
  <tr>
    <td>
      <a href="#" onClick="checkName();return false;">Check Name</a>
    </td>
    <td>
      <input type="submit" value="Add Buddy" name="action">
    </td>
  </tr>
</table>
</form>
EOQ;
}

$page_content = $form;
?>