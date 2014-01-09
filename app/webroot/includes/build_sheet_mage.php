<?php
/* @var string $table_class */
use classes\core\helpers\FormHelper;

/* @var string $table_class */

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
            <b>Path</b>
        </td>
        <td>
            $splat1
        </td>
        <td>
            <b>Legacy</b>
        </td>
        <td>
            $subsplat
        </td>
    </tr>
    <tr>
        <td>
            <b>Order</b>
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
            <b>Cabal</b>
        </td>
        <td>
            $friends
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
            <b>Familiar</b>
        </td>
        <td>
            $helper
        </td>
    </tr>
    <tr>
        <td>
            <b>Sanctum/Hallow</b>
        </td>
        <td>
            $safe_place
        </td>
    </tr>
</table>
EOQ;

// ruling arcana
$arcana_list = "";
if ($edit_powers) {
    $arcana_list .= <<<EOQ
<a href="#" onClick="addArcana('rulingarcana');return false;">Add Ruling arcana</a><br>
EOQ;
} else {
    $arcana_list .= "Ruling Arcana<br>";
}

$arcana_list .= <<<EOQ
<table class="normal_text" name="rulingarcana_list" id="rulingarcana_list" width="100%">
  <tr>
    <th width="50%">
      Name
    </th>
    <th width="50%">
      Level
    </th>
  </tr>
EOQ;

$arcanas = getPowers($characterId, 'RulingArcana', NAMENOTE, 2);

for ($i = 0; $i < sizeof($arcanas); $i++) {
    $arcana_dots = FormHelper::Dots("rulingarcana${i}", $arcanas[$i]->getPowerLevel(), $element_type['supernatural'], $character_type, $max_dots, $edit_powers, false, $edit_xp);
    $arcana_name = $arcanas[$i]->getPowerName();
    $arcana_id = $arcanas[$i]->getPowerID();

    if ($edit_powers) {
        $arcana_name = <<<EOQ
<input type="text" name="rulingarcana${i}_name" id="rulingarcana${i}_name" size="15" class="$input_class" value="$arcana_name">
EOQ;
    }

    $arcana_list .= <<<EOQ
  <tr>
    <td>
      $arcana_name
    </td>
    <td align="center">
      $arcana_dots
      <input type="hidden" name="rulingarcana${i}_id" id="rulingarcana${i}_id" value="$arcana_id">
    </td>
  </tr>
EOQ;
}
$arcana_list .= "</table>";


// common arcana
if ($edit_powers) {
    $arcana_list .= <<<EOQ
<a href="#" onClick="addArcana('commonarcana');return false;">Add Common Arcana</a><br>
EOQ;
} else {
    $arcana_list .= "Common Arcana<br>";
}

$arcana_list .= <<<EOQ
<table class="normal_text" name="commonarcana_list" id="commonarcana_list" width="100%">
  <tr>
    <th width="50%">
      Name
    </th>
    <th width="50%">
      Level
    </th>
  </tr>
EOQ;

$arcanas = getPowers($characterId, 'CommonArcana', NAMENOTE, 3);

for ($i = 0; $i < sizeof($arcanas); $i++) {
    $arcana_dots = FormHelper::Dots("commonarcana${i}", $arcanas[$i]->getPowerLevel(), $element_type['supernatural'], $character_type, $max_dots, $edit_powers, false, $edit_xp);

    $arcana_name = $arcanas[$i]->getPowerName();
    $arcana_id = $arcanas[$i]->getPowerID();

    if ($edit_powers) {
        $arcana_name = <<<EOQ
<input type="text" name="commonarcana${i}_name" id="commonarcana${i}_name" size="15" class="$input_class" value="$arcana_name">
EOQ;
    }

    $arcana_list .= <<<EOQ
  <tr>
    <td>
      $arcana_name
    </td>
    <td align="center">
      $arcana_dots
      <input type="hidden" name="commonarcana${i}_id" id="commonarcana${i}_id" value="$arcana_id">
    </td>
  </tr>
EOQ;
}
$arcana_list .= "</table>";

// inferior arcana
if ($edit_powers) {
    $arcana_list .= <<<EOQ
<a href="#" onClick="addArcana('inferiorarcana');return false;">Add Inferior Arcana</a><br>
EOQ;
} else {
    $arcana_list .= "Inferior Arcana<br>";
}

$arcanas = getPowers($characterId, 'InferiorArcana', NAMENOTE, 1);

$arcana_list .= <<<EOQ
<table class="normal_text" name="inferiorarcana_list" id="inferiorarcana_list" width="100%">
  <tr>
    <th width="50%">
      Name
    </th>
    <th width="50%">
      Level
    </th>
  </tr>
EOQ;
for ($i = 0; $i < sizeof($arcanas); $i++) {
    $arcana_dots = FormHelper::Dots("inferiorarcana${i}", $arcanas[$i]->getPowerLevel(), $element_type['supernatural'], $character_type, $max_dots, $edit_powers, false, $edit_xp);

    $arcana_name = $arcanas[$i]->getPowerName();
    $arcana_id = $arcanas[$i]->getPowerID();

    if ($edit_powers) {
        $arcana_name = <<<EOQ
<input type="text" name="inferiorarcana${i}_name" id="inferiorarcana${i}_name" size="15" class="$input_class" value="$arcana_name">
EOQ;
    }

    $arcana_list .= <<<EOQ
  <tr>
    <td>
      $arcana_name
    </td>
    <td align="center">
      $arcana_dots
      <input type="hidden" name="inferiorarcana${i}_id" id="inferiorarcana${i}_id" value="$arcana_id">
    </td>
  </tr>
EOQ;
}
$arcana_list .= "</table>";


// rotes
$rote_list = "";
$supernatural_xp_js = "";
if ($edit_xp) {
    $supernatural_xp_js = " onChange=\"updateXP($element_type[supernatural])\" ";
}

if ($edit_powers) {
    $rote_list .= <<<EOQ
<a href="#" onClick="addRote();return false;">Add Rote</a><br>
EOQ;
} else {
    $rote_list .= "Rotes<br>";
}

$rote_list .= <<<EOQ
<table class="normal_text" name="rote_list" id="rote_list" width="100%">
  <tr>
    <th width="40%">
      Name
    </th>
    <th width="40%">
      Note
    </th>
    <th width="20%">
      Level
    </th>
  </tr>
EOQ;

$rotes = getPowers($characterId, 'Rote', NAMENOTE, 5);

for ($i = 0; $i < sizeof($rotes); $i++) {
    $rote_name = $rotes[$i]->getPowerName();
    $rote_note = $rotes[$i]->getPowerNote();
    $rote_level = $rotes[$i]->getPowerLevel();
    $rote_id = $rotes[$i]->getPowerID();

    if ($edit_powers) {
        $rote_name = <<<EOQ
<input type="text" name="rote${i}_name" id="rote${i}_name" size="15" class="$input_class" value="$rote_name">
EOQ;
        $rote_note = <<<EOQ
<input type="text" name="rote{$i}_note" id="rote{$i}_note" size="15" class="$input_class" value="$rote_note">
EOQ;

        $rote_level = <<<EOQ
<input type="text" name="rote{$i}" id="rote{$i}" size="3" maxlength="2" class="$input_class" $supernatural_xp_js value="$rote_level">
EOQ;
    }

    $rote_list .= <<<EOQ
  <tr>
    <td>
      $rote_name
    </td>
    <td>
      $rote_note
    </td>
    <td align="center">
      $rote_level
      <input type="hidden" name="rote${i}_id" id="rote${i}_id" value="$rote_id">
    </td>
  </tr>
EOQ;
}
$rote_list .= "</table>";

$traits_table = <<<EOQ
<table class="character-sheet $table_class">
    <tr>
        <th colspan="3" align="center" width="50%">
            Merits
			$abilities_help
        </th>
        <th colspan="3" align="center" width="50%">
            Flaws/Derangements
			$abilities_help
        </th>
    </tr>
    <tr valign="top">
        <td colspan="3" rowspan="1">
            $character_merit_list
        </td>
        <td colspan="3" rowspan="1">
            $character_flaw_list
        </td>
    </tr>
    <tr>
        <th colspan="3" valign="top">
            Arcana
			$abilities_help
        </th>
        <th colspan="3" valign="top">
            Rotes
			$abilities_help
        </th>
    </tr>
    <tr valign="top">
        <td colspan="3">
            $arcana_list
            </th>
        <td colspan="3">
            $rote_list
            </th>
    </tr>
</table>
<table class="character-sheet $table_class">
    <tr>
        <th colspan="6" align="center">
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
            Gnosis
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
            Wisdom
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
            Mana
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
