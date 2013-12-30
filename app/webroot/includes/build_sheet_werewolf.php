<?php
/** @var $table_class string */
/** @var $characterId int */
/** @var $max_dots int */
/** @var $input_class string */

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
            <b>Auspice</b>
        </td>
        <td>
            $splat1
        </td>
        <td>
            <b>Lodge</b>
        </td>
        <td>
            $subsplat
        </td>
    </tr>
    <tr>
        <td>
            <b>Tribe</b>
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
            <b>Pack</b>
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
            <b>Totem</b>
        </td>
        <td>
            $helper
        </td>
    </tr>
    <tr>
        <td>
            <b>Territory/Loci</b>
        </td>
        <td>
            $safe_place
        </td>
    </tr>
</table>
EOQ;

$werewolfupdate_js = "";
if ($edit_xp) {
    $werewolfupdate_js = " onChange=\"updateXP($element_type[supernatural])\" ";
}

if ($edit_powers) {
    $gift_list .= <<<EOQ
<a href="#" onClick="addGift('affgift');return false;">Add Affinity Gift</a><br>
EOQ;
} else {
    $gift_list .= "Affinity Gifts<br>";
}

$gift_list .= <<<EOQ
<table class="normal_text" name="affgift_list" id="affgift_list" width="100%">
  <tr>
    <th width="35%">
      Gift List
    </th>
    <th width="35%">
      Gift Name
    </th>
    <th width="30%">
      Gift Rank
    </th>
  </tr>
EOQ;

$gifts = getPowers($characterId, 'AffGift', NOTELEVEL, 5);

for ($i = 0; $i < sizeof($gifts); $i++) {
    $gift_dots = makeDotsXP("affgift${i}", $element_type['supernatural'], $character_type, $max_dots, $gifts[$i]->getPowerLevel(), $edit_powers, false, $edit_xp);

    $gift_note = $gifts[$i]->getPowerNote();
    $gift_name = $gifts[$i]->getPowerName();
    $gift_id = $gifts[$i]->getPowerID();

    if ($edit_powers) {
        $gift_note = <<<EOQ
<input type="text" name="affgift${i}_note" id="affgift${i}_note" size="15" class="$input_class" value="$gift_note">
EOQ;
        $gift_name = <<<EOQ
<input type="text" name="affgift${i}_name" id="affgift${i}_name" size="15" class="$input_class" value="$gift_name">
EOQ;
    }

    $gift_list .= <<<EOQ
  <tr>
    <td>
      $gift_note
    </td>
    <td>
      $gift_name
    </td>
    <td align="center">
      $gift_dots
      <input type="hidden" name="affgift${i}_id" id="affgift${i}_id" value="$gift_id">
    </td>
  </tr>
EOQ;
}
$gift_list .= "</table>";

if ($edit_powers) {
    $gift_list .= <<<EOQ
<a href="#" onClick="addGift('nonaffgift');return false;">Add Non-Affinity Gift</a><br>
EOQ;
} else {
    $gift_list .= "Non-Affinity Gifts<br>";
}

$gift_list .= <<<EOQ
<table class="normal_text" name="nonaffgift_list" id="nonaffgift_list" width="100%">
  <tr>
    <th width="35%">
      Gift List
    </th>
    <th width="35%">
      Gift Name
    </th>
    <th width="30%">
      Gift Rank
    </th>
  </tr>
EOQ;

$gifts = getPowers($characterId, 'NonAffGift', NOTELEVEL, 3);

for ($i = 0; $i < sizeof($gifts); $i++) {
    $gift_dots = makeDotsXP("nonaffgift${i}", $element_type['supernatural'], $character_type, $max_dots, $gifts[$i]->getPowerLevel(), $edit_powers, false, $edit_xp);

    $gift_note = $gifts[$i]->getPowerNote();
    $gift_name = $gifts[$i]->getPowerName();
    $gift_id = $gifts[$i]->getPowerID();

    if ($edit_powers) {
        $gift_note = <<<EOQ
<input type="text" name="nonaffgift${i}_note" id="nonaffgift${i}_note" size="15" class="$input_class" value="$gift_note">
EOQ;
        $gift_name = <<<EOQ
<input type="text" name="nonaffgift${i}_name" id="nonaffgift${i}_name" size="15" class="$input_class" value="$gift_name">
EOQ;
    }

    $gift_list .= <<<EOQ
  <tr>
    <td>
      $gift_note
    </td>
    <td>
      $gift_name
    </td>
    <td align="center">
      $gift_dots
      <input type="hidden" name="nonaffgift${i}_id" id="nonaffgift${i}_id" value="$gift_id">
    </td>
  </tr>
EOQ;
}

$gift_list .= "</table>";

$merit_js = "";
if ($edit_xp) {
    $werewolfupdate_js = " onChange=\"updateXP($element_type[merit])\" ";
}

$ritual_list = "";
if ($edit_powers) {
    $ritual_list .= <<<EOQ
<a href="#" onClick="addRitual();return false;">Add Ritual</a><br>
EOQ;
} else {
    $ritual_list .= "Rituals<br>";
}

$renowns = getRenownsRituals($characterId);

$rituals_dots = makeDotsXP("rituals", $element_type['supernatural'], $character_type, $max_dots, $renowns["rituals"]->getPowerLevel(), $edit_powers, false, $edit_xp);
$rituals_id = $renowns["rituals"]->getPowerID();

$ritual_list .= <<<EOQ
<table class="normal_text" name="ritual_list" id="ritual_list">
  <tr>
    <td>
      Rituals
    </td>
    <td>
      $rituals_dots
      <input type="hidden" name="rituals_id" id="rituals_id" value="$rituals_id">
    </td>
  </tr>
  <tr>
    <th>
      Ritual Name
    </th>
    <th>
      Ritual Rank
    </th>
  </tr>
EOQ;
$rituals = getPowers($characterId, 'Ritual', NAMENOTE, 2);

for ($i = 0; $i < sizeof($rituals); $i++) {
    $ritual_dots = makeDotsXP("ritual${i}", $element_type['merit'], $character_type, $max_dots, $rituals[$i]->getPowerLevel(), $edit_powers, false, $edit_xp);

    $ritual_name = $rituals[$i]->getPowerName();
    $ritual_id = $rituals[$i]->getPowerID();

    if ($edit_powers) {
        $ritual_name = <<<EOQ
<input type="text" name="ritual${i}_name" id="ritual${i}_name" size="15" class="$input_class" value="$ritual_name">
EOQ;
    }

    $ritual_list .= <<<EOQ
  <tr>
    <td>
      $ritual_name
    </td>
    <td>
      $ritual_dots
      <input type="hidden" name="ritual${i}_id" id="ritual${i}_id" value="$ritual_id">
    </td>
  </tr>
EOQ;
}

$ritual_list .= "</table>";

$purity_dots = makeDotsXP("purity", $element_type['supernatural'], $character_type, $max_dots, $renowns["purity"]->getPowerLevel(), $edit_powers, false, $edit_xp);
$purity_id = $renowns["purity"]->getPowerID();

$glory_dots = makeDotsXP("glory", $element_type['supernatural'], $character_type, $max_dots, $renowns["glory"]->getPowerLevel(), $edit_powers, false, $edit_xp);
$glory_id = $renowns["glory"]->getPowerID();

$honor_dots = makeDotsXP("honor", $element_type['supernatural'], $character_type, $max_dots, $renowns["honor"]->getPowerLevel(), $edit_powers, false, $edit_xp);
$honor_id = $renowns["honor"]->getPowerID();

$wisdom_dots = makeDotsXP("wisdom", $element_type['supernatural'], $character_type, $max_dots, $renowns["wisdom"]->getPowerLevel(), $edit_powers, false, $edit_xp);
$wisdom_id = $renowns["wisdom"]->getPowerID();

$cunning_dots = makeDotsXP("cunning", $element_type['supernatural'], $character_type, $max_dots, $renowns["cunning"]->getPowerLevel(), $edit_powers, false, $edit_xp);
$cunning_id = $renowns["cunning"]->getPowerID();

$traits_table = <<<EOQ
<table class="character-sheet $table_class">
    <tr>
    </tr>
    <tr>
        <th colspan="3" align="center" width="50%">
            Merits
			$abilities_help
        </th>
        <th colspan="3" align="center" width="50%">
            Gifts
			$abilities_help
        </th>
    </tr>
    <tr valign="top">
        <td colspan="3" rowspan="1">
            $character_merit_list
        </td>
        <td colspan="3" rowspan="3">
            $gift_list
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
    <tr>
        <th colspan="3">
            Rituals
			$abilities_help
        </th>
        <th colspan="3">
            Renown
			$abilities_help
        </th>
    </tr>
    <tr>
        <td colspan="3" rowspan="3" valign="top">
            $ritual_list
        </td>
        <td colspan="3" rowspan="1" valign="top">
            <table border="0" cellpadding="1" cellspacing="1" class="normal_text">
                <tr>
                    <td>
                        Purity
                    </td>
                    <td>
                        $purity_dots
                        <input type="hidden" name="purity_id" id="purity_id" value="$purity_id">
                    </td>
                </tr>
                <tr>
                    <td>
                        Glory
                    </td>
                    <td>
                        $glory_dots
                        <input type="hidden" name="glory_id" id="glory_id" value="$glory_id">
                    </td>
                </tr>
                <tr>
                    <td>
                        Honor
                    </td>
                    <td>
                        $honor_dots
                        <input type="hidden" name="honor_id" id="honor_id" value="$honor_id">
                    </td>
                </tr>
                <tr>
                    <td>
                        Wisdom
                    </td>
                    <td>
                        $wisdom_dots
                        <input type="hidden" name="wisdom_id" id="wisdom_id" value="$wisdom_id">
                    </td>
                </tr>
                <tr>
                    <td>
                        Cunning
                    </td>
                    <td>
                        $cunning_dots
                        <input type="hidden" name="cunning_id" id="cunning_id" value="$cunning_id">
                    </td>
                </tr>
            </table>
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
            Primal Urge
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
            Harmony
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
            Essence
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
