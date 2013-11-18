<?php
			$vitals_table = <<<EOQ
<table bgcolor="$table_bg_color" border="0" cellpadding="1" cellspacing="0" width="100%">
	<tr>
	  <td>
			<table border="0" cellpadding="2" cellspacing="1" class="$table_class" width="100%">
				<tr>
					<th colspan="4" align="center">
						Vitals
					</th>
				</tr>
				<tr>
					<td bgcolor="$cell_bg_color" width="15%">
						<b>Name</b>
					</td>
					<td bgcolor="$cell_bg_color" width="35%">
						$character_name
					</td>
					<td bgcolor="$cell_bg_color" width="15%">
						<b>Character Type</b>
					</td>
					<td bgcolor="$cell_bg_color" width="35%">
						$character_type_select
					</td>
				</tr>
				<tr>
					<td bgcolor="$cell_bg_color">
						<b>Location</b>
					</td>
					<td bgcolor="$cell_bg_color">
						$location
					</td>
					<td bgcolor="$cell_bg_color">
						<b>Sex:</b>
					</td>
					<td bgcolor="$cell_bg_color">
						$sex
					</td>
				</tr>
				<tr>
					<td bgcolor="$cell_bg_color">
						<b>Virtue</b>
					</td>
					<td bgcolor="$cell_bg_color">
						$virtue
					</td>
					<td bgcolor="$cell_bg_color">
						<b>Vice</b>
					</td>
					<td bgcolor="$cell_bg_color">
						$vice
					</td>
				</tr>
				<tr>
					<td bgcolor="$cell_bg_color">
						<b>Age</b>
					</td>
					<td bgcolor="$cell_bg_color">
						$age
					</td>
					<td bgcolor="$cell_bg_color">
						<b>Apparent Age</b>
					</td>
					<td bgcolor="$cell_bg_color">
						$apparent_age
					</td>
				</tr>
				<tr>
					<td bgcolor="$cell_bg_color">
						<b>Is NPC</b>
					</td>
					<td bgcolor="$cell_bg_color">
						$is_npc
					</td>
					<td bgcolor="$cell_bg_color">
						<b>Status</b>
					</td>
					<td bgcolor="$cell_bg_color">
						$status
					</td>
				</tr>
				$admin_row
			</table>
		</td>
	</tr>
</table>
EOQ;
		
			$information_table = <<<EOQ
<table bgcolor="$table_bg_color" border="0" cellpadding="1" cellspacing="0" width="100%">
	<tr>
	  <td>
			<table border="0" cellpadding="2" cellspacing="1" class="$table_class" width="100%">
				<tr>
					<th colspan="2" align="center">
						Information
					</th>
				</tr>
				<tr>
					<td bgcolor="$cell_bg_color" width="25%">
						<b>Concept</b>
					</td>
					<td bgcolor="$cell_bg_color" width="75%">
						$concept
					</td>
				</tr>
				<tr>
					<td bgcolor="$cell_bg_color">
						<b>Description</b>
					</td>
					<td bgcolor="$cell_bg_color">
						$description
					</td>
				</tr>
				<tr>
					<td bgcolor="$cell_bg_color">
						<b>URL</b>
					</td>
					<td bgcolor="$cell_bg_color">
						$url
					</td>
				</tr>
				<tr>
					<td bgcolor="$cell_bg_color">
						<b>Daily Equipment</b>
					</td>
					<td bgcolor="$cell_bg_color">
						$equipment_public
					</td>
				</tr>
				<tr>
					<td bgcolor="$cell_bg_color">
						<b>Other Equipment</b>
					</td>
					<td bgcolor="$cell_bg_color">
						$equipment_hidden
					</td>
				</tr>
				<tr>
					<td bgcolor="$cell_bg_color">
						<b>Public Effects</b>
					</td>
					<td bgcolor="$cell_bg_color">
						$public_effects
					</td>
				</tr>
				<tr>
					<td bgcolor="$cell_bg_color">
						<b>Home</b>
					</td>
					<td bgcolor="$cell_bg_color">
						$safe_place
					</td>
				</tr>
			</table>
		</td>
	</tr>
</table>
EOQ;

      // Vices
      $disciplines_list = "";
      if($edit_powers)
      {
        $disciplines_list .= <<<EOQ
<a href="#" onClick="addDisc('icdisc');return false;">Add Vice</a><br>
EOQ;
      }
      else
      {
        $disciplines_list .= "Vices<br>";
      }
      
      $disciplines_list .= <<<EOQ
<table width="100%" border="0" cellspacing="1" cellpadding="1" class="normal_text" name="icdisc_list" id="icdisc_list">
  <tr>
    <th width="50%">
      Name
    </th>
    <th width="50%">
      Level
    </th>
  </tr>
EOQ;

      $icdiscs = getPowers($characterId, 'ICDisc', NAMENOTE, 3);
      
      for($i = 0; $i < sizeof($icdiscs); $i++)
      {
      	$discipline_dots = makeDotsXP("icdisc${i}", $element_type['supernatural'], $character_type, $max_dots, $icdiscs[$i]->getPowerLevel(), $edit_powers, false, $edit_xp);
      	
      	$discipline_name = $icdiscs[$i]->getPowerName();
      	$discipline_id = $icdiscs[$i]->getPowerID();
      	
      	if($edit_powers)
      	{
        	$discipline_name = <<<EOQ
<input type="text" name="icdisc${i}_name" id="icdisc${i}_name" size="15" class="$input_class" value="$discipline_name">
EOQ;
      	}
      	
     	  $disciplines_list .= <<<EOQ
  <tr>
    <td>
      $discipline_name
    </td>
    <td align="center">
      $discipline_dots
      <input type="hidden" name="icdisc${i}_id" id="icdisc${i}_id" value="$discipline_id">
    </td>
  </tr>
EOQ;
          
      }
      $disciplines_list .= "</table>";
      
      // Vestments
      $supernatural_xp_js = "";
      if($edit_xp)
      {
        $supernatural_xp_js = " onChange=\"updateXP($element_type[supernatural])\" ";
      }
      
      if($edit_powers)
      {
        $disciplines_list .= <<<EOQ
<br>
<a href="#" onClick="addDevotion();return false;">Add Vestment</a><br>
EOQ;
      }
      else
      {
        $disciplines_list .= "Vestments<br>";
      }
      
      $disciplines_list .= <<<EOQ
<table width="100%" border="0" cellspacing="1" cellpadding="1" class="normal_text" name="devotion_list" id="devotion_list">
  <tr>
    <th width="50%">
      Name
    </th>
    <th width="50%">
      Cost
    </th>
  </tr>
EOQ;

      $devotions = getPowers($characterId, 'Devotion', NAMENOTE, 2);
      
      for($i = 0; $i < sizeof($devotions); $i++)
      {
        $devotion_name = $devotions[$i]->getPowerName();
        $devotion_cost = $devotions[$i]->getPowerLevel();
        $devotion_id = $devotions[$i]->getPowerID();
        
        if($edit_powers)
        {
          $devotion_name = <<<EOQ
<input type="text" name="devotion${i}_name" id="devotion${i}_name" size="15" class="$input_class" value="$devotion_name">

EOQ;
          $devotion_cost = <<<EOQ
<input type="text" name="devotion{$i}_cost" id="devotion{$i}_cost" size="3" maxlength="2" class="$input_class" value="$devotion_cost" $supernatural_xp_js>
EOQ;
        }
        
      	$disciplines_list .= <<<EOQ
  <tr>
    <td>
      $devotion_name
    </td>
    <td align="center">
      $devotion_cost
      <input type="hidden" name="devotion${i}_id" id="devotion${i}_id" value="$devotion_id" $supernatural_xp_js>
    </td>
  </tr>
EOQ;
      }
      
      
      $disciplines_list .= "</table>";
      
			$traits_table = <<<EOQ
<table bgcolor="$table_bg_color" border="0" cellpadding="1" cellspacing="0" width="100%">
	<tr>
	  <td>
			<table border="0" cellpadding="2" cellspacing="1" class="$table_class" width="100%">
				<tr>
					<td colspan="6" align="center">
						<b>Abilities</b>
						$abilities_help
					</td>
				</tr>
				<tr>
				  <th bgcolor="$cell_bg_color" colspan="3" align="center" width="50%">
				    Merits
				  </th>
				  <th bgcolor="$cell_bg_color" colspan="3" align="center" width="50%">
				    Disciplines
				  </th>
				</tr>
				<tr valign="top">
				  <td bgcolor="$cell_bg_color" colspan="3">
  		      $character_merit_list
				  </td>
				  <td bgcolor="$cell_bg_color" colspan="3" rowspan="3">
				    $disciplines_list
				  </td>
				</tr>
				<tr>
				  <th bgcolor="$cell_bg_color" colspan="3" align="center">
				    Flaws/Derangements
				  </th>
				</tr>
				<tr>
				  <td bgcolor="$cell_bg_color" colspan="3" valign="top">
            $character_flaw_list
				  </td>
				</tr>
		  </table>
			<table border="0" cellpadding="2" cellspacing="1" class="$table_class" width="100%">
				<tr>
					<th colspan="6" align="center">
						Traits
					</th>
				</tr>
				<tr>
					<td bgcolor="$cell_bg_color" width="15%">
						Health
					</td>
					<td bgcolor="$cell_bg_color" colspan="2" width="30%">
						$health_dots
					</td>
					  <td colspan="1" bgcolor="$cell_bg_color" width="15%">
				    Wounds
				  </td>
				  <td colspan="2" bgcolor="$cell_bg_color" width="40%">
				    Bashing: $wounds_bashing Lethal: $wounds_lethal Agg: $wounds_aggravated
				  </td>
			</tr>
				<tr>
				  <td colspan="1" bgcolor="$cell_bg_color">
				  	Primary Vice
				  </td>
				  <td colspan="2" bgcolor="$cell_bg_color">
				  	$power_trait_dots
				  </td>
				  <td colspan="1" bgcolor="$cell_bg_color">
				  	Size
				  </td>
				  <td colspan="2" bgcolor="$cell_bg_color">
				  	$size
				  </td>
				</tr>
				<tr>
				  <td colspan="1" bgcolor="$cell_bg_color">
				  	Humanity
				  </td>
				  <td colspan="2" bgcolor="$cell_bg_color">
				  	$morality_dots
				  </td>
				  <td colspan="1" bgcolor="$cell_bg_color">
				  	Defense
				  </td>
				  <td colspan="2" bgcolor="$cell_bg_color">
				  	$defense
				  </td>
				</tr>
				<tr>
				  <td bgcolor="$cell_bg_color">
				  	Willpower Perm
				  </td>
				  <td colspan="2" bgcolor="$cell_bg_color">
				  	$willpower_perm_dots
				  </td>
				  <td colspan="1" bgcolor="$cell_bg_color">
				  	Initiative Mod
				  </td>
				  <td colspan="2" bgcolor="$cell_bg_color">
				  	$initiative_mod
				  </td>
				</tr>
				<tr>
				  <td bgcolor="$cell_bg_color">
				  	Willpower Temp
				  </td>
				  <td colspan="2" bgcolor="$cell_bg_color">
				  	$willpower_temp_dots
				  </td>
				  <td colspan="1" bgcolor="$cell_bg_color">
				  	Speed
				  </td>
				  <td colspan="2" bgcolor="$cell_bg_color">
				  	$speed
				  </td>
				</tr>
				<tr>
				  <td bgcolor="$cell_bg_color">
				  	Demonic Willpower
				  </td>
				  <td colspan="2" bgcolor="$cell_bg_color">
				  	$power_points_dots
				  </td>
				  <td colspan="1" bgcolor="$cell_bg_color">
				  	Armor
				  </td>
				  <td colspan="2" bgcolor="$cell_bg_color">
				  	$armor
				  </td>
				</tr>
			</table>
		</td>
	</tr>
</table>
EOQ;
?>