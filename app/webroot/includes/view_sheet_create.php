<?
$page_title = "Create Character";

$show_form = true; 

if(isset($_POST['character_name']))
{
	if($userdata['session_logged_in'])
	{
		// check if they are being referred to from itself
		/*if($_SERVER['HTTP_REFERER'] != "http://www.wantonwicked.net/view_sheet.php?action=create")
		{
			die();
		}*/
		
		// attempt to process character
		$str_to_find = array('"', ",");
		$str_to_replace = array("", "");
		$character_name = addslashes(htmlspecialchars(str_replace($str_to_find, $str_to_replace, stripslashes($_POST['character_name']))));
		
		// verify that character name isn't in use already
		$name_check_query = "select character_id from characters where character_name='$character_name';";
		$name_check_result = mysql_query($name_check_query) || die(mysql_error());
		if(mysql_num_rows($name_check_result))
		{
			// warn that there is already a character with that name
			$show_form = false;
			$page_content = <<<EOQ
There is already a character with that name, please go back and give the character a different name.
EOQ;
	
		}
		else
		{
			// can insert the character
			$show_form = false;
			
			$now = date('Y-m-d h:i:s');
			$show_sheet = $_POST['show_sheet'];
			$view_password = $_POST['view_password'];
			$character_type = $_POST['character_type'];
			$city = $_POST['location'];
			$age = $_POST['age'] +0;
			$sex = $_POST['sex'];
			$apparent_age = $_POST['apparent_age'] +0;
			$concept = htmlspecialchars($_POST['concept']);
			$description = htmlspecialchars($_POST['description']);
			if($description == "")
			{
				$description = "I need a description";
			}
			
			$url = htmlspecialchars($_POST['url']);
			$safe_place = htmlspecialchars($_POST['safe_place']);
			$friends = isset($_POST['friends']) ? htmlspecialchars($_POST['friends']) : "";
			$helper = isset($_POST['helper']) ? htmlspecialchars($_POST['helper']) : "";
			$exit_line = htmlspecialchars($_POST['exit_line']);
			if($exit_line == "")
			{
				$exit_line = "I need an exit line";
			}
			
			$icon = $_POST['icon'] +0;
			$is_npc = isset($_POST['is_npc']) ? "Y" : "N";
			$virtue = $_POST['virtue'];
			$vice = $_POST['vice'];
			$splat1 = $_POST['splat1'];
			$splat2 = $_POST['splat2'];
			$subsplat = htmlspecialchars($_POST['subsplat']);
			
			$attribute_list = array("intelligence", "wits", "resolve", "strength", "dexterity", "stamina", "presence", "manipulation", "composure" );
			
			while(list($key, $attribute) = each($attribute_list))
			{
				$$attribute = $_POST[$attribute] +0;
			}

			reset($attribute_list);
	
	
			$skill_list = array("academics", "computer", "crafts", "investigation", "medicine", "occult", "politics", "science", "athletics", "brawl", "drive", "firearms", "larceny", "stealth", "survival", "weaponry", "animal_ken", "empathy", "expression", "intimidation", "persuasion", "socialize", "streetwise", "subterfuge");
			
			while(list($key, $skill) = each($skill_list))
			{
				$$skill = $_POST[$skill]+0;
				$skill_spec = $skill . "_spec";
				$$skill_spec = htmlspecialchars($_POST[$skill_spec]);
				
			}

			reset($skill_list);
			
			$size = $_POST['size'] +0;
			$speed = $_POST['speed'] +0;
			$initiative_mod = $_POST['initiative_mod'] +0;
			$defense = $_POST['defense'] +0;
			$armor = $_POST['armor'];
			$health = $_POST['health'] +0;
			$wounds_agg = $_POST['wounds_agg'] +0;
			$wounds_lethal = $_POST['wounds_lethal'] +0;
			$wounds_bashing = $_POST['wounds_bashing'] +0;
			$willpower_perm = $_POST['willpower_perm'] +0;
			$willpower_temp = $_POST['willpower_temp'] +0;
			$power_stat = isset($_POST['power_trait']) ? $_POST['power_trait'] +0 : 0;
			$power_points = isset($_POST['power_points']) ? $_POST['power_points'] +0: 0;
			$morality = $_POST['morality'] +0;
			$merits = htmlspecialchars($_POST['merits']);
			$flaws = htmlspecialchars($_POST['flaws']);
			$powers = htmlspecialchars($_POST['powers']);
			$equipment_public = htmlspecialchars($_POST['equipment_public']);
			$equipment_hidden = htmlspecialchars($_POST['equipment_hidden']);
			$public_effects = htmlspecialchars($_POST['public_effects']);
			$history = htmlspecialchars($_POST['history']);
			$character_notes = htmlspecialchars($_POST['notes']);
			$goals = htmlspecialchars($_POST['goals']);
			$head_sanctioned = '';
			$is_sanctioned = '';
			$asst_sanctioned = '';
			$is_deleted = 'N';
			$current_experience = 0;
			$total_experience = 0;
			$xp_per_day = .5;
			$gm_notes = '';
			$sheet_update = '';
			$login_note = '';
			$hide_icon = $_POST['hide_icon'];
			$status = $_POST['status'];

			$trans_query = "begin;";
			$trans_result = mysql_query($trans_query) || die(mysql_error());
			
			$lock_query = "lock tables login_character_index write, characters write;";
			$lock_result = mysql_query($lock_query) || die(mysql_error());
			
			// get next character id
			$character_id = getNextID($connection, "characters", "character_id");
			
			$insert_query = <<<EOQ
insert into
    characters
    (
        user_id,
        character_name,
        show_sheet,
        view_password,
        character_type,
        city,
        age,
        sex,
        apparent_age,
        concept,
        description,
        url,
        safe_place,
        friends,
        exit_line
    )
values
(
$userdata[user_id],
'$character_name', 
'$show_sheet',
'$view_password', 
'$character_type', 
'$city', 
$age,
'$sex', 
$apparent_age,
'$concept', 
'$description', 
'$url',
'$safe_place',
'$friends', 
'$exit_line', 
$icon, 
'$is_npc', 
'$virtue',
'$vice', 
'$splat1', 
'$splat2',
'$subsplat',
$size,
$speed, 
$initiative_mod, 
$defense, 
'$armor',
$health,
$wounds_agg,
$wounds_lethal,
$wounds_bashing,
$willpower_perm,
$willpower_temp,
$power_stat,
$power_points, 
$morality,
'$merits', 
'$flaws',
'$powers',
'$equipment_public',
'$equipment_hidden',
'$public_effects',
'$history',
'$character_notes',
'$goals',
'$head_sanctioned',
'$is_sanctioned', 
'$asst_sanctioned', 
'$is_deleted',
$current_experience, 
$total_experience,
$xp_per_day,
'$gm_notes',
'$sheet_update',
'$login_note',
'$hide_icon',
'$helper',
'$status');
EOQ;
			//echo "$insert_query<br>";
			$insert_result = mysql_query($insert_query) || die(mysql_error());
			
			$login_query = "insert into login_character_index values (null, $userdata[user_id], $character_id);";
			$login_query = mysql_query($login_query) || die(mysql_error());
			
			$lock_query = "unlock tables;";
			$lock_result = mysql_query($lock_query) || die(mysql_error());
			
			$trans_query = "commit;";
			$trans_result = mysql_query($trans_query) || die(mysql_error());
			
			// create post for ST forum
			$character_query = "select Character_Name, Character_Type, City from characters where primary_login_id=$userdata[user_id] and is_sanctioned='Y' and is_npc='N' and is_deleted = 'N' order by Character_Name;";
			$character_result = mysql_query($character_query) || die(mysql_error());
			
			$character_list = "";
			while($character_detail = mysql_fetch_array($character_result, MYSQL_ASSOC))
			{
				$character_list .= "$character_detail[Character_Name] ($character_detail[Character_Type] in $character_detail[City]), ";
			}
			
			if($character_list == "")
			{
				// put in the statement "No characters"
				$character_list = "No Characters";
			}
			else
			{
				// trim off the ending comma
				$character_list = substr($character_list, 0, strlen($character_list) -2);
			}

			$login_query = "select * from login where ID=$userdata[user_id];";
			$login_result = mysql_query($login_query) || die(mysql_error());
			$login_detail = mysql_fetch_array($login_result, MYSQL_ASSOC);
			
			$message = <<<EOQ
$userdata[user_name] has submitted a new character named $character_name.
$userdata[user_name]'s email address is $login_detail[Email].
Link to the sheet for $character_name: http://www.wantonwicked.net/view_sheet.php?action=st_view&view_character_id=$character_id

Other Characters they play: $character_list.

The following are some detail about the new character:
Concept: $concept

History: $history
Goals: $goals
EOQ;

			
			$message = addslashes($message);
			include($phpbb_root_path . 'includes/bbcode.'.$phpEx);
			include($phpbb_root_path . 'includes/functions_post.'.$phpEx);

			$mode = "newtopic";
			$username = "JeffV";
			$subject = "New Character: $city - $character_name";
			$poll_title = "";
			$poll_options = "";
			$poll_length = "";
			$bbcode_uid = '';
			$bbcode_on = 1;
			$smilies_on = 1;
			$html_on = true;
			$post_data = array();
    	$post_data['first_post'] = true;
    	$post_data['last_post'] = false;
    	$post_data['has_poll'] = false;
    	$post_data['edit_poll'] = false;
			
			$error_msg = "";
			$return_message = "";
			$return_meta = "";
			$forum_id = 15;
			$topic_id = 0;
			$post_id = 0;
			$topic_type = POST_NORMAL;
			$attach_sig = 0;
			$temp_id = $userdata['user_id'];
			$temp_name = $userdata['username'];
			
			$userdata['user_id'] = 8;
			$userdata['username'] = "JeffV";

			prepare_post($mode, $post_data, $bbcode_on, $html_on, $smilies_on, $error_msg, $username, $bbcode_uid, $subject, $message, $poll_title, $poll_options, $poll_length);
			
			if ( $error_msg == '' )
			{
				submit_post($mode, $post_data, $return_message, $return_meta, $forum_id, $topic_id, $post_id, $poll_id, $topic_type, $bbcode_on, $html_on, $smilies_on, $attach_sig, $bbcode_uid, str_replace("\'", "''", $username), str_replace("\'", "''", $subject), str_replace("\'", "''", $message), str_replace("\'", "''", $poll_title), $poll_options, $poll_length);
				
				update_post_stats($mode, $post_data, $forum_id, $topic_id, $post_id, $userdata['user_id']);
			}
			else
			{
			  echo "PHPBB: $error_msg<br>";
			}
			
			// restore them back to original values
			$userdata['user_id'] = $temp_id;
			$userdata['username'] = $temp_name;
			
			$page_content = <<<EOQ
If you see any errors above please manually send an email to jeffv@wantonwicked.net notifying me of your character's name and the exact message of the error text.<br>
<br>
$character_name has been attached to your profile $userdata[user_name].<br>
EOQ;

			$java_script = <<<EOQ
<script language="JavaScript">
  window.opener.location.reload(true);
  window.opener.focus();
  //window.close();
</script>
EOQ;
		}
	}
	else
	{
		$page_content = <<<EOQ
You are not logged into the site, please log in again, and resubmit the character.
EOQ;
		$show_form= false;
	}
}


if($show_form)
{
	$character_id = 0;
	$stats = '';
	$character_type = 'Mortal';
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
	$edit_is_npc = true;
	$edit_attributes = true;
	$edit_skills = true;
	$edit_perm_traits = true;
	$edit_temp_traits = true;
	$edit_powers = true;
	$edit_history = true;
	$edit_goals = true;
	$edit_login_note = false;
	$edit_experience = true;
	$show_st_notes = false;
	$view_is_asst = false;
	$view_is_st = false;
	$view_is_head = false;	
	$view_is_admin = false;
	$may_edit = true;
	$edit_cell = true;
	$calculate_derived = true;
	
	$character_sheet .= buildWoDSheet($stats, $character_type, $edit_show_sheet, $edit_name, $edit_vitals, $edit_is_npc, $edit_is_dead, $edit_location, $edit_concept, $edit_description, $edit_url, $edit_equipment, $edit_public_effects, $edit_group, $edit_exit_line, $edit_is_npc, $edit_attributes, $edit_skills, $edit_perm_traits, $edit_temp_traits, $edit_powers, $edit_history, $edit_goals, $edit_login_note, $edit_experience, $show_st_notes, $view_is_asst, $view_is_st, $view_is_head, $view_is_admin, $may_edit, $edit_cell, $calculate_derived);
	
	$page_content .= <<<EOQ
When creating a character, please make sure you have reviewed <a href="wiki/?CharacterCreation">Character Creation Guidelines</a>, the <a href="wiki/?ORBList">O/R/B List</a> and the page on <a href="wiki/?GoalsAndBeliefs">Goals &amp; Beliefs</a>.<br>
<iframe src="blank.html" name="char_info" id="char_info" width="1" height="1" border="0" frameborder="0" scrolling="no">
</iframe>
<br>
<form name="character_sheet" id="character_sheet" method="post" action="$_SERVER[PHP_SELF]?action=create">
<div align="center" name="char_sheet" id="char_sheet">
$character_sheet
</div>
</form>
EOQ;
			
	$java_script = <<<EOQ
<script language="javascript">
function changeDots (tag_name, value, number_of_dots, remove)
{
	// if is the same value then set to 0
	if((value == document.getElementById(tag_name).value) && remove)
	{
		value = 0;
	}
	
	// determine character type
	var character_type = document.getElementById("character_type").value
	character_type = character_type.toLowerCase();
	
	// cycle through the dots to fill up the values up to the selected value
	for(i = 1; i <= Number(number_of_dots); i++)
	{
		if(i <= value)
		{
			document.getElementById(tag_name+i).src="img/" + character_type + "_filled.gif";
		}
		else
		{
			document.getElementById(tag_name+i).src="img/empty.gif";
		}
	}
	
	document.getElementById(tag_name).value = value;
}

function updateTraits()
{
	// willpower
	var resolve = document.getElementById("resolve").value;
	var composure = document.getElementById("composure").value;
	changeDots("willpower_perm", Number(resolve)+Number(composure), 10, false);
	changeDots("willpower_temp", Number(resolve)+Number(composure), 10, false);
	
	// health
	var stamina = document.getElementById("stamina").value;
	var size = document.getElementById("size").value;
	changeDots("health", Number(stamina) + Number(size), 15, false);
	
	// defense
	var wits = document.getElementById("wits").value;
	var dexterity = document.getElementById("dexterity").value;
	var defense = wits; 
	
	if (dexterity < wits)
	{
		defense = dexterity;
	}
	document.getElementById("defense").value = defense;
	
	// initiative
	var initiative = Number(dexterity) + Number(composure); 
	document.getElementById("initiative_mod").value = initiative;
	
	// speed
	var strength = document.getElementById("strength").value;
	var speed = Number(size) + Number(strength) + Number(dexterity);
	
	document.getElementById("speed").value = speed;
}

function changeSheet(character_type)
{
  var sURL = "get_sheet.php?action=create&character_id=$character_id&view_own=y&character_type="+character_type;
  window.char_info.location.href = sURL;
}

function SubmitCharacter()
{
	if(document.character_sheet.character_name.value.match(/\w/g))
	{
		window.document.character_sheet.submit();
	}
	else
	{
		alert('Please Enter a Character Name');
	}
}
</script>
EOQ;
}
?>