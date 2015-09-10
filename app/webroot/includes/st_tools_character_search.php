<?php
use classes\core\helpers\FormHelper;
use classes\core\helpers\MenuHelper;
use classes\core\helpers\Request;
use classes\core\repository\Database;
use classes\utility\ArrayTools;

$contentHeader = $page_title = "Character Type Search";

// get posted information
$selected_character_types = Request::getValue('character_types', array());
$selected_cities = Request::getValue('cities', array());
$selected_splat1 = Request::getValue('splat1', array());
$selected_splat2 = Request::getValue('splat2', array());
$selected_virtues = Request::getValue('virtues', array());
$selected_vices = Request::getValue('vices', array());
$only_sanctioned = Request::getValue('only_sanctioned', false);

// test if submitting anything to search for
if (count($selected_cities) || count($selected_splat1) || count($selected_splat2) || Request::isPost()) {
    $character_query = "select * from characters where is_deleted='N' and ";

    if ($only_sanctioned) {
        $character_query .= " is_sanctioned='Y' and ";
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

        foreach($selected_virtues as $key => $value) {
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
    $characters = Database::getInstance()->query($character_query)->all();
}


// build form
$character_types = array("Mortal", "Ghoul", "Vampire", "Werewolf", "Wolfblooded", "Mage", "Sleepwalker", "Psychic", "Thaumaturge", "Promethean", "Changeling", "Hunter", "Geist");
$characterTypes = ArrayTools::array_valuekeys($character_types);
sort($character_types);
$cities = array("Savannah", "San Diego", "The City", "Side Game");
$cities = ArrayTools::array_valuekeys($cities);

$splat1 = array(
    'None',
    'Vampire' => array(
        "Daeva", "Gangrel", "Mekhet", "Nosferatu", "Ventrue",
    ),
    'Werewolf' => array(
        "Rahu", "Cahalith", "Elodoth", "Ithaeur", "Irraka",
    ),
    'Mage' => array(
        "Acanthus", "Mastigos", "Moros", "Obrimos", "Thyrsus",
    ),
    'Changeling' => array(
        "Beast", "Darkling", "Elemental", "Fairest", "Ogre", "Wizened",
    ),
    'Hunter' => array(
        "Academic", "Artist", "Athlete", "Cop", "Criminal", "Detective", "Doctor", "Engineer", "Hacker", "Hit man", "Journalist", "Laborer", "Occultist", "Professional", "Religious Leader", "Scientist", "Soldier", "Technician", "Vagrant"
    )
);
$splat1 = ArrayTools::array_valuekeys($splat1);
$splat2 = array(
    'Vampire' => array(
        "Carthian", "Circle of the Crone", "Invictus", "Lancea Sanctum", "Ordo Dracul", "Unaligned",
    ),
    'Werewolf' => array(
        "Blood Talons", "Bone Shadows", "Hunters in Darkness", "Iron Masters", "Storm Lords", "Ghost Wolves", "Fire-Touched", "Ivory Claws", "Predator Kings",
    ),
    'Mage' => array(
        "The Adamantine Arrows", "Free Council", "Guardians of the Veil", "The Mysterium", "The Silver Ladder", "Apostate", "Seer of the Throne", "Banisher",
    ),
    'Changeling' => array(
        "Spring", "Summer", "Autumn", "Winter", "Courtless"
    )
);
$splat2 = ArrayTools::array_valuekeys($splat2);
$virtues = ArrayTools::array_valuekeys(array("Charity", "Faith", "Fortitude", "Hope", "Justice", "Prudence", "Temperance"));
$vices = ArrayTools::array_valuekeys(array("Envy", "Gluttony", "Greed", "Lust", "Pride", "Sloth", "Wrath"));

$storytellerMenu = require_once('menus/storyteller_menu.php');
$menu = MenuHelper::GenerateMenu($storytellerMenu);

ob_start();
?>

<?php echo $menu; ?>
<form name="character_search" method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>?action=character_search">
    <table>
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
                <?php echo FormHelper::Multiselect($characterTypes, 'character_types[]', $selected_character_types); ?>
            </td>
            <td>
                City:
            </td>
            <td>
                <?php echo FormHelper::Multiselect($cities, 'cities[]', $selected_cities); ?>
            </td>
        </tr>
        <tr>
            <td>
                Splat 1<br>
                (Clan/Auspice/Path)
            </td>
            <td>
                <?php echo FormHelper::Multiselect($splat1, 'splat1[]', $selected_splat1, array('size' => 10)); ?>
            </td>
            <td>
                Splat 2<br>
                (Covenant/Tribe/Order)
            </td>
            <td>
                <?php echo FormHelper::Multiselect($splat2, 'splat2[]', $selected_splat2, array('size' => 10)); ?>
            </td>
        </tr>
        <tr>
            <td>
                Virtue:
            </td>
            <td>
                <?php echo FormHelper::Multiselect($virtues, 'virtues[]', $selected_virtues); ?>
            </td>
            <td>
                Vice:
            </td>
            <td>
                <?php echo FormHelper::Multiselect($vices, 'vices[]', $selected_vices); ?>
            </td>
        </tr>
        <tr>
            <td>
                Only Sanctioned
            </td>
            <td>
                <?php echo FormHelper::Checkbox('only_sanctioned', 1, $only_sanctioned); ?>
            </td>
            <td>
            </td>
            <td>
            </td>
        </tr>
        <tr>
            <td colspan="4">
                <input type="submit" value="Find Characters">
            </td>
        </tr>
    </table>
</form>
<?php if(isset($characters)): ?>
    <?php if (count($characters) > 0): ?>
<br><br>
<table>
    <tr>
        <th colspan="9">
            Number of characters found: <?php echo count($characters); ?>
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
    <?php foreach ($characters as $character_detail): ?>
    <tr>
        <td>
            <?php echo $character_detail['character_name']; ?>
        </td>
        <td>
            <?php echo $character_detail['is_npc']; ?>
        </td>
        <td>
            <?php echo $character_detail['city']; ?>
        </td>
        <td>
            <?php echo $character_detail['character_type']; ?>
        </td>
        <td>
            <?php echo $character_detail['splat1']; ?>
        </td>
        <td>
            <?php echo $character_detail['splat2']; ?>
        </td>
        <td>
            <?php echo $character_detail['virtue']; ?>
        </td>
        <td>
            <?php echo $character_detail['vice']; ?>
        </td>
        <td>
            <a href="view_sheet.php?action=st_view_xp&view_character_id=<?php echo $character_detail['id']; ?>" target="">View</a>
        </td>
    </tr>
    <?php endforeach; ?>
    </table>
    <?php else: ?>
        <br><br>No Characters matching that criteria.
    <?php endif; ?>
<?php endif; ?>
<?php
$page_content = ob_get_clean();

