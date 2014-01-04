<?php
use classes\core\repository\Database;

$contentHeader = $page_title = "Character Type Search";

// get posted information
$selected_character_types = isset($_POST['character_types']) ? $_POST['character_types'] : "";
$selected_cities = isset($_POST['cities']) ? $_POST['cities'] : "";
$selected_splat1 = isset($_POST['splat1']) ? $_POST['splat1'] : "";
$selected_splat2 = isset($_POST['splat2']) ? $_POST['splat2'] : "";
$selected_virtues = isset($_POST['virtues']) ? $_POST['virtues'] : "";
$selected_vices = isset($_POST['vices']) ? $_POST['vices'] : "";
$only_sanctioned = isset($_POST['only_sanctioned']) ? true : false;
$only_new = isset($_POST['only_new']) ? true : false;

$result_set = "";

// test if submitting anything to search for
if ($selected_character_types || $selected_cities || $selected_splat1 || $selected_splat2 || $selected_virtues || $selected_vices) {
    $character_query = "select * from characters where is_deleted='N' and ";

    if ($only_sanctioned) {
        $character_query .= " is_sanctioned='Y' and ";
    }

    if ($only_new) {
        $character_query .= " is_sanctioned='' AND first_login > DATE_SUB(NOW(), INTERVAL 1 MONTH) AND ";
    }

    if ($selected_character_types) {
        $character_query .= "( ";

        while (list($key, $value) = each($selected_character_types)) {
            $character_query .= " character_type = '$value' or ";
        }

        $character_query = substr($character_query, 0, strlen($character_query) - 3) . " ) and ";
    }

    if ($selected_cities) {
        $character_query .= "( ";

        while (list($key, $value) = each($selected_cities)) {
            $character_query .= " city = '$value' or ";
        }

        $character_query = substr($character_query, 0, strlen($character_query) - 3) . " ) and ";
    }

    if ($selected_splat1) {
        $character_query .= "( ";

        while (list($key, $value) = each($selected_splat1)) {
            $character_query .= " splat1 = '$value' or ";
        }

        $character_query = substr($character_query, 0, strlen($character_query) - 3) . " ) and ";
    }

    if ($selected_splat2) {
        $character_query .= "( ";

        while (list($key, $value) = each($selected_splat2)) {
            $character_query .= " splat2 = '$value' or ";
        }

        $character_query = substr($character_query, 0, strlen($character_query) - 3) . " ) and ";
    }

    if ($selected_virtues) {
        $character_query .= "( ";

        while (list($key, $value) = each($selected_virtues)) {
            $character_query .= " virtue = '$value' or ";
        }

        $character_query = substr($character_query, 0, strlen($character_query) - 3) . " ) and ";
    }

    if ($selected_vices) {
        $character_query .= "( ";

        while (list($key, $value) = each($selected_vices)) {
            $character_query .= " vice = '$value' or ";
        }

        $character_query = substr($character_query, 0, strlen($character_query) - 3) . " ) and ";
    }

    $character_query = substr($character_query, 0, strlen($character_query) - 5) . " Order by Is_NPC desc, City, Character_Type, Character_Name;";
    $characters = Database::GetInstance()->Query($character_query)->All();

    if (count($characters) > 0) {
        $num_of_chars = count($characters);

        $result_set = <<<EOQ
<br><br>
<table>
    <tr>
        <th colspan="9">
            Number of characters found: $num_of_chars
        </th>
    </tr>
    <tr>
        <th>
            Character Name
        </th>
        <th>
            NPC
        </th>
        <th>
            City
        </th>
        <th>
            Type
        </th>
        <th>
            Splat1
        </th>
        <th>
            Splat2
        </th>
        <th>
            Virtue
        </th>
        <th>
            Vice
        </th>
        <th>
        </th>
    </tr>
EOQ;

        foreach ($characters as $character_detail) {

            $result_set .= <<<EOQ
<tr>
    <td>
        $character_detail[Character_Name]
    </td>
    <td>
        $character_detail[Is_NPC]
    </td>
    <td>
        $character_detail[City]
    </td>
    <td>
        $character_detail[Character_Type]
    </td>
    <td>
        $character_detail[Splat1]
    </td>
    <td>
        $character_detail[Splat2]
    </td>
    <td>
        $character_detail[Virtue]
    </td>
    <td>
        $character_detail[Vice]
    </td>
    <td>
        <a href="view_sheet.php?action=st_view_xp&view_character_id=$character_detail[id]">View Character</a>
    </td>
</tr>
EOQ;
        }

        $result_set .= "</table>";
    }
    else {
        $result_set = "<br><br>No Characters matching that criteria.";
    }
}


// build form
$character_types = array("Mortal", "Ghoul", "Vampire", "Werewolf", "Mage", "Psychic", "Thaumaturge", "Promethean", "Changeling", "Hunter", "Geist");
$cities = array("Savannah", "San Diego", "The City", "Side Game");
$splat1 = array("Daeva", "Gangrel", "Mekhet", "Nosferatu", "Ventrue", "Rahu", "Cahalith", "Elodoth", "Ithaeur", "Irraka", "None", "Acanthus", "Mastigos", "Moros", "Obrimos", "Thyrsus", "Beast", "Darkling", "Elemental", "Fairest", "Ogre", "Wizened", "Academic", "Artist", "Athlete", "Cop", "Criminal", "Detective", "Doctor", "Engineer", "Hacker", "Hit man", "Journalist", "Laborer", "Occultist", "Professional", "Religious Leader", "Scientist", "Soldier", "Technician", "Vagrant");
$splat2 = array("Carthian", "Circle of the Crone", "Invictus", "Lancea Sanctum", "Ordo Dracul", "Unaligned", "Blood Talons", "Bone Shadows", "Hunters in Darkness", "Iron Masters", "Storm Lords", "Ghost Wolves", "Fire-Touched", "Ivory Claws", "Predator Kings", "The Adamantine Arrows", "Free Council", "Guardians of the Veil", "The Mysterium", "The Silver Ladder", "Apostate", "Seer of the Throne", "Banisher", "Spring", "Summer", "Autumn", "Winter", "Courtless");
$virtues = array("Charity", "Faith", "Fortitude", "Hope", "Justice", "Prudence", "Temperance");
$vices = array("Envy", "Gluttony", "Greed", "Lust", "Pride", "Sloth", "Wrath");

$character_type_select = buildMultiSelect($selected_character_types, $character_types, $character_types, "character_types[]", 3, true);
$city_select = buildMultiSelect($selected_cities, $cities, $cities, "cities[]", 3, true);
$splat1_select = buildMultiSelect($selected_splat1, $splat1, $splat1, "splat1[]", 6, true);
$splat2_select = buildMultiSelect($selected_splat2, $splat2, $splat2, "splat2[]", 6, true);
$virtue_select = buildMultiSelect($selected_virtues, $virtues, $virtues, "virtues[]", 3, true);
$vice_select = buildMultiSelect($selected_vices, $vices, $vices, "vices[]", 3, true);
$sanctioned_check = ($only_sanctioned) ? "checked" : "";
$new_check = ($only_new) ? "checked" : "";

$form = <<<EOQ
<form name="character_search" method="post" action="$_SERVER[PHP_SELF]?action=character_search">
    <table border="0" cellpadding="2" cellspacing="2" class="normal_text">
        <tr>
            <th colspan="4">
                Character Type Search
            </th>
        </tr>
        <tr>
            <td>
                Character Type:
            </td>
            <td>
                $character_type_select
            </td>
            <td>
                City:
            </td>
            <td>
                $city_select
            </td>
        </tr>
        <tr>
            <td>
                Splat 1<br>
                (Clan/Auspice/Path)
            </td>
            <td>
                $splat1_select
            </td>
            <td>
                Splat 2<br>
                (Covenant/Tribe/Order)
            </td>
            <td>
                $splat2_select
            </td>
        </tr>
        <tr>
            <td>
                Virtue:
            </td>
            <td>
                $virtue_select
            </td>
            <td>
                Vice:
            </td>
            <td>
                $vice_select
            </td>
        </tr>
        <tr>
            <td>
                Only Sanctioned
            </td>
            <td>
                <input type="checkbox" name="only_sanctioned" value="Y" $sanctioned_check>
            </td>
            <td>
            </td>
            <td>
            </td>
        </tr>
        <tr>
            <td>
                Only New
            </td>
            <td>
                <input type="checkbox" name="only_new" value="Y" $new_check>
            </td>
            <td>
            </td>
            <td>
            </td>
        </tr>
        <tr>
            <td colspan="4">
                <input type="submit" value="Find Characters">
                <input type="reset" value="Clear Form">
            </td>
        </tr>
    </table>
</form>
EOQ;

$page_content = $form . $result_set;

