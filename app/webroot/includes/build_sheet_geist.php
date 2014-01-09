<?php
/* @var string $table_class */
use classes\core\helpers\FormHelper;

$vitals_table = <<<EOQ
<table class="character-sheet $table_class">
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
            <b>Sex:</b>
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
            <b>Archetype</b>
        </td>
        <td>
            $splat1
        </td>
        <td>
            <b>Threshold</b>
        </td>
        <td>
            $splat2
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
            <b>Icon</b>
        </td>
        <td>
            $icon
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
    $admin_row
</table>
EOQ;

$information_table = <<<EOQ
<table class="character-sheet $table_class">
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
            <b>Krewe</b>
        </td>
        <td>
            $friends
        </td>
    </tr>
    <tr>
        <td>
            <b>Haunt</b>
        </td>
        <td>
            $safe_place
        </td>
    </tr>
</table>
EOQ;

// Keys
$power_list = "";
$supernatural_xp_js = "";
if ($edit_xp) {
    $supernatural_xp_js = " onBlur=\"updateXP($element_type[supernatural])\" ";
}

if ($edit_powers) {
    $power_list .= <<<EOQ
<a href="#" onClick="addKey();return false;">Add Key</a><br>
EOQ;
}
else {
    $power_list .= "Keys<br>";
}

$power_list .= <<<EOQ
<table class="normal_text" name="key_list" id="key_list" width="100%">
  <tr>
    <th width="10%">
      Name
    </th>
  </tr>
EOQ;

$powers = getPowers($characterId, 'Key', NAMENOTE, 2);

for ($i = 0; $i < sizeof($powers); $i++) {
    $power_name = $powers[$i]->getPowerName();
    $power_id = $powers[$i]->getPowerID();

    if ($edit_powers) {
        $power_name = <<<EOQ
<input type="text" name="key${i}_name" id="key${i}_name" size="15" class="$input_class" value="$power_name" $supernatural_xp_js>
EOQ;
    }

    $power_list .= <<<EOQ
  <tr>
    <td>
      $power_name
      <input type="hidden" name="key${i}_id" id="key${i}_id" value="$power_id">
    </td>
  </tr>
EOQ;
}
$power_list .= "</table>";

// Manifestations
if ($edit_powers) {
    $power_list .= <<<EOQ
<br>
<a href="#" onClick="addManifestation();return false;">Add Manifestation</a><br>
EOQ;
}
else {
    $power_list .= "Manifestations<br>";
}

$power_list .= <<<EOQ
<table class="normal_text" name="manifestation_list" id="manifestation_list" width="100%">
  <tr>
    <th width="60%">
      Name
    </th>
    <th width="40%">
      Level
    </th>
  </tr>
EOQ;

$powers = getPowers($characterId, 'manifestation', NAMENOTE, 4);

for ($i = 0; $i < sizeof($powers); $i++) {
    $power_dots = FormHelper::Dots("manifestation${i}", $powers[$i]->getPowerLevel(), $element_type['supernatural'], $character_type, $max_dots, $edit_powers, false, $edit_xp);

    $power_name = $powers[$i]->getPowerName();
    $power_id = $powers[$i]->getPowerID();

    if ($edit_powers) {
        $power_name = <<<EOQ
<input type="text" name="manifestation${i}_name" id="manifestation${i}_name" size="15" class="$input_class" value="$power_name">
EOQ;
    }

    $power_list .= <<<EOQ
  <tr>
    <td>
      $power_name
    </td>
    <td>
      $power_dots
      <input type="hidden" name="manifestation${i}_id" id="manifestation${i}_id" value="$power_id">
    </td>
  </tr>
EOQ;
}
$power_list .= "</table>";

// Manifestations
if ($edit_powers) {
    $power_list .= <<<EOQ
<br>
<a href="#" onClick="addCeremony();return false;">Add Ceremony</a><br>
EOQ;
}
else {
    $power_list .= "Ceremonies<br>";
}

$power_list .= <<<EOQ
<table class="normal_text" name="ceremony_list" id="ceremony_list" width="100%">
  <tr>
    <th width="60%">
      Name
    </th>
    <th width="40%">
      Level
    </th>
  </tr>
EOQ;

$powers = getPowers($characterId, 'Ceremonies', NAMENOTE, 2);

for ($i = 0; $i < sizeof($powers); $i++) {
    $power_dots = FormHelper::Dots("ceremony${i}", $powers[$i]->getPowerLevel(), $element_type['merit'], $character_type, $max_dots, $edit_powers, false, $edit_xp);

    $power_name = $powers[$i]->getPowerName();
    $power_id = $powers[$i]->getPowerID();

    if ($edit_powers) {
        $power_name = <<<EOQ
<input type="text" name="ceremony${i}_name" id="ceremony${i}_name" size="15" class="$input_class" value="$power_name">
EOQ;
    }

    $power_list .= <<<EOQ
  <tr>
    <td>
      $power_name
    </td>
    <td>
      $power_dots
      <input type="hidden" name="ceremony${i}_id" id="ceremony${i}_id" value="$power_id">
    </td>
  </tr>
EOQ;
}
$power_list .= "</table>";


$traits_table = <<<EOQ
<table class="character-sheet $table_class">
    <tr>
      <th bgcolor="$cell_bg_color" colspan="3" align="center" width="50%">
        Merits
		$abilities_help
      </th>
      <th bgcolor="$cell_bg_color" colspan="3" align="center" width="50%">
        Powers
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
      <th bgcolor="$cell_bg_color" colspan="3" align="center">
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
<table class="character-sheet $table_class">
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
          <td colspan="1" bgcolor="$cell_bg_color" width="15%">
        Wounds
      </td>
      <td colspan="2" bgcolor="$cell_bg_color" width="40%">
        Bashing: $wounds_bashing Lethal: $wounds_lethal Agg: $wounds_aggravated
      </td>
</tr>
    <tr>
      <td colspan="1" bgcolor="$cell_bg_color">
        Psyche
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
        Synergy
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
      <td>
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
      <td>
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
      <td>
        Plasm
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
EOQ;
