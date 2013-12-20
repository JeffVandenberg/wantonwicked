<?php
			$vitals_table = <<<EOQ
<table class="character-sheet $table_class">
    <tr>
        <th colspan="4">
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
            <b>Age</b>
        </td>
        <td>
            $age
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
        <th colspan="2">
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
            <b>Home</b>
        </td>
        <td>
            $safe_place
        </td>
    </tr>
</table>
EOQ;

$traits_table = <<<EOQ
<table class="character-sheet $table_class">
    <tr>
        <th colspan="3">
            Merits
			$abilities_help
        </th>
        <th colspan="3">
            Flaws/Derangements
			$abilities_help
        </th>
    </tr>
    <tr valign="top">
        <td colspan="3" width="50%">
            $character_merit_list
        </td>
        <td colspan="3" width="50%">
            $character_flaw_list
        </td>
    </tr>
</table>
<table class="character-sheet mortal_normal_text">
    <tr>
        <th colspan="6">
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
</table>
EOQ;
