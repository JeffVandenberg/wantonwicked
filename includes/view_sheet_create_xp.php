<?php
/* @var array $userdata */
ini_set('display_errors', 1);
$page_title = "Create Character";
$contentHeader = $page_title;

$show_form = true; 
$error = "";

if(isset($_POST['character_name']))
{
    $show_form = false;
	if($userdata['user_id'] != 1)
	{
		$edit_show_sheet = true;
		$edit_name = true;
		$edit_vitals = true;
		$edit_is_npc = true;
		$edit_is_dead = true;
		$edit_location = true;
		$edit_concept = true;
		$edit_description = true;
		$edit_url = true;
		$edit_equipment = true;
		$edit_public_effects = true;
		$edit_group = true;
		$edit_exit_line = true;
		$edit_attributes = true;
		$edit_skills = true;
		$edit_perm_traits = true;
		$edit_temp_traits = true;
		$edit_powers = true;
		$edit_history = true;
		$edit_goals = true;
		$edit_login_note = false;
		$edit_experience = false;
		$show_st_notes = false;
		$view_is_asst = false;
		$view_is_st = false;
		$view_is_head = false;
		$view_is_admin = false;
		$may_edit = true;
		$edit_cell = true;
  	    $error = updateWoDSheetXP($_POST, $edit_show_sheet, $edit_name, $edit_vitals, $edit_is_npc, $edit_is_dead, $edit_location, $edit_concept, $edit_description, $edit_url, $edit_equipment, $edit_public_effects, $edit_group, $edit_exit_line, $edit_attributes, $edit_skills, $edit_perm_traits, $edit_temp_traits, $edit_powers, $edit_history, $edit_goals, $edit_login_note, $edit_experience, $show_st_notes, $view_is_asst, $view_is_st, $view_is_head, $view_is_admin, $may_edit, $edit_cell);

        $page_content .= <<<EOQ
If you see any errors please manually send an email to jeffvandenberg@gmail.com notifying me
of your character's name and the exact message of the error text.<br>
<br>
EOQ;
        if($error == '')
        {
            if(!$_POST['character_id'])
            {
                $page_content .= "$_POST[character_name] has been attached to your profile $userdata[username].<br>";
                $java_script .= <<<EOQ
<script type="text/javascript">
  window.opener.location.reload(true);
  //window.opener.focus();
  //window.close();
</script>
EOQ;
            }
            else
            {
                $page_content .= "$_POST[character_name] has been updated.<br>";
            }
        }
        else
        {
            $page_content .= $error;
        }

	}
	else
	{
		$page_content = <<<EOQ
You are not logged into the site, please log in again, and resubmit the character.<br/>
<br/>
<a href="/">Wanton Wicked Home Page</a>
EOQ;
	}
}


if($show_form)
{
  // load for an AJAX Style solution
  $java_script .= <<<EOQ
<script src="js/xmlHTTP.js" type="text/javascript"></script>
<script src="js/create_character_xp.js" type="text/javascript"></script>
<script type="text/javascript">
    $(function() {
        loadNew(true);
    });
</script>
EOQ;
  
	$page_content .= <<<EOQ
When creating a character, please make sure you have reviewed
<a href="wiki/index.php?n=GameRef.CharacterCreation" target="_blank">Character Creation Guidelines</a>.<br>
<br>
<br>
Please make sure you have read over the
<a href="wiki/index.php?n=GameRef.CharacterSheetFAQ" target="_blank">Character Sheet FAQ</a>.
 his character sheet is based off of XP Pools rather than dot allocation.
<br>
<span class="error_text">$error</span>
<br>
<form name="character_sheet" id="character_sheet" method="post" action="$_SERVER[PHP_SELF]?action=create_xp">
<div align="center" name="charSheet" id="charSheet">Loading Character Sheet...
</div>
</form>
EOQ;
}
else
{
  //$page_content = "Thank you for submitting your character.<br>";
}