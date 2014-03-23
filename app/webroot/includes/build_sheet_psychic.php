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
					<td bgcolor="$cell_bg_color"><b> Virtue</b></td>
					<td bgcolor="$cell_bg_color">$virtue</td>
					<td bgcolor="$cell_bg_color"><b>Vice</b></td>
					<td bgcolor="$cell_bg_color">$vice</td>
				</tr>
				<tr>
					<td bgcolor="$cell_bg_color">
						<b>Icon</b>
					</td>
					<td bgcolor="$cell_bg_color">
						$icon
					</td>
					<td bgcolor="$cell_bg_color">
						<b>Age</b>
					</td>
					<td bgcolor="$cell_bg_color">
						$age
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

      $psychic_merits_list = "";
      $supernaturalupdate_js = "";
      if($edit_xp)
      {
        $supernaturalupdate_js = " onChange=\"updateXP($element_type[supernatural])\" ";
      }
      
      if($edit_powers)
      {
        $psychic_merits_list .= <<<EOQ
<a href="#" onClick="addPsychicMerit();return false;">Add Psychic Merit</a><br>
EOQ;
      }
      else
      {
        $psychic_merits_list .= "Psychic Merits<br>";
      }
      
      $psychic_merits_list .= <<<EOQ
<table border="0" cellspacing="1" cellpadding="1" class="normal_text" name="psychic_merit_list" id="psychic_merit_list">
  <tr>
    <th width="35%">
      Merit Name
    </th>
    <th width="35%">
      Merit Note
    </th>
    <th width="30%">
      Merit Level
    </th>
  </tr>
EOQ;

      $psychic_merits = getPowers($characterId, 'PsychicMerit', NOTELEVEL, 3);
      
      for($i = 0; $i < sizeof($psychic_merits); $i++)
      {
      	$psychic_merit_dots = FormHelper::Dots("psychicmerit${i}", $psychic_merits[$i]->getPowerLevel(), $element_type['merit'], $character_type, $max_dots, $edit_powers, false, $edit_xp);
      	
      	$psychic_merit_note = $psychic_merits[$i]->getPowerNote();
      	$psychic_merit_name = $psychic_merits[$i]->getPowerName();
      	$psychic_merit_id = $psychic_merits[$i]->getPowerID();
      	
      	if($edit_powers)
      	{
        	$psychic_merit_name = <<<EOQ
<input type="text" name="psychicmerit${i}_name" id="psychicmerit${i}_name" size="15" class="$input_class" value="$psychic_merit_name">
EOQ;

        	$psychic_merit_note = <<<EOQ
<input type="text" name="psychicmerit${i}_note" id="psychicmerit${i}_note" size="15" class="$input_class" value="$psychic_merit_note">
EOQ;
      	}
      	
      	$psychic_merits_list .= <<<EOQ
  <tr>
    <td>
      $psychic_merit_name
    </td>
    <td>
      $psychic_merit_note
    </td>
    <td align="center">
      $psychic_merit_dots
      <input type="hidden" name="psychicmerit${i}_id" id="psychicmerit${i}_id" value="$psychic_merit_id">
    </td>
  </tr>
EOQ;
      }
      $psychic_merits_list .= "</table>";
      
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
				  <th bgcolor="$cell_bg_color" colspan="3" align="center">
				    Merits
				  </th>
				  <th bgcolor="$cell_bg_color" colspan="3" align="center">
				    Psychic Merits
				  </th>
				</tr>
				<tr valign="top">
				  <td bgcolor="$cell_bg_color" colspan="3" rowspan="3" width="50%">
			      $character_merit_list
				  </td>
				  <td bgcolor="$cell_bg_color" colspan="3" width="50%">
				    $psychic_merits_list
				  </td>
				</tr>
				<tr>
				  <th bgcolor="$cell_bg_color" colspan="3" align="center">
				    Flaws/Derangements
				  </th>
				</tr>
				<tr>
				  <td bgcolor="$cell_bg_color" colspan="3" width="50%">
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
					<td bgcolor="$cell_bg_color" colspan="2" width="50%">
						$health_dots
					</td>
				  <td colspan="1" bgcolor="$cell_bg_color" width="15%">
				  	Size
				  </td>
				  <td colspan="2" bgcolor="$cell_bg_color" width="20%">
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