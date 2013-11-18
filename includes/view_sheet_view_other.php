<?
$page_title = "View Character Sheet";
$view_character_name = (isset($_POST['view_character_name'])) ? htmlspecialchars($_POST['view_character_name']) : "";

// test if looking up a character
if($view_character_name)
{
  // try to get character
  $character_query = <<<EOQ
SELECT login.*, wod_characters.*, gm_login.Name as ST_Name, asst_login.Name as Asst_Name
FROM ((wod_characters INNER JOIN login ON wod_characters.primary_login_id = login.id) LEFT JOIN login AS gm_login on wod_characters.last_st_updated = gm_login.id) LEFT JOIN login AS asst_login ON wod_characters.last_asst_st_updated = asst_login.id
WHERE character_name='$view_character_name';
EOQ;
  $character_result = mysql_query($character_query) or die(mysql_error());
  
  if(mysql_num_rows($character_result))
  {
    $character_detail = mysql_fetch_array($character_result, MYSQL_ASSOC);
    
    //if (( $character_detail['View_Password'] == "$_POST[viewpwd]") || ($character_detail['Show_Sheet'] == 'Y'))
	  if(($character_detail['Show_Sheet'] == 'Y') && ( $character_detail['View_Password'] == $_POST['viewpwd']))
    {
  	  // show full sheet 
			$edit_show_sheet = false;
			$edit_name = false;
			$edit_vitals = false;
			$edit_is_npc = false;
			$edit_is_dead = false;
			$edit_location = false;
			$edit_concept = false;
			$edit_description = false;
			$edit_url = false;
			$edit_equipment = false;
			$edit_public_effects = false;
			$edit_group = false;
			$edit_exit_line = false;
			$edit_is_npc = false;
			$edit_attributes = false;
			$edit_skills = false;
			$edit_perm_traits = false;
			$edit_temp_traits = false;
			$edit_powers = false;
			$edit_history = false;
			$edit_goals = false;
			$edit_login_note = false;
			$edit_experience = false;
			$show_st_notes = false;
			$view_is_asst = false;
			$view_is_st = false;
			$view_is_head = false;	
			$view_is_admin = false;
			$may_edit = false;
			$edit_cell = false;
			$calculate_derived = false;
			$character_type = $character_detail['Character_Type'];
			$character_sheet = buildWoDSheet($character_detail, $character_type, $edit_show_sheet, $edit_name, $edit_vitals, $edit_is_npc, $edit_is_dead, $edit_location, $edit_concept, $edit_description, $edit_url, $edit_equipment, $edit_public_effects, $edit_group, $edit_exit_line, $edit_is_npc, $edit_attributes, $edit_skills, $edit_perm_traits, $edit_temp_traits, $edit_powers, $edit_history, $edit_goals, $edit_login_note, $edit_experience, $show_st_notes, $view_is_asst, $view_is_st, $view_is_head, $view_is_admin, $may_edit, $edit_cell, $calculate_derived);
	  }
	  else
	  {
  	  // show partial sheet
  	  $character_sheet = <<<EOQ
<table border="0" cellpadding="2" cellspacing="2" class="normal_text">
  <tr>
    <td class="highlight">
      Character Name
    </td>
    <td>
      $character_detail[Character_Name]
    </td>
  </tr>
  <tr>    
    <td class="highlight">
      City
    </td>
    <td>
      $character_detail[City]
    </td>
  </tr>
  <tr>
    <td class="highlight">
      Description
    </td>
    <td>
      $character_detail[Description]
    </td>
  </tr>
  <tr>
    <td class="highlight">
      Public Effects
    </td>
    <td>
      $character_detail[Public_Effects]
    </td>
  </tr>
  <tr>
    <td class="highlight">
      Daily Equipment
    </td>
    <td colspan="3">
      $character_detail[Equipment_Public]
    </td>
  </tr>
</table>
EOQ;
	  }
  }
  else
  {
    $character_sheet = "That character doesn't exist.";
  }
  
}

$character_query_form = <<<EOQ
<form name="view_others" method="post" action="$_SERVER[PHP_SELF]?action=view_other">
  Enter the name and, if required, the<br> password to view another player's character sheet<br>
  <span class="highlight">Character Name:</span> <input type="text" name="view_character_name" size="25" maxlength="35"><br>
  <span class="highlight">View Password:</span> <input type="password" name="viewpwd" size="25" maxlength="30"><br>
  <input type="hidden" name="site" value="$site">
  <input type="submit" name="submit" value="View the sheet">
</form>
EOQ;

$page_content = <<<EOQ
$character_query_form
<br>
$character_sheet
EOQ;
?>