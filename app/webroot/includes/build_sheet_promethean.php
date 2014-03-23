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
						<b>Lineage</b>
					</td>
					<td bgcolor="$cell_bg_color">
						$splat1
					</td>
					<td bgcolor="$cell_bg_color">
						<b>Athanor</b>
					</td>
					<td bgcolor="$cell_bg_color">
						$subsplat
					</td>
				</tr>
				<tr>
					<td bgcolor="$cell_bg_color">
						<b>Refinement</b>
					</td>
					<td bgcolor="$cell_bg_color">
						$splat2
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
						<b>Age</b>
					</td>
					<td bgcolor="$cell_bg_color">
						$age
					</td>
					<td bgcolor="$cell_bg_color">
						<b>Throng</b>
					</td>
					<td bgcolor="$cell_bg_color">
						$friends
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
						<b>Current Lodgings</b>
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

// powers
$powers_list = "";

// bestowments
$supernatural_xp_js = "";

if ($edit_xp) {
    $supernatural_xp_js = " onChange=\"updateXP($element_type[supernatural])\" ";
}

if ($edit_powers) {
    $powers_list .= <<<EOQ
<a href="#" onClick="addBestowment();return false;">Add Bestowment</a><br>
EOQ;
} else {
    $powers_list .= "Bestowments<br>";
}

$powers_list .= <<<EOQ
<table border="0" cellspacing="1" cellpadding="1" class="normal_text" name="bestowment_list" id="bestowment_list" width="100%">
  <tr>
    <th width="50%">
      Name
    </th>
    <th width="50%">
      Cost
    </th>
  </tr>
EOQ;

$bestowments = getPowers($characterId, 'Bestowment', NAMELEVEL, 2);

for ($i = 0; $i < sizeof($bestowments); $i++) {
    $bestowment_name = $bestowments[$i]->getPowerName();
    $bestowment_cost = $bestowments[$i]->getPowerLevel();
    $bestowment_id = $bestowments[$i]->getPowerID();

    if ($edit_powers) {
        $bestowment_name = <<<EOQ
<input type="text" name="bestowment${i}_name" id="bestowment${i}_name" size="15" class="$input_class" value="$bestowment_name">
EOQ;

        $bestowment_cost = <<<EOQ
<input type="text" name="bestowment{$i}_cost" id="bestowment{$i}_cost" size="3" maxlength="2" class="$input_class" value="$bestowment_cost" $supernatural_xp_js>
EOQ;
    }

    $powers_list .= <<<EOQ
  <tr>
    <td>
      $bestowment_name
    </td>
    <td align="center">
      $bestowment_cost
      <input type="hidden" name="bestowment${i}_id" id="bestowment${i}_id" value="$bestowment_id" $supernatural_xp_js>
    </td>
  </tr>
EOQ;
}
$powers_list .= "</table>";

// affinity transmutations
if ($edit_powers) {
    $powers_list .= <<<EOQ
<br>
<a href="#" onClick="addTrans('afftrans');return false;">Add Affinity Transmutation</a><br>
EOQ;
} else {
    $powers_list .= "Affinity Transmutations<br>";
}

$powers_list .= <<<EOQ
<table border="0" cellspacing="1" cellpadding="1" class="normal_text" name="afftrans_list" id="afftrans_list" width="100%">
  <tr>
    <th width="35%">
      List
    </th>
    <th width="35%">
      Name
    </th>
    <th width="30%">
      Level
    </th>
  </tr>
EOQ;

$powers = getPowers($characterId, 'AffTrans', NOTELEVEL, 4);

for ($i = 0; $i < sizeof($powers); $i++) {
    $afftrans_dots = FormHelper::Dots("afftrans${i}", $powers[$i]->getPowerLevel(), $element_type['supernatural'], $character_type, $max_dots, $edit_powers, false, $edit_xp);

    $afftrans_list = $powers[$i]->getPowerNote();
    $afftrans_name = $powers[$i]->getPowerName();
    $afftrans_id = $powers[$i]->getPowerID();

    if ($edit_powers) {
        $afftrans_list = <<<EOQ
<input type="text" name="afftrans${i}_list" id="afftrans${i}_list" size="15" class="$input_class" value="$afftrans_list">
EOQ;

        $afftrans_name = <<<EOQ
<input type="text" name="afftrans${i}_name" id="afftrans${i}_name" size="15" class="$input_class" value="$afftrans_name">
EOQ;
    }

    $powers_list .= <<<EOQ
  <tr>
    <td>
      $afftrans_list
    </td>
    <td>
      $afftrans_name
    </td>
    <td align="center">
      $afftrans_dots
      <input type="hidden" name="afftrans${i}_id" id="afftrans${i}_id" value="$afftrans_id">
    </td>
  </tr>
EOQ;
}
$powers_list .= "</table>";

// out of clan
if ($edit_powers) {
    $powers_list .= <<<EOQ
<br>
<a href="#" onClick="addTrans('nonafftrans');return false;">Add Non-Affinity Transmutation</a><br>
EOQ;
} else {
    $powers_list .= "Non-Affinity Transmutations<br>";
}

$powers_list .= <<<EOQ
<table border="0" cellspacing="1" cellpadding="1" class="normal_text" name="nonafftrans_list" id="nonafftrans_list" width="100%">
  <tr>
    <th width="35%">
      List
    </th>
    <th width="35%">
      Name
    </th>
    <th width="30%">
      Level
    </th>
  </tr>
EOQ;

$powers = getPowers($characterId, 'NonAffTrans', NOTELEVEL, 2);

for ($i = 0; $i < sizeof($powers); $i++) {
    $nonafftrans_dots = FormHelper::Dots("nonafftrans${i}", $powers[$i]->getPowerLevel(), $element_type['supernatural'], $character_type, $max_dots, $edit_powers, false, $edit_xp);

    $nonafftrans_list = $powers[$i]->getPowerNote();
    $nonafftrans_name = $powers[$i]->getPowerName();
    $nonafftrans_id = $powers[$i]->getPowerID();

    if ($edit_powers) {
        $nonafftrans_list = <<<EOQ
<input type="text" name="nonafftrans${i}_list" id="nonafftrans${i}_list" size="15" class="$input_class" value="$nonafftrans_list">
EOQ;

        $nonafftrans_name = <<<EOQ
<input type="text" name="nonafftrans${i}_name" id="nonafftrans${i}_name" size="15" class="$input_class" value="$nonafftrans_name">
EOQ;
    }

    $powers_list .= <<<EOQ
  <tr>
    <td>
      $nonafftrans_list
    </td>
    <td>
      $nonafftrans_name
    </td>
    <td align="center">
      $nonafftrans_dots
      <input type="hidden" name="nonafftrans${i}_id" id="nonafftrans${i}_id" value="$nonafftrans_id">
    </td>
  </tr>
EOQ;
}
$powers_list .= "</table>";

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
				    Transmutations
				  </th>
				</tr>
				<tr valign="top">
				  <td bgcolor="$cell_bg_color" colspan="3">
  		      $character_merit_list
				  </td>
				  <td bgcolor="$cell_bg_color" colspan="3" rowspan="3">
				    $powers_list
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
				  	Azoth
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
				  	Pyros
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