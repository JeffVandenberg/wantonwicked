<?
$page_title = "Profile -&gt; Character Lookup";
$profile_name = (isset($_POST['profile_name'])) ? $_POST['profile_name'] : "";
$profile_name = (isset($_GET['profile_name'])) ? $_GET['profile_name'] : $profile_name;

if ($profile_name != "")
{
	$character_select="The Characters attached to $profile_name's profile are:<br>";
	$character_query = <<<EOQ
SELECT C.character_name, C.is_deleted
FROM (login INNER JOIN login_character_index as lci ON login.id = lci.login_id) INNER JOIN characters AS C ON C.id = lci.character_id
WHERE login.Name='$profile_name' and C.is_deleted='N'
ORDER BY C.character_name;
EOQ;

	$detail_query = <<<EOQ
SELECT *
FROM login
WHERE Name='$profile_name';
EOQ;

	$character_result = mysql_query($character_query) or die(mysql_error());
	$detail_result = mysql_query($detail_query) or die(mysql_error());

	if ( mysql_num_rows($detail_result) )
	{
		$details = mysql_fetch_array($detail_result, MYSQL_ASSOC);
		
		if(mysql_num_rows($character_result))
		{
			$character_select .= "<form name=\"sanction\" method=\"post\" action=\"view_sheet.php?action=st_view_xp\" target=\"_blank\">";
			$character_select .= "<select name=\"view_character_name\">";
			while ($name = mysql_fetch_array($character_result, MYSQL_ASSOC))
			{
				$character_select .= "<option value=\"$name[character_name]\">$name[character_name]</option>";
			}
			
			$character_select.="</select>\n";
			$character_select.="<input type=\"submit\" value=\"View Character Sheet\">";
			$character_select.="</form>\n";
			
			// get player to character stats
			
			// how many currently sanctioned PCs they have
			$sanced_query = "select count(character_id) as SancedPCs from characters where primary_login_id = $details[ID] and is_sanctioned='Y' and is_deleted='N' and is_npc='n';";
			$sanced_result = mysql_query($sanced_query) or die(mysql_error());
			$sanced_detail = mysql_fetch_array($sanced_result, MYSQL_ASSOC);
			$character_select .= "Sanced PCs: $sanced_detail[SancedPCs]<br>";
			
			
			// how many desanctioned (non-deleted) PCs they have
			$sanced_query = "select count(character_id) as SancedPCs from characters where primary_login_id = $details[ID] and is_sanctioned='N' and is_deleted='N' and is_npc='n';";
			$sanced_result = mysql_query($sanced_query) or die(mysql_error());
			$sanced_detail = mysql_fetch_array($sanced_result, MYSQL_ASSOC);
			$character_select .= "UnSanced PCs: $sanced_detail[SancedPCs]<br>";
			
			// how many deleted PCs they have
			$sanced_query = "select count(character_id) as SancedPCs from characters where primary_login_id = $details[ID] and is_deleted='Y' and is_npc='n';";
			$sanced_result = mysql_query($sanced_query) or die(mysql_error());
			$sanced_detail = mysql_fetch_array($sanced_result, MYSQL_ASSOC);
			$character_select .= "Deleted Characters: $sanced_detail[SancedPCs]<br>";
			
			$character_select .= "<br>";
			
			
		}
		else
		{
			$character_select .= "Player has no Characters.<br>";
		}

		$character_select .= <<<EOQ
ID #: $details[ID]<br>
Email: $details[Email]<br>
First IP: $details[First_IP]<br>
Last IP: $details[Last_IP]<br>
First Login: $details[First_Login]<br>
Last Login: $details[Last_Login]<br> 
EOQ;

	}
	else
	{
		$character_select="Profile Name was not found.<br>\n";
	}

}
else
{
	$character_select="No login selected yet";
}

$page_content = <<<EOQ
<table width="100%" border="0" cellpadding="2" cellspading="2" class="normal_text">
  <tr valign="top">
    <td width="45%">
      <form name="lookup" method="post" action="$_SERVER[PHP_SELF]?action=profile_lookup">
	      Profile Name:<input type="text" name="profile_name" size="30" maxlength="60"><BR>
	      <BR>
	      <input type="submit" name="Lookup Profile" value="Lookup Profile">
	    </form>
    </td>
    <td width="55%" align="center">
      $character_select
    </td>
  </tr>
</table>
EOQ;

?>