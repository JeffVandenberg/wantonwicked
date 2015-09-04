<?php
use classes\core\helpers\UserdataHelper;

include_once 'includes/classes/character/character.php';

$character_name = $_GET['username'];
if (strpos($character_name, '-- ') > 0) {
    $character_name = substr($character_name, 0, strpos($character_name, '-- '));
}
$character_query = "select * from characters where character_name = '$character_name';";
$character_result = mysql_query($character_query) or die(mysql_error());
$character_detail = mysql_fetch_array($character_result, MYSQL_ASSOC);
//print_r($character_detail);
if ($character_detail) {
    if (false && UserdataHelper::IsSt($userdata)) {
        // display character
        header("Location: http://www.wantonwicked.net/view_sheet.php?action=st_view_xp&view_character_id=$character_detail[id]");
    } else {
        $profile_query = "select username from phpbb_users where user_id = " . $character_detail['user_id'];
        $profile_result = mysql_query($profile_query) or die(mysql_error());
        $profile_detail = mysql_fetch_array($profile_result, MYSQL_ASSOC);

        $broken_favors_query = <<<EOQ
SELECT
	favors.*,
	from_character.character_name AS from_character_name,
	to_character.character_name AS to_character_name,
	favor_types.name AS favor_type_name
FROM
	favors
		LEFT JOIN characters AS from_character ON favors.source_id = from_character.id
		LEFT JOIN characters AS to_character ON favors.target_id = to_character.id
		LEFT JOIN favor_types ON favors.favor_type_id = favor_types.id
WHERE
	favors.source_id = $character_detail[id]
	AND favors.favor_type_id = 1
	AND favors.is_broken = 1
EOQ;

        $broken_favors_result = mysql_query($broken_favors_query) or die(mysql_error());

        $broken_favors = "";
        while ($broken_favors_detail = mysql_fetch_array($broken_favors_result, MYSQL_ASSOC)) {
            $broken_favors .= "Owed a favor to $broken_favors_detail[to_character_name] and broke it on $broken_favors_detail[date_broken].<br />";
        }

        if ($broken_favors == "") {
            $broken_favors = "None.";
        }


        $page_title = "View $character_detail[Character_Name]";
        $extra_public_effects = "";
        //print_r($character_detail);
        $age = $character_detail['Age'];
        switch ($character_detail['Character_Type']) {
            case 'Vampire':
                $blood_potency = DetermineBloodPotency($_GET['srcuid'], $character_detail);
                $extra_public_effects = "Blood Potency: $blood_potency, ";
            case 'Possessed':
            case 'Purified':
                $age = $character_detail['Apparent_Age'];
                break;
        }

        $page_content = <<<EOQ
<h3>Profile Information</h3>
<table style="width:100%;border:none;text-align:left;margin-top:20px;">
	<tr>
		<td style="width:120px;">
			Character Name
		</td>
		<td>
			$character_detail[Character_Name]
		</td>
	</tr>
	<tr>
		<td style="width:120px;">
			Profile
		</td>
		<td>
			$profile_detail[username]
		</td>
	</tr>
	<tr style="background-color:#333333;">
		<td>
			Age
		</td>
		<td>
			$age
		</td>
	</tr>
	<tr>
		<td>
			Description
		</td>
		<td>
			$character_detail[Description]
		</td>
	</tr>
	<tr style="background-color:#333333;">
		<td>
			Public Effects
		</td>
		<td>
			$extra_public_effects $character_detail[Public_Effects]
		</td>
	</tr>
	<tr>
		<td>
			Equipment
		</td>
		<td>
			$character_detail[Equipment_Public]
		</td>
	</tr>
</table>
<h2>Broken Favors</h2>
$broken_favors
EOQ;
    }
} else {
    $page_content = "Unable to find $_GET[username].";
}

function DetermineBloodPotency($sourceCharacterId, $targetCharacter)
{
    $bloodPotency = $targetCharacter['Power_Stat'];
    // do they have obfuscate 2?
    $characterDao = new Character();
    if ($characterDao->DoesCharacterHavePowerAtLevel($targetCharacter['id'], 'Obfuscate', 2)) {
        $bloodPotency = 'None';
    } else if ($characterDao->DoesCharacterHavePowerAtLevel($targetCharacter['id'], 'Protean', 1)) {
        // do they have Protean 1?
        $sourceCharacter = $characterDao->GetById($sourceCharacterId);
        if ($targetCharacter['Power_Stat'] < $sourceCharacter['Power_Stat']) {
            $bloodPotency = $sourceCharacter['Power_Stat'];
        }
    }

    // otherwise return raw power_stat
    return $bloodPotency;
}