<?php
use classes\core\helpers\FormHelper;

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
						<b>Profession</b>
					</td>
					<td bgcolor="$cell_bg_color">
						$splat1
					</td>
					<td bgcolor="$cell_bg_color">
						<b>Compact</b>
					</td>
					<td bgcolor="$cell_bg_color">
						$subsplat
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
						<b>Icon</b>
					</td>
					<td bgcolor="$cell_bg_color">
						$icon
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
						<b>Cell</b>
					</td>
					<td bgcolor="$cell_bg_color">
						$friends
					</td>
				</tr>
				<tr>
					<td bgcolor="$cell_bg_color">
						<b>Safehouse</b>
					</td>
					<td bgcolor="$cell_bg_color">
						$safe_place
					</td>
				</tr>
				<tr>
					<td bgcolor="$cell_bg_color">
						<b>Exit Line</b>
					</td>
					<td bgcolor="$cell_bg_color">
						$exit_line
					</td>
				</tr>
			</table>
		</td>
	</tr>
</table>
EOQ;

      // endowments
      $endowments_list = "";
      if($edit_powers)
      {
        $endowments_list .= <<<EOQ
<a href="#" onClick="addEndowment();return false;">Add Endowment</a><br>
EOQ;
      }
      else
      {
        $endowments_list .= "Endowments<br>";
      }
      
      $endowments_list .= <<<EOQ
<table width="100%" border="0" cellspacing="1" cellpadding="1" class="normal_text" name="endowments_list" id="endowments_list">
  <tr>
    <th width="30%">
      Name
    </th>
    <th width="30%">
      Note
    </th>
		<th>
			Level
		</th>
  </tr>
EOQ;

      $powers = getPowers($characterId, 'Endowment', NAMENOTE, 5);
      
      for($i = 0; $i < sizeof($powers); $i++)
      {
      	$power_dots = FormHelper::Dots("endowment${i}", $powers[$i]->getPowerLevel(), $element_type['merit'], $character_type, $max_dots, $edit_powers, false, $edit_xp);
      	
      	$power_name = $powers[$i]->getPowerName();
				$power_note = $powers[$i]->getPowerNote();
      	$power_id = $powers[$i]->getPowerID();
      	
      	if($edit_powers)
      	{
        	$power_name = <<<EOQ
<input type="text" name="endowment${i}_name" id="endowment${i}_name" size="15" class="$input_class" value="$power_name">
EOQ;
        	$power_note = <<<EOQ
<input type="text" name="endowment${i}_note" id="endowment${i}_note" size="15" class="$input_class" value="$power_note">
EOQ;
      	}
      	
     	  $endowments_list .= <<<EOQ
  <tr>
    <td>
      $power_name
    </td>
		<td>
			$power_note
		</td>
    <td align="center">
      $power_dots
      <input type="hidden" name="endowment${i}_id" id="endowment${i}_id" value="$power_id">
    </td>
  </tr>
EOQ;
          
      }
      $endowments_list .= "</table>";
      
      // tactics
      $supernatural_xp_js = "";
      if($edit_xp)
      {
        $supernatural_xp_js = " onChange=\"updateXP($element_type[merit])\" ";
      }
      
      if($edit_powers)
      {
        $endowments_list .= <<<EOQ
<br>
<a href="#" onClick="addTactic();return false;">Add Tactic</a><br>
EOQ;
      }
      else
      {
        $endowments_list .= "Tactics<br>";
      }

      $endowments_list .= <<<EOQ
<table width="100%" border="0" cellspacing="1" cellpadding="1" class="normal_text" name="tactics_list" id="tactics_list">
  <tr>
    <th width="50%">
      Name
    </th>
    <th width="50%">
      Cost
    </th>
  </tr>
EOQ;

      $powers = getPowers($characterId, 'Tactic', NAMENOTE, 2);
			
			for($i = 0; $i < sizeof($powers); $i++)
      {
        $power_name = $powers[$i]->getPowerName();
        $power_cost = $powers[$i]->getPowerLevel();
        $power_id = $powers[$i]->getPowerID();
        
        if($edit_powers)
        {
          $power_name = <<<EOQ
<input type="text" name="tactic${i}_name" id="tactic${i}_name" size="15" class="$input_class" value="$power_name">

EOQ;
          $power_cost = <<<EOQ
<input type="text" name="tactic{$i}_cost" id="tactic{$i}_cost" size="3" maxlength="2" class="$input_class" value="$power_cost" $supernatural_xp_js>
EOQ;
        }
				
      	$endowments_list .= <<<EOQ
  <tr>
    <td>
      $power_name
    </td>
    <td align="center">
      $power_cost
      <input type="hidden" name="tactic${i}_id" id="tactic${i}_id" value="$power_id" $supernatural_xp_js>
    </td>
  </tr>
EOQ;
			}

			$endowments_list .= "</table>";

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
				    Endowments
				  </th>
				</tr>
				<tr valign="top">
				  <td bgcolor="$cell_bg_color" colspan="3">
  		      $character_merit_list
				  </td>
				  <td bgcolor="$cell_bg_color" colspan="3" rowspan="3">
				    $endowments_list
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
					<td bgcolor="$cell_bg_color" colspan="2">
						$health_dots
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
				    Wounds
				  </td>
				  <td colspan="2" bgcolor="$cell_bg_color">
				    Bashing: $wounds_bashing Lethal: $wounds_lethal Agg: $wounds_aggravated
				  </td>
				  <td colspan="1" bgcolor="$cell_bg_color">
				  	Defense
				  </td>
				  <td colspan="2" bgcolor="$cell_bg_color">
				  	$defense
				  </td>
				</tr>
				<tr>
				  <td colspan="1" bgcolor="$cell_bg_color">
				  	Morality
				  </td>
				  <td colspan="2" bgcolor="$cell_bg_color">
				  	$morality_dots
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
				  	Willpower Perm
				  </td>
				  <td colspan="2" bgcolor="$cell_bg_color">
				  	$willpower_perm_dots
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
				  	Willpower Temp
				  </td>
				  <td colspan="2" bgcolor="$cell_bg_color">
				  	$willpower_temp_dots
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