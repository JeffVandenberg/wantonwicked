<?php
use classes\core\helpers\MenuHelper;
use classes\core\helpers\Request;

$page_title = "Update/View Character";

// page variables
$show_form = true;
$error = "";

// save
if (isset($_POST['submit'])) {
    // test to make sure that a person's viewing their own
    $characterId = $_POST['character_id'] + 0;
    $character_query = <<<EOQ
SELECT 
	wod.*
FROM 
	wod_characters AS wod 
	INNER JOIN login_character_index AS lci ON wod.character_id = lci.character_id
 WHERE lci.login_id = $userdata[user_id]
   AND wod.character_id = $characterId;
EOQ;

    $character_result = mysql_query($character_query) or die(mysql_error());

    if (mysql_num_rows($character_result)) {
        $character_detail = mysql_fetch_array($character_result, MYSQL_ASSOC);

        if (($character_detail['Asst_Sanctioned'] != '') || ($character_detail['Is_Sanctioned'] != '') || ($character_detail['Head_Sanctioned'] != '')) {
            $edit_show_sheet = true;
            $edit_name = false;
            $edit_vitals = false;
            $edit_is_npc = false;
            $edit_is_dead = false;
            $edit_location = false;
            $edit_concept = false;
            $edit_description = true;
            $edit_url = true;
            $edit_equipment = false;
            $edit_public_effects = false;
            $edit_group = false;
            $edit_exit_line = true;
            $edit_attributes = false;
            $edit_skills = false;
            $edit_perm_traits = false;
            $edit_temp_traits = true;
            $edit_powers = false;
            $edit_history = false;
            $edit_goals = true;
            $edit_login_note = false;
            $edit_experience = false;
            $show_st_notes = false;
            $view_is_asst = false;
            $view_is_st = false;
            $view_is_head = false;
            $view_is_admin = false;
            $may_edit = true;
            $edit_cell = false;
        } else {
            $edit_show_sheet = true;
            $edit_name = true;
            $edit_vitals = true;
            $edit_is_npc = true;
            $edit_is_dead = true;
            $edit_location = true;
            $edit_concept = true;
            $edit_description = true;
            $edit_url = true;
            $edit_equipment = true;
            $edit_public_effects = true;
            $edit_group = true;
            $edit_exit_line = true;
            $edit_attributes = true;
            $edit_skills = true;
            $edit_perm_traits = true;
            $edit_temp_traits = true;
            $edit_powers = true;
            $edit_history = true;
            $edit_goals = true;
            $edit_login_note = false;
            $edit_experience = false;
            $show_st_notes = false;
            $view_is_asst = false;
            $view_is_st = false;
            $view_is_head = false;
            $view_is_admin = false;
            $may_edit = true;
            $edit_cell = true;
        }

        $error = updateWoDSheetXP($_POST, $edit_show_sheet, $edit_name, $edit_vitals, $edit_is_npc, $edit_is_dead, $edit_location, $edit_concept, $edit_description, $edit_url, $edit_equipment, $edit_public_effects, $edit_group, $edit_exit_line, $edit_attributes, $edit_skills, $edit_perm_traits, $edit_temp_traits, $edit_powers, $edit_history, $edit_goals, $edit_login_note, $edit_experience, $show_st_notes, $view_is_asst, $view_is_st, $view_is_head, $view_is_admin, $may_edit, $edit_cell);

        if ($error != "") {
            $page_content = $error;
            $show_form = false;
        }
    } else {
        $show_form = false;
        $page_content = "You may only view your own sheets.";
    }
}

// page content
if (!$show_form) {
    die();
}

$characterId = Request::GetValue('character_id', 0);

// get minimal character details;
$character_query = <<<EOQ
SELECT
 head_sanctioned, 
 is_sanctioned, 
 asst_sanctioned, 
 is_npc 
FROM 
  wod_characters w
  INNER JOIN login_character_index lci
  ON w.character_id = lci.character_id
where 
  w.character_id = $characterId
  AND lci.login_id = $userdata[user_id]
EOQ;

$character_result = mysql_query($character_query) or die(mysql_error());
$character_detail = mysql_fetch_array($character_result, MYSQL_ASSOC) or die(mysql_error());

$edit_xp = "false";
if ($character_detail['head_sanctioned'] == ''
    && $character_detail['is_sanctioned'] == ''
    && $character_detail['asst_sanctioned'] == ''
) {
    $edit_xp = "true";
}

$java_script .= <<<EOQ
<script src="js/xmlHTTP.js" type="text/javascript"></script>
<script src="js/create_character_xp.js" type="text/javascript"></script>
<script>
    $(function() {
        loadCharacter($characterId, $edit_xp);
    });
</script>
EOQ;

require_once('helpers/character_menu.php');
/* @var array $characterMenu */
$characterMenu['Help'] = array(
    'link' => '#',
    'submenu' => array(
        'Character Creation' => array(
            'link' => '/wiki/?CharacterCreation',
            'target' => '_blank'
        ),
        'ORB List' => array(
            'link' => '/wiki/?ORBList',
            'target' => '_blank'
        ),
        'Goals &amp; Beliefs' => array(
            'link' => '/wiki/?GoalsAndBeliefs',
            'target' => '_blank'
        )
    )
);
$menu = MenuHelper::GenerateMenu($characterMenu);
ob_start();
?>

<?php echo $menu; ?>
    <form name="character_sheet" id="character_sheet" method="post"
          action="<?php echo $_SERVER['PHP_SELF']; ?>?action=view_own_xp">
        <div align="center" name="charSheet" id="charSheet">Loading Character Sheet...
        </div>
    </form>
<?php
$page_content = ob_get_clean();
