<?
$page_title = "Look up Character Names";
$page_content = "Look up Character";

$character_name = "";
$result_set = "";

// do search
if ( !empty($_POST['character_name']) )
{
  $character_name = str_replace( "*", "%", $_POST['character_name'] );
  
  //$character_query = "select * from characters where character_name like '$character_name' and is_deleted='n' and city='The City';";
  $character_query = "select * from characters where character_name like '$character_name' and is_deleted='n';";
  $character_result = mysql_query($character_query) or die(mysql_error());
  //echo "$character_query : " . mysql_num_rows($character_result) . "<br>";
  
  if(mysql_num_rows($character_result))
  {
    $result_set = <<<EOQ
<br><br>
<table border="0" cellpadding="2" cellspacing="2" class="normal_text">
  <tr>
    <th>
      Character Name
    </th>
    <th>
      City
    </th>
    <th>
      Character Type
    </th>
    <th>
    </th>
  </tr>
EOQ;

    $i = 0;
    while($character_detail = mysql_fetch_array($character_result, MYSQL_ASSOC))
    {
      $row_color = (($i++)%2) ? "#443f33" : "";
      
      $result_set .= <<<EOQ
  <tr bgcolor="$row_color">
    <td>
      $character_detail[Character_Name]
    </td>
    <td>
      $character_detail[City]
    </td>
    <td>
      $character_detail[Character_Type]
    </td>
    <td>
      <a href="view_sheet.php?action=st_view_xp&view_character_id=$character_detail[id]">View Character</a>
    </td>
  </tr>
EOQ;
    }
    
    $result_set .= "</table>";
    $character_name = stripslashes($character_name);
  }
  else
  {
    $result_set = "<br><br>No characters found.";
  }
}

// detail form
$search_form = <<<EOQ
<form method="post" action="$_SERVER[PHP_SELF]?action=character_name_lookup">
  Partial Character Name: <input type="text" name="character_name" value="$character_name" size="20" maxlength="35">
  <input type="submit" value="Find Characters"><br>
</form>
Valid wildcards are * or %
EOQ;

$page_content = $search_form . $result_set;
?>