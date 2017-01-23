<?php
use classes\character\helper\CharacterHelper;
use classes\core\helpers\Request;
use classes\core\helpers\Response;
use classes\core\helpers\UserdataHelper;
use classes\core\repository\Database;

/* @var array $userdata */

$character_name = Request::getValue('username');
if (strpos($character_name, '-- ') > 0) {
    $character_name = substr($character_name, 0, strpos($character_name, '-- '));
}
$character_query = "select * from characters where character_name = ?;";
$params = array(
    $character_name
);

$character_detail = Database::getInstance()->query($character_query)->single($character_detail);
if ($character_detail) {
    if (UserdataHelper::IsSt($userdata)) {
        // display character
        Response::redirect('/characters/stView/' . $character_detail['id']);
    } else {
        $profile_query = "select username from phpbb_users where user_id = ?";
		$params = array(
			$character_detail['user_id']
		);
        $profile_detail = Database::getInstance()->query($profile_query)->single($params);

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
	favors.source_id = ?
	AND favors.favor_type_id = 1
	AND favors.is_broken = 1
EOQ;

		$params = array(
			$character_detail['id']
		);

        $broken_favors = "";
		foreach(Database::getInstance()->query($broken_favors_query)->all($params) as $broken_favors_detail) {
            $broken_favors .= "Owed a favor to $broken_favors_detail[to_character_name] and broke it on $broken_favors_detail[date_broken].<br />";
        }

        if ($broken_favors == "") {
            $broken_favors = "None.";
        }

        $page_title = "View $character_detail[Character_Name]";
        $extra_public_effects = "";
        $age = $character_detail['Age'];
        switch ($character_detail['Character_Type']) {
            case 'Vampire':
                $blood_potency = CharacterHelper::DetermineBloodPotency($_GET['srcuid'], $character_detail);
                $extra_public_effects = "Blood Potency: $blood_potency, ";
				break;
            case 'Possessed':
            case 'Purified':
				$blood_potency = CharacterHelper::DetermineBloodPotency($_GET['srcuid'], $character_detail);
				$extra_public_effects = "Blood Potency: $blood_potency, ";
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
