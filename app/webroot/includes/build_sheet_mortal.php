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

ob_start();
?>
    <div style="float:left;width:50%;">
        <?php echo $character_merit_list; ?>
    </div>
    <div style="float:left;width:50%;">
        <?php echo $character_flaw_list; ?>
        <?php echo $characterMiscList; ?>
    </div>
    <table class="character-sheet mortal_normal_text">
        <tr>
            <th colspan="6">
                Traits
            </th>
        </tr>
        <tr>
            <td style="width:15%">
                Health
            </td>
            <td colspan="2" style="width:50%">
                <?php echo $health_dots; ?>
            </td>
            <td colspan="1" style="width:15%">
                Size
            </td>
            <td colspan="2" style="width:20%">
                <?php echo $size; ?>
            </td>
        </tr>
        <tr>
            <td colspan="1">
                Wounds
            </td>
            <td colspan="2" style="white-space: nowrap;">
                Bashing: <?php echo $wounds_bashing; ?>
                Lethal: <?php echo $wounds_lethal; ?>
                Agg: <?php echo $wounds_aggravated; ?>
            </td>
            <td colspan="1">
                Defense
            </td>
            <td colspan="2">
                <?php echo $defense; ?>
            </td>
        </tr>
        <tr>
            <td colspan="1">
                Morality
            </td>
            <td colspan="2">
                <?php echo $morality_dots; ?>
            </td>
            <td colspan="1">
                Initiative Mod
            </td>
            <td colspan="2">
                <?php echo $initiative_mod; ?>
            </td>
        </tr>
        <tr>
            <td>
                Willpower Perm
            </td>
            <td colspan="2">
                <?php echo $willpower_perm_dots; ?>
            </td>
            <td colspan="1">
                Speed
            </td>
            <td colspan="2">
                <?php echo $speed; ?>
            </td>
        </tr>
        <tr>
            <td>
                Willpower Temp
            </td>
            <td colspan="2">
                <?php echo $willpower_temp_dots; ?>
            </td>
            <td colspan="1">
                Armor
            </td>
            <td colspan="2">
                <?php echo $armor; ?>
            </td>
        </tr>
    </table>
<?php
$traits_table = ob_get_clean();