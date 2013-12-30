<?php
			$vitals_table = <<<EOQ
<table class="character-sheet $table_class" width="100%">
    <tr>
        <th colspan="4" align="center">
            Vitals
        </th>
    </tr>
    <tr>
        <td width="15%">
            <b>Name</b>
        </td>
        <td width="35%">
            $character_name
        </td>
        <td width="15%">
            <b>Character Type</b>
        </td>
        <td width="35%">
            $character_type_select
        </td>
    </tr>
    <tr>
        <td>
            <b>Location</b>
        </td>
        <td>
            $location
        </td>
        <td>
            <b>Sex</b>
        </td>
        <td>
            $sex
        </td>
    </tr>
    <tr>
        <td>
            <b>Virtue</b>
        </td>
        <td>
            $virtue
        </td>
        <td>
            <b>Vice</b>
        </td>
        <td>
            $vice
        </td>
    </tr>
    <tr>
        <td>
            <b>Seeming</b>
        </td>
        <td>
            $splat1
        </td>
        <td>
            <b>Kith</b>
        </td>
        <td>
            $subsplat
        </td>
    </tr>
    <tr>
        <td>
            <b>Court</b>
        </td>
        <td>
            $splat2
        </td>
        <td>
            <b>Icon</b>
        </td>
        <td>
            $icon
        </td>
    </tr>
    <tr>
        <td>
            <b>Age</b>
        </td>
        <td>
            $age
        </td>
        <td>
            <b>Years Missing (real)</b>
        </td>
        <td>
            $apparent_age
        </td>
    </tr>
    <tr>
        <td>
            <b>Is NPC</b>
        </td>
        <td>
            $is_npc
        </td>
        <td>
            <b>Status</b>
        </td>
        <td>
            $status
        </td>
    </tr>
</table>
EOQ;
		
			$information_table = <<<EOQ
<table class="character-sheet $table_class" width="100%">
    <tr>
        <th colspan="2" align="center">
            Information
        </th>
    </tr>
    <tr>
        <td width="25%">
            <b>Concept</b>
        </td>
        <td width="75%">
            $concept
        </td>
    </tr>
    <tr>
        <td>
            <b>Description</b>
        </td>
        <td>
            $description
        </td>
    </tr>
    <tr>
        <td>
            <b>URL</b>
        </td>
        <td>
            $url
        </td>
    </tr>
    <tr>
        <td>
            <b>Daily Equipment</b>
        </td>
        <td>
            $equipment_public
        </td>
    </tr>
    <tr>
        <td>
            <b>Other Equipment</b>
        </td>
        <td>
            $equipment_hidden
        </td>
    </tr>
    <tr>
        <td>
            <b>Public Effects</b>
        </td>
        <td>
            $public_effects
        </td>
    </tr>
    <tr>
        <td>
            <b>Motley</b>
        </td>
        <td>
            $friends
        </td>
    </tr>
    <tr>
        <td>
            <b>Hollow</b>
        </td>
        <td>
            $safe_place
        </td>
    </tr>
    <tr>
        <td>
            <b>Exit Line</b>
        </td>
        <td>
            $exit_line
        </td>
    </tr>
</table>
EOQ;

      // affinity contracts
      $power_list = "";
      if($edit_powers)
      {
        $power_list .= <<<EOQ
<a href="#" onClick="addContract('affcont');return false;">Add Affinity Contract</a><br>
EOQ;
      }
      else
      {
        $power_list .= "Affinity Contracts<br>";
      }
      
      $power_list .= <<<EOQ
<table border="0" cellspacing="1" cellpadding="1" class="normal_text" name="affcont_list" id="affcont_list" width="100%">
  <tr>
    <th width="35%">
      Name
    </th>
    <th width="35%">
      Note
    </th>
    <th width="30%">
      Level
    </th>
  </tr>
EOQ;

      $icdiscs = getPowers($characterId, 'AffContract', NAMENOTE, 3);
      
      for($i = 0; $i < sizeof($icdiscs); $i++)
      {
      	$power_dots = makeDotsXP("affcont${i}", $element_type['supernatural'], $character_type, $max_dots, $icdiscs[$i]->getPowerLevel(), $edit_powers, false, $edit_xp);
      	
      	$power_name = $icdiscs[$i]->getPowerName();
      	$power_note = $icdiscs[$i]->getPowerNote();
      	$power_id = $icdiscs[$i]->getPowerID();
      	
      	if($edit_powers)
      	{
        	$power_name = <<<EOQ
<input type="text" name="affcont${i}_name" id="affcont${i}_name" size="15" class="$input_class" value="$power_name">
EOQ;

        	$power_note = <<<EOQ
<input type="text" name="affcont${i}_note" id="affcont${i}_note" size="15" class="$input_class" value="$power_note">
EOQ;
      	}
      	
      	$power_list .= <<<EOQ
  <tr>
    <td>
      $power_name
    </td>
    <td>
      $power_note
    </td>
    <td align="center">
      $power_dots
      <input type="hidden" name="affcont${i}_id" id="affcont${i}_id" value="$power_id">
    </td>
  </tr>
EOQ;
      }
      $power_list .= "</table>";
      
      // non affinity contracts
      if($edit_powers)
      {
        $power_list .= <<<EOQ
<br>
<a href="#" onClick="addContract('nonaffcont');return false;">Add Non-Affinity Contract</a><br>
EOQ;
      }
      else
      {
        $power_list .= "Nonaffinity Contracts<br>";
      }
      
      $power_list .= <<<EOQ
<table border="0" cellspacing="1" cellpadding="1" class="normal_text" name="nonaffcont_list" id="nonaffcont_list" width="100%">
  <tr>
    <th width="35%">
      Name
    </th>
    <th width="35%">
      Note
    </th>
    <th width="30%">
      Level
    </th>
  </tr>
EOQ;
      
      $oocdiscs = getPowers($characterId, 'NonAffContract', NAMENOTE, 3);
      
      for($i = 0; $i < sizeof($oocdiscs); $i++)
      {
      	$power_dots = makeDotsXP("nonaffcont${i}", $element_type['supernatural'], $character_type, $max_dots, $oocdiscs[$i]->getPowerLevel(), $edit_powers, false, $edit_xp);
      	
      	$power_name = $oocdiscs[$i]->getPowerName();
      	$power_note = $oocdiscs[$i]->getPowerNote();
      	$power_id = $oocdiscs[$i]->getPowerID();
      	
      	if($edit_powers)
      	{
        	$power_name = <<<EOQ
<input type="text" name="nonaffcont${i}_name" id="nonaffcont${i}_name" size="15" class="$input_class" value="$power_name">
EOQ;

        	$power_note = <<<EOQ
<input type="text" name="nonaffcont${i}_note" id="nonaffcont${i}_note" size="15" class="$input_class" value="$power_note">
EOQ;
      	}
      	
      	$power_list .= <<<EOQ
<tr>
    <td>
        $power_name
    </td>
    <td>
        $power_note
    </td>
    <td align="center">
        $power_dots
        <input type="hidden" name="nonaffcont${i}_id" id="nonaffcont${i}_id" value="$power_id">
    </td>
</tr>
EOQ;
      }
      $power_list .= "</table>";
      
      // Goblin Contracts
      $supernatural_xp_js = "";
      if($edit_xp)
      {
        $supernatural_xp_js = " onChange=\"updateXP($element_type[supernatural])\" ";
      }
      
      if($edit_powers)
      {
        $power_list .= <<<EOQ
<br>
<a href="#" onClick="addContract('gobcont');return false;">Add Goblin Contract</a><br>
EOQ;
      }
      else
      {
        $power_list .= "Goblin Contracts<br>";
      }
      
      $power_list .= <<<EOQ
<table border="0" cellspacing="1" cellpadding="1" class="normal_text" name="gobcont_list" id="gobcont_list" width="100%">
  <tr>
    <th width="70%">
      Name
    </th>
    <th width="30%">
      Level
    </th>
  </tr>
EOQ;

      $powers = getPowers($characterId, 'GoblinContract', NAMENOTE, 2);
      
      for($i = 0; $i < sizeof($powers); $i++)
      {
        $power_dots = makeDotsXP("gobcont${i}", $element_type['supernatural'], $character_type, $max_dots, $powers[$i]->getPowerLevel(), $edit_powers, false, $edit_xp);
        $power_name = $powers[$i]->getPowerName();
        $power_id = $powers[$i]->getPowerID();
        
      	if($edit_powers)
      	{
        	$power_name = <<<EOQ
<input type="text" name="gobcont${i}_name" id="gobcont${i}_name" size="15" class="$input_class" value="$power_name">
EOQ;
      	}
      	
      	$power_list .= <<<EOQ
  <tr>
    <td>
      $power_name
    </td>
    <td align="center">
      $power_dots
      <input type="hidden" name="gobcont${i}_id" id="gobcont${i}_id" value="$power_id" $supernatural_xp_js>
    </td>
  </tr>
EOQ;
      }
      $power_list .= "</table>";
      
			$traits_table = <<<EOQ
<table class="character-sheet $table_class"  width="100%">
    <tr>
        <th colspan="3" align="center" width="50%">
            Merits
			$abilities_help
        </th>
        <th colspan="3" align="center" width="50%">
            Contracts
			$abilities_help
        </th>
    </tr>
    <tr valign="top">
        <td colspan="3">
            $character_merit_list
        </td>
        <td colspan="3" rowspan="3">
            $power_list
        </td>
    </tr>
    <tr>
        <th colspan="3" align="center">
            Flaws/Derangements
			$abilities_help
        </th>
    </tr>
    <tr>
        <td colspan="3" valign="top">
            $character_flaw_list
        </td>
    </tr>
</table>
<table class="character-sheet $table_class"  width="100%">
    <tr>
        <th colspan="6">
            Traits
        </th>
    </tr>
    <tr>
        <td width="15%">
            Health
        </td>
        <td colspan="2" width="30%">
            $health_dots
        </td>
        <td colspan="1" width="15%">
            Wounds
        </td>
        <td colspan="2" width="40%">
            Bashing: $wounds_bashing Lethal: $wounds_lethal Agg: $wounds_aggravated
        </td>
    </tr>
    <tr>
        <td colspan="1">
            Wyrd
        </td>
        <td colspan="2">
            $power_trait_dots
        </td>
        <td colspan="1">
            Size
        </td>
        <td colspan="2">
            $size
        </td>
    </tr>
    <tr>
        <td colspan="1">
            Clarity
        </td>
        <td colspan="2">
            $morality_dots
        </td>
        <td colspan="1">
            Defense
        </td>
        <td colspan="2">
            $defense
        </td>
    </tr>
    <tr>
        <td>
            Willpower Perm
        </td>
        <td colspan="2">
            $willpower_perm_dots
        </td>
        <td colspan="1">
            Initiative Mod
        </td>
        <td colspan="2">
            $initiative_mod
        </td>
    </tr>
    <tr>
        <td>
            Willpower Temp
        </td>
        <td colspan="2">
            $willpower_temp_dots
        </td>
        <td colspan="1">
            Speed
        </td>
        <td colspan="2">
            $speed
        </td>
    </tr>
    <tr>
        <td>
            Glamour
        </td>
        <td colspan="2">
            $power_points_dots
        </td>
        <td colspan="1">
            Armor
        </td>
        <td colspan="2">
            $armor
        </td>
    </tr>
</table>
EOQ;
