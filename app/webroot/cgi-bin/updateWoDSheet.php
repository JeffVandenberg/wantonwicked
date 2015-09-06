<?
function updateWoDSheet($stats, $edit_show_sheet = false, $edit_name = false, $edit_vitals = false, $edit_is_npc = false, $edit_is_dead = false, $edit_location = false, $edit_concept = false, $edit_description = false, $edit_url = false, $edit_equipment = false, $edit_public_effects = false, $edit_group = false, $edit_exit_line = false, $edit_attributes = false, $edit_skills = false, $edit_perm_traits = false, $edit_temp_traits = false, $edit_powers = false, $edit_history = false, $edit_goals = false, $edit_login_note = false, $edit_experience = false, $show_st_notes_table = false, $view_is_asst = false, $view_is_st = false, $view_is_head = false, $view_is_admin = false, $may_edit = false, $edit_cell = false)
{
	// variables necessary for update
	$attribute_list = array("intelligence", "wits", "resolve", "strength", "dexterity", "stamina", "presence", "manipulation", "composure" );
	$skill_list = array("academics", "computer", "crafts", "investigation", "medicine", "occult", "politics", "science", "athletics", "brawl", "drive", "firearms", "larceny", "stealth", "survival", "weaponry", "animal_ken", "empathy", "expression", "intimidation", "persuasion", "socialize", "streetwise", "subterfuge");
	
	$now = date('Y-m-d h:i:s');
	$update_query = "update characters set ";
	global $userdata;
	
	if($edit_show_sheet)
	{
		$show_sheet = $stats['show_sheet'];
		$view_password = htmlspecialchars($stats['view_password']);
		$hide_icon = $stats['hide_icon'];
		
		$update_query .= "show_sheet = '$show_sheet', view_password='$view_password', hide_icon = '$hide_icon', ";
	}
	
	if($edit_name)
	{
  	//echo "Editting Name<br>";
  	
		$str_to_find = array('"', ",");
		$str_to_replace = array("", "");
		$character_name = addslashes(htmlspecialchars(str_replace($str_to_find, $str_to_replace, stripslashes($stats['character_name']))));
		if(trim($character_name) == "")
		{
  		$character_name = "Character " . $stats['character_id'];
		}
		//echo "PARSED CHARACTER NAME: $character_name<br>";
		$update_query .= "character_name = '$character_name', ";
    //echo "$update_query<br>";
	}
	
	if($edit_vitals)
	{
		$character_type = $stats['character_type'];
		$location = $stats['location'];
		$sex = $stats['sex'];
		$virtue = $stats['virtue'];
		$vice = $stats['vice'];
		$icon = $stats['icon'] +0;
		$splat1 = (isset($stats['splat1'])) ? $stats['splat1'] : "";
		$splat2 = (isset($stats['splat2'])) ? $stats['splat2'] : "";
		$subsplat = (isset($stats['subsplat'])) ? htmlspecialchars($stats['subsplat']) : "";
		$age = $stats['age'] +0;
		$apparent_age = (isset($stats['apparent_age'])) ? $stats['apparent_age'] +0 : 0;
		
		$update_query .= "character_type = '$character_type', city = '$location', sex = '$sex', virtue = '$virtue', vice = '$vice', icon = $icon, splat1 = '$splat1', splat2 = '$splat2', subsplat = '$subsplat', age = $age, apparent_age = $apparent_age, ";
	}
	
	if($edit_is_npc)
	{
		$is_npc = (isset($stats['is_npc'])) ? "Y" : "N";
		$update_query .= "is_npc = '$is_npc', ";
	}
	
	if($edit_is_dead)
	{
		$status = $stats['status'];
		$update_query .= "status = '$status', ";
	}
	
	if($edit_concept)
	{
		$concept = htmlspecialchars($stats['concept']);
		$update_query .= "concept = '$concept', ";
	}
	
	if($edit_description)
	{
		$description = htmlspecialchars($stats['description']);
		$update_query .= "description = '$description', ";
	}
	
	if($edit_url)
	{
		$url = htmlspecialchars($stats['url']);
		$update_query .= "url = '$url', ";
	}
	
	if($edit_equipment)
	{
		$equipment_public = htmlspecialchars($stats['equipment_public']);
		$equipment_hidden = htmlspecialchars($stats['equipment_hidden']);
		$update_query .= "equipment_public = '$equipment_public', equipment_hidden = '$equipment_hidden', ";
	}
	
	if($edit_public_effects)
	{
		$public_effects = htmlspecialchars($stats['public_effects']);
		$update_query .= "public_effects = '$public_effects', ";
	}
	
	if($edit_group)
	{
		$friends = (isset($stats['friends'])) ? htmlspecialchars($stats['friends']) : "";
		$helper = (isset($stats['helper'])) ? htmlspecialchars($stats['helper']) : "";
		$safe_place = htmlspecialchars($stats['safe_place']);
		$update_query .= "friends = '$friends', helper = '$helper', safe_place = '$safe_place', ";
	}
	
	if($edit_exit_line)
	{
		$exit_line = htmlspecialchars($stats['exit_line']);
		$update_query .= "exit_line = '$exit_line', ";
	}
	
	if($edit_attributes)
	{
		while(list($key, $attribute) = each($attribute_list))
		{
			$update_query .= "$attribute = " . $stats["$attribute"] .", ";
		}
		reset($attribute_list);
	}
	
	if($edit_skills)
	{
		while(list($key, $skill) = each($skill_list))
		{
			$$skill = $stats[$skill]+0;
			$skill_spec = $skill . "_spec";
			$$skill_spec = htmlspecialchars($stats[$skill_spec]);
			$update_query .= "$skill = " . $$skill . ", $skill_spec = '" . $$skill_spec . "', ";
		}
		reset($skill_list);
	}
	
	if($edit_perm_traits)
	{
		$power_trait = (isset($stats['power_trait'])) ? $stats['power_trait'] +0 : 0;
		$willpower_perm = $stats['willpower_perm'] + 0;
		$morality = $stats['morality'] + 0;
		$health = $stats['health'] + 0;
		$size = $stats['size'] + 0;
		$defense = $stats['defense'] + 0;
		$initiative_mod = $stats['initiative_mod'] + 0;
		$speed = $stats['speed'] + 0;
		$armor = $stats['armor'];
		$update_query .= "power_stat = $power_trait, willpower_perm = $willpower_perm, morality = $morality, health = $health, size = $size, defense = $defense, initiative_mod = $initiative_mod, speed = $speed, armor = '$armor', ";
	}
	
	if($edit_temp_traits)
	{
		$power_points = (isset($stats['power_points'])) ? $stats['power_points'] + 0 : 0;
		$willpower_temp = $stats['willpower_temp'] + 0;
		$wounds_agg = $stats['wounds_aggravated'] + 0;
		$wounds_lethal = $stats['wounds_lethal'] + 0;
		$wounds_bashing = $stats['wounds_bashing'] + 0;
		$update_query .= "power_points = $power_points, willpower_temp = $willpower_temp, wounds_agg = $wounds_agg, wounds_lethal = $wounds_lethal, wounds_bashing = $wounds_bashing, ";
	}
	
	if($edit_powers)
	{
		$merits = htmlspecialchars($stats['merits']);
		$flaws = htmlspecialchars($stats['flaws']);
		$powers = htmlspecialchars($stats['powers']);
		$update_query .= "merits = '$merits', flaws = '$flaws', powers = '$powers', ";
	}
	
	if($edit_history)
	{
		$history = htmlspecialchars($stats['history']);
		$update_query .= "history = '$history', ";
	}
	
	if($edit_goals)
	{
		$notes = htmlspecialchars($stats['notes']);
		$goals = htmlspecialchars($stats['goals']);
		$update_query .= "goals = '$goals', character_notes = '$notes', ";
	}
	
	if($edit_login_note)
	{
		$login_note = htmlspecialchars($stats['login_note']);
		$update_query .= "login_note = '$login_note', ";
	}
	
	if($show_st_notes_table)
	{
		// check for sanctioned info
  	if($view_is_head)
  	{
    	$update_query .= "head_sanctioned = '$stats[head_sanctioned]', ";
  	}
  	
  	if($view_is_st)
  	{
    	$update_query .= "is_sanctioned = '$stats[is_sanctioned]', last_st_updated = $userdata[user_id], when_last_st_updated = '$now', ";
  	}
  	
  	if($view_is_asst)
  	{
    	$update_query .= "asst_sanctioned = '$stats[asst_sanctioned]', last_asst_st_updated = $userdata[user_id], when_last_asst_st_updated = '$now', ";
  	}
  	
    if($edit_experience)
    {
    	$current_experience = $stats['current_experience'] + 0;
    	$total_experience = $stats['total_experience'] +0;
    	$update_query .= "current_experience = $current_experience, total_experience = $total_experience, ";
    }
    
    // add ST Updates field
    $short_now = date('Y-m-d');
    $sheet_updates = <<<EOQ
$stats[sheet_updates]
$stats[new_sheet_updates]
$userdata[username] on $short_now
EOQ;
    $sheet_updates = htmlspecialchars($sheet_updates);
    $update_query .= "sheet_update = '$sheet_updates', ";
    
    // test if new st notes
    if(!empty($stats['new_gm_notes']))
    {
      $gm_notes = <<<EOQ
$stats[gm_notes]
$stats[new_gm_notes]
$userdata[username] on $short_now
EOQ;
      $gm_notes = htmlspecialchars($gm_notes);
      $update_query .= "gm_notes = '$gm_notes', ";
    }
	}
	
	if($edit_cell)
	{
		$cell_id = $stats['cell_id'];
		$update_query .= "cell_id = '$cell_id', ";
	}
	
	if($may_edit)
	{
		if($update_query != "update characters set ")
		{
			$update_query = substr($update_query, 0, strlen($update_query) -2);
			$update_query .= " where character_id = $stats[character_id];";
			//echo $update_query."<br>";
			$update_result = mysql_query($update_query) || die(mysql_error());
		}
	}
}
?>