<?php
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
        <td><b> Virtue</b></td>
        <td>$virtue</td>
        <td><b>Vice</b></td>
        <td>$vice</td>
    </tr>
    <tr>
        <td>
            <b>Icon</b>
        </td>
        <td>
            $icon
        </td>
        <td>
            <b>Domitor</b>
        </td>
        <td>
            $friends
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
            <b>Apparent Age</b>
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
            <b>Home</b>
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

// in clan
$disciplines_list = "";
if ($edit_powers) {
    $disciplines_list .= <<<EOQ
<a href="#" onClick="addDisc('icdisc');return false;">Add In-Clan Discipline</a><br>
EOQ;
} else {
    $disciplines_list .= "In-Clan Disciplines<br>";
}

$disciplines_list .= <<<EOQ
<table border="0" cellspacing="1" cellpadding="1" class="normal_text" name="icdisc_list" id="icdisc_list" width="100%">
  <tr>
    <th width="50%">
      Name
    </th>
    <th width="50%">
      Level
    </th>
  </tr>
EOQ;
$icdiscs = getPowers($characterId, 'ICDisc', NAMENOTE, 2);

for ($i = 0; $i < sizeof($icdiscs); $i++) {
    $discipline_dots = makeDotsXP("icdisc${i}", $element_type['supernatural'], $character_type, $max_dots, $icdiscs[$i]->getPowerLevel(), $edit_powers, false, $edit_xp);

    $discipline_name = $icdiscs[$i]->getPowerName();
    $discipline_id = $icdiscs[$i]->getPowerID();

    if ($edit_powers) {
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

// out of clan
if ($edit_powers) {
    $disciplines_list .= <<<EOQ
<br>
<a href="#" onClick="addDisc('oocdisc');return false;">Add Out-of-Clan Discipline</a><br>
EOQ;
} else {
    $disciplines_list .= "Out-of-Clan Disciplines<br>";

}

$disciplines_list .= <<<EOQ
<table border="0" cellspacing="1" cellpadding="1" class="normal_text" name="oocdisc_list" id="oocdisc_list" width="100%">
  <tr>
    <th width="50%">
      Name
    </th>
    <th width="50%">
      Level
    </th>
  </tr>
EOQ;

$oocdiscs = getPowers($characterId, 'OOCDisc', NAMENOTE, 2);

for ($i = 0; $i < sizeof($oocdiscs); $i++) {
    $discipline_dots = makeDotsXP("oocdisc${i}", $element_type['supernatural'], $character_type, $max_dots, $oocdiscs[$i]->getPowerLevel(), $edit_powers, false, $edit_xp);

    $discipline_name = $oocdiscs[$i]->getPowerName();
    $discipline_id = $oocdiscs[$i]->getPowerID();

    if ($edit_powers) {
        $discipline_name = <<<EOQ
<input type="text" name="oocdisc${i}_name" id="oocdisc${i}_name" size="15" class="$input_class" value="$discipline_name">
EOQ;
    }

    $disciplines_list .= <<<EOQ
  <tr>
    <td>
      $discipline_name
    </td>
    <td align="center">
      $discipline_dots
      <input type="hidden" name="oocdisc${i}_id" id="oocdisc${i}_id" value="$discipline_id">
    </td>
  </tr>
EOQ;
}
$disciplines_list .= "</table>";

// devotions
$supernatural_xp_js = "";
if ($edit_xp) {
    $supernatural_xp_js = " onChange=\"updateXP($element_type[supernatural])\" ";
}

if ($edit_powers) {
    $disciplines_list .= <<<EOQ
<br>
<a href="#" onClick="addDevotion();return false;">Add Devotion/Ritual/Other</a><br>
EOQ;
} else {
    $disciplines_list .= "Devotions/Rituals/Other<br>";
}

$disciplines_list .= <<<EOQ
<table border="0" cellspacing="1" cellpadding="1" class="normal_text" name="devotion_list" id="devotion_list" width="100%">
  <tr>
    <th width="50%">
      Name
    </th>
    <th width="50%">
      Cost
    </th>
  </tr>
EOQ;

$devotions = getPowers($characterId, 'Devotion', NAMENOTE, 1);

for ($i = 0; $i < sizeof($devotions); $i++) {
    $devotion_name = $devotions[$i]->getPowerName();
    $devotion_cost = $devotions[$i]->getPowerLevel();
    $devotion_id = $devotions[$i]->getPowerID();

    if ($edit_powers) {
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
<table class="character-sheet $table_class">
    <tr>
        <td colspan="6" align="center">
            <b>Abilities</b>
            $abilities_help
        </td>
    </tr>
    <tr>
        <th colspan="3" align="center" width="50%">
            Merits
        </th>
        <th colspan="3" align="center" width="50%">
            Disciplines
        </th>
    </tr>
    <tr valign="top">
        <td colspan="3">
            $character_merit_list
        </td>
        <td colspan="3" rowspan="3">
            $disciplines_list
        </td>
    </tr>
    <tr>
        <th colspan="3" align="center">
            Flaws/Derangements
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
        <th colspan="6" align="center">
            Traits
        </th>
    </tr>
    <tr>
        <td width="15%">
            Health
        </td>
        <td colspan="2" width="50%">
            $health_dots
        </td>
        <td colspan="1" width="15%">
            Size
        </td>
        <td colspan="2" width="20%">
            $size
        </td>
    </tr>
    <tr>
        <td colspan="1" >
            Wounds
        </td>
        <td colspan="2" >
            Bashing: $wounds_bashing Lethal: $wounds_lethal Agg: $wounds_aggravated
        </td>
        <td colspan="1" >
            Defense
        </td>
        <td colspan="2" >
            $defense
        </td>
    </tr>
    <tr>
        <td colspan="1" >
            Morality
        </td>
        <td colspan="2" >
            $morality_dots
        </td>
        <td colspan="1" >
            Initiative Mod
        </td>
        <td colspan="2" >
            $initiative_mod
        </td>
    </tr>
    <tr>
        <td>
            Willpower Perm
        </td>
        <td colspan="2" >
            $willpower_perm_dots
        </td>
        <td colspan="1" >
            Speed
        </td>
        <td colspan="2" >
            $speed
        </td>
    </tr>
    <tr>
        <td>
            Willpower Temp
        </td>
        <td colspan="2" >
            $willpower_temp_dots
        </td>
        <td colspan="1" >
            Armor
        </td>
        <td colspan="2" >
            $armor
        </td>
    </tr>
    <tr>
        <td>
            Blood
        </td>
        <td colspan="2" >
            $power_points_dots
        </td>
        <td colspan="1" >

        </td>
        <td colspan="2" >

        </td>
    </tr>
</table>
EOQ;
