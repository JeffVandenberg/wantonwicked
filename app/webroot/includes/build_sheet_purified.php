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

// Numina
$power_list = "";
$supernatural_xp_js = "";
if ($edit_xp) {
    $supernatural_xp_js = " onBlur=\"updateXP($element_type[supernatural])\" ";
}

if ($edit_powers) {
    $power_list .= <<<EOQ
<a href="#" onClick="addNumina();return false;">Add Numina</a><br>
EOQ;
} else {
    $power_list .= "Numina<br>";
}

$power_list .= <<<EOQ
<table border="0" cellspacing="1" cellpadding="1" class="normal_text" name="numina_list" id="numina_list" width="100%">
  <tr>
    <th width="10%">
      Name
    </th>
  </tr>
EOQ;

$powers = getPowers($characterId, 'Numina', NAMENOTE, 2);

for ($i = 0; $i < sizeof($powers); $i++) {
    $power_name = $powers[$i]->getPowerName();
    $power_id = $powers[$i]->getPowerID();

    if ($edit_powers) {
        $power_name = <<<EOQ
<input type="text" name="numina${i}_name" id="numina${i}_name" size="15" class="$input_class" value="$power_name" $supernatural_xp_js>
EOQ;
    }

    $power_list .= <<<EOQ
  <tr>
    <td>
      $power_name
      <input type="hidden" name="numina${i}_id" id="numina${i}_id" value="$power_id">
    </td>
  </tr>
EOQ;
}
$power_list .= "</table>";

// Siddhi
if ($edit_powers) {
    $power_list .= <<<EOQ
<br>
<a href="#" onClick="addSiddhi();return false;">Add Siddhi</a><br>
EOQ;
} else {
    $power_list .= "Siddhi<br>";
}

$power_list .= <<<EOQ
<table border="0" cellspacing="1" cellpadding="1" class="normal_text" name="siddhi_list" id="siddhi_list" width="100%">
  <tr>
    <th width="60%">
      Name
    </th>
    <th width="40%">
      Level
    </th>
  </tr>
EOQ;

$powers = getPowers($characterId, 'Siddhi', NAMENOTE, 4);

for ($i = 0; $i < sizeof($powers); $i++) {
    $power_dots = FormHelper::Dots("siddhi${i}", $powers[$i]->getPowerLevel(), $element_type['supernatural'], $character_type, $max_dots, $edit_powers, false, $edit_xp);

    $power_name = $powers[$i]->getPowerName();
    $power_id = $powers[$i]->getPowerID();

    if ($edit_powers) {
        $power_name = <<<EOQ
<input type="text" name="siddhi${i}_name" id="siddhi${i}_name" size="15" class="$input_class" value="$power_name">
EOQ;
    }

    $power_list .= <<<EOQ
  <tr>
    <td>
      $power_name
    </td>
    <td>
      $power_dots
      <input type="hidden" name="siddhi${i}_id" id="siddhi${i}_id" value="$power_id">
    </td>
  </tr>
EOQ;
}
$power_list .= "</table>";


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
				    Powers
					</th>
				</tr>
				<tr valign="top">
				  <td bgcolor="$cell_bg_color" colspan="3">
  		      $character_merit_list
				  </td>
				  <td bgcolor="$cell_bg_color" colspan="3" rowspan="3">
				    $power_list
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
					<th colspan="6">
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
				  <td colspan="2" bgcolor="$cell_bg_color" width="40%" style="white-space: nowrap;">
				    Bashing: $wounds_bashing Lethal: $wounds_lethal Agg: $wounds_aggravated
				  </td>
			</tr>
				<tr>
				  <td colspan="1" bgcolor="$cell_bg_color">
				  	Chi
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
				  	Morality
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
				  	Essence
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