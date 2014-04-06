<?php
/* @var array $userdata */
// get character id
use classes\character\repository\CharacterRepository;
use classes\core\helpers\FormHelper;
use classes\core\helpers\MenuHelper;
use classes\core\helpers\Request;
use classes\core\helpers\Response;
use classes\core\helpers\SessionHelper;
use classes\core\helpers\UserdataHelper;
use classes\core\repository\Database;
use classes\core\repository\RepositoryManager;
use classes\log\CharacterLog;
use classes\log\data\ActionType;
use classes\request\repository\RequestRepository;

$characterId = Request::GetValue('character_id', 0);

$characterRepository = RepositoryManager::GetRepository('classes\character\data\Character');
/* @var CharacterRepository $characterRepository */
$character = $characterRepository->FindById($characterId);
if ($character === false) {
    Response::Redirect('');
}
/* @var Character $character */
if ($character['is_npc'] == 'Y') {
    if (!UserdataHelper::IsSt($userdata)) {
        CharacterLog::LogAction($characterId, ActionType::InvalidAccess, 'Attempted access to character interface',
                                $userdata['user_id']);
        SessionHelper::SetFlashMessage("You're not authorized to view that character.");
        Response::Redirect('');
    }
}
else {
    if ($character['user_id'] != $userdata['user_id']) {
        CharacterLog::LogAction($characterId, ActionType::InvalidAccess, 'Attempted access to character interface',
                                $userdata['user_id']);
        SessionHelper::SetFlashMessage("You're not authorized to view that character.");
        Response::Redirect('');
    }
}

$willpower_temp = $character['willpower_temp'];
$power_points = $character['power_points'];
$wounds_agg = $character['wounds_agg'];
$wounds_lethal = $character['wounds_lethal'];
$wounds_bashing = $character['wounds_bashing'];
$health = $character['health'];
$temporary_health_levels = $character['temporary_health_levels'];
$total_health = $temporary_health_levels;
$size = $character['size'];
$werewolf_form = "";
$current_form = Request::GetValue('current_form', "Hishu");
$updated_pp = $character['updated_pp'];

$max_power_points = getMaxPowerPoints($character['power_stat']);
if ($power_points > $max_power_points) {
    $power_points = $max_power_points;
}


// adjust health for werewolves
switch ($current_form) {
    case "Dalu":
        $health += 2;
        $size += 1;
        break;
    case "Gauru":
        $health += 4;
        $size += 2;
        break;
    case "Urshul":
        $health += 3;
        $size += 1;
        break;
    default:
        break;
}
$page_title = 'Die Roller/Status for: '.$character['character_name'];
$contentHeader = $page_title;

// test if doing an update or dice roll
if (isset($_POST['submit_die_roller'])) {
    // test to see if they are making a die roll they are making
    // they are attempting a roll, get all of the relevant details
    $character_name = htmlspecialchars($_POST['character_name']);
    $description    = htmlspecialchars($_POST['action']);
    $dice           = $_POST['dice'] + 0;
    $ten_again      = (($_POST['reroll'] == '10again') || ($_POST['reroll'] == '9again') || ($_POST['reroll'] == '8again'))
        ? "Y" : "N";
    $nine_again     = (($_POST['reroll'] == '9again') || ($_POST['reroll'] == '8again')) ? "Y" : "N";
    $eight_again    = ($_POST['reroll'] == '8again') ? "Y" : "N";
    $one_cancel     = (isset($_POST['1cancel'])) ? "Y" : "N";
    $chance_die     = (isset($_POST['chance_die'])) ? "Y" : "N";
    $is_rote        = (isset($_POST['is_rote'])) ? "Y" : "N";
    $used_wp        = (isset($_POST['spend_willpower'])) ? "Y" : "N";
    $used_pp        = (isset($_POST['spend_pp'])) ? "Y" : "N";

    // check for bias
    $bias = "normal";

    if (substr($description, 0, 1) == "+") {
        $description = substr($description, 1, strlen($description) - 1);
        $bias        = "high";
    }
    if (substr($description, 0, 1) == "-") {
        $description = substr($description, 1, strlen($description) - 1);
        $bias        = "low";
    }

    if (($used_wp == 'Y') && ($chance_die == 'N')) {
        $dice += 3;
    }

    if (($used_pp == 'Y') && ($chance_die == 'N')) {
        $dice += 2;
    }

    // validate
    $dice = ($dice < 0) ? -$dice : $dice;
    $dice = ($dice > 40) ? 40 : $dice;
    $dice = ($chance_die == 'Y') ? 1 : $dice;

    $character_name = (trim($character_name) == "") ? "Someone" : mysql_real_escape_string($character_name);
    $description    = (trim($description) == "") ? "does something sneaky" : mysql_real_escape_string($description);

    if ($dice) {
        $result = rollWoDDice($dice, $ten_again, $nine_again, $eight_again, $one_cancel, $chance_die, $bias,
                              $is_rote == 'Y');

        $now = date('Y-m-d h:i:s');
        //$bias = 'normal';
        $insert_query = "insert into wod_dierolls values (null, $characterId, '$now', '$character_name', '$description', $dice, '$ten_again', '$nine_again', '$eight_again', '$one_cancel', '$used_wp', '$used_pp', '$result[result]', '$result[note]', $result[num_of_successes], '$chance_die', '$bias', '$is_rote');";

        //echo $insert_query;
        $insert_result = mysql_query($insert_query) or die(mysql_error());
        $rollId = mysql_insert_id();

        // update relevant stats
        if (($used_wp == 'Y') || ($used_pp == 'Y')) {
            $update_query = "update characters set ";
            if ($used_wp == 'Y') {
                $update_query .= "willpower_temp = willpower_temp - 1, ";
                $willpower_temp--;
            }

            if ($used_pp == 'Y') {
                $update_query .= "power_points = power_points - 1, ";
                $power_points--;
            }

            $update_query = substr($update_query, 0, strlen($update_query) - 2);
            $update_query .= " where id = $characterId;";
            $update_result = mysql_query($update_query) or die(mysql_error());
        }

        // test if attaching to a request
        if ($_POST['request_id'] > 0) {
            $requestRepository = new RequestRepository();
            $requestRepository->AttachRollToRequest($_POST['request_id'], $rollId);
            $requestRepository->TouchRecord($_POST['request_id'], $userdata['user_id']);
        }
    }
    Response::Redirect('dieroller.php?action=character&character_id='.$characterId);
}

if (isset($_POST['submit_update_stats'])) {
    $wounds_agg = $_POST['wounds_agg'] + 0;
    $wounds_agg = ($wounds_agg < 0) ? 0 : $wounds_agg;

    $wounds_lethal = $_POST['wounds_lethal'] + 0;
    $wounds_lethal = ($wounds_lethal < 0) ? 0 : $wounds_lethal;

    $wounds_bashing = $_POST['wounds_bashing'] + 0;
    $wounds_bashing = ($wounds_bashing < 0) ? 0 : $wounds_bashing;

    $power_points = (isset($_POST['power_points'])) ? $_POST['power_points'] + 0 : $power_points;
    $power_points = ($power_points < 0) ? 0 : $power_points;

    $temporary_health_levels = $_POST['temporary_health_levels'] + 0;
    $health                  = $health + $temporary_health_levels;

    if ($power_points > $max_power_points) {
        $power_points = $max_power_points;
    }

    $willpower_temp = (isset($_POST['extra_spend_willpower'])) ? --$willpower_temp : $willpower_temp;

    $update_query = <<<EOQ
UPDATE
	characters
set 
	wounds_agg = $wounds_agg, 
	wounds_lethal = $wounds_lethal, 
	wounds_bashing = $wounds_bashing, 
	power_points = $power_points, 
	willpower_temp = $willpower_temp,
	temporary_health_levels = $temporary_health_levels
where 
	id = $characterId;
EOQ;
    $update_result = Database::GetInstance()->Query($update_query)->Execute();
    Response::Redirect('dieroller.php?action=character&character_id='.$characterId.'&current_form='.$current_form);
}

$extra_row = "";
$extra_spend_willpower = "";
if ($willpower_temp > 0) {
    $extra_row .= <<<EOQ
Spend Willpower on Roll: <input type="checkbox" name="spend_willpower" value="y"> &nbsp;&nbsp;
EOQ;

    // for extraneous willpower expenditures
    $extra_spend_willpower = <<<EOQ
Spend Willpower: <input type="checkbox" name="extra_spend_willpower" value="y"><br> 
EOQ;
}

// test if they are a vamp for blood spending
if (($character['character_type'] == 'Vampire') && ($power_points > 0)) {
    $extra_row .= <<<EOQ
Spend Blood: <input type="checkbox" name="spend_pp" value="y">
EOQ;
}

// show extra status areas
$extra_status = "";
if ($character['character_type'] == 'Vampire') {
    $extra_status = <<<EOQ
Blood: <input type="text" name="power_points" value="$power_points" size="3" maxlength="2">
EOQ;
}
if ($character['character_type'] == 'Ghoul') {
    $extra_status = <<<EOQ
Blood: <input type="text" name="power_points" value="$power_points" size="3" maxlength="2">
EOQ;
}
if ($character['character_type'] == 'Mage') {
    $extra_status = <<<EOQ
Mana: <input type="text" name="power_points" value="$power_points" size="3" maxlength="2">
EOQ;
}
if ($character['character_type'] == 'Werewolf') {
    $extra_status  = <<<EOQ
Essence: <input type="text" name="power_points" value="$power_points" size="3" maxlength="2">
EOQ;
    $forms         = array("Hishu", "Dalu", "Gauru", "Urshul", "Urhan");
    $form_select   = buildSelect($current_form, $forms, $forms, "current_form");
    $werewolf_form = <<<EOQ
Form: $form_select<br>
EOQ;
}
if ($character['character_type'] == 'Changeling') {
    $extra_status = <<<EOQ
Glamour: <input type="text" name="power_points" value="$power_points" size="3" maxlength="2">
EOQ;
}
if ($character['character_type'] == 'Geist') {
    $extra_status = <<<EOQ
Plasm: <input type="text" name="power_points" value="$power_points" size="3" maxlength="2">
EOQ;
}

// calculate Status
$status = "Still Standing<br>";
if ($wounds_agg >= $health) {
    $status       = "Dead (Game over man! Game Over!)<br>";
    $found_status = true;
}

if (!$found_status && (($wounds_agg + $wounds_lethal) >= $health)) {
    if ($character['character_type'] == 'Vampire') {
        $status = "On the ground in Torpor(No Actions)<br>";
    }
    else {
        $status = "On the ground bleeding (No Actions)<br>";
    }
    $found_status = true;
}

if (!$found_status && (($wounds_agg + $wounds_lethal + $wounds_bashing) >= $health)) {
    if ($character['character_type'] == 'Vampire') {
        $status = "Gravely wounded<br>";
    }
    else {
        $status = "Ready to Pass out (Stamina Rolls Necessary)<br>";
    }

    $found_status = true;
}

// make wound representation
$wound_representation = "";
for ($i = 1; $i <= $health; $i++) {
    if ($i <= $wounds_agg) {
        $wound_representation .= "<img src=\"img/wound_agg.gif\" width=\"13\" height=\"13\">";
    }
    else {
        if ($i <= ($wounds_agg + $wounds_lethal)) {
            $wound_representation .= "<img src=\"img/wound_lethal.gif\" width=\"13\" height=\"13\">";
        }
        else {
            if ($i <= ($wounds_agg + $wounds_lethal + $wounds_bashing)) {
                $wound_representation .= "<img src=\"img/wound_bashing.gif\" width=\"13\" height=\"13\">";
            }
            else {
                $wound_representation .= "<img src=\"img/wound_empty.gif\" width=\"13\" height=\"13\">";
            }
        }
    }
}

// calculate wound penalty
$wounds = ($health - 3) - $wounds_agg - $wounds_lethal - $wounds_bashing;

$wounds = ($wounds > 0) ? 0 : $wounds;
$wounds = ($wounds < -3) ? -3 : $wounds;

if ($wounds) {
    $status .= "($wounds penalty)";
}

$page_size = 20;
$page = (isset($_POST['page'])) ? $_POST['page'] + 0 : 1;
$page = (isset($_GET['page'])) ? $_GET['page'] + 0 : $page;
if ($page < 1) {
    $page = 1;
}

$count_query = <<<EOQ
SELECT
	COUNT(*) AS count
FROM
	wod_dierolls
EOQ;
$count_data = ExecuteQueryItem($count_query);

$count = $count_data['count'];
$pages = round($count / $page_size, 0);
if ($pages == 0) {
    $pages = 1;
}

if ($page > $pages) {
    $page = $pages;
}

$current_row = ($page - 1) * $page_size;
$showNext = false;
$showPrev = false;

if ($page != $pages) {
    $showNext = true;
}
if ($page != 1) {
    $showPrev = true;
}

// get past rolls
$roll_query = <<<EOQ
SELECT
	*
FROM
	wod_dierolls 
ORDER BY
	roll_id DESC
LIMIT 
	$current_row, $page_size;
EOQ;
$roll_result = mysql_query($roll_query) or die(mysql_error());

$rolls = <<<EOQ
<table border="0" class="normal_text" width="100%" cellspacing="0" cellpadding="4">
EOQ;

$i = 0;
while ($roll_detail = mysql_fetch_array($roll_result, MYSQL_ASSOC)) {
    $row_color    = (($i++) % 2) ? "#443a33" : "#000000";
    $wp           = "";
    $pp           = "";
    $chance       = "";
    $eight_again  = "";
    $nine_again   = "";
    $no_ten_again = "";
    $rote_action  = "";
    $ones_remove  = "";

    if ($roll_detail['Used_WP'] == 'Y') {
        $wp = "(WP)";
    }

    if ($roll_detail['Used_PP'] == 'Y') {
        $pp = "(BP)";
    }

    if ($roll_detail['Chance_Die'] == 'Y') {
        $chance = "(Chance Die)";
    }

    if ($roll_detail['1_Cancel'] == 'Y') {
        $ones_remove = "(1's Remove)";
    }

    if ($roll_detail['Is_Rote'] == 'Y') {
        $rote_action = "(Rote Action)";
    }

    if (($roll_detail['8_Again'] == 'Y') && ($roll_detail['9_Again'] == 'Y') && ($roll_detail['10_Again'] == 'Y')) {
        $eight_again = "(8-Again)";
    }

    if (($roll_detail['8_Again'] == 'N') && ($roll_detail['9_Again'] == 'Y') && ($roll_detail['10_Again'] == 'Y')) {
        $nine_again = "(9-Again)";
    }

    if (($roll_detail['8_Again'] == 'N') && ($roll_detail['9_Again'] == 'N') && ($roll_detail['10_Again'] == 'N')) {
        $no_ten_again = "(No 10-Again)";
    }

    $rolls .= <<<EOQ
	<tr valign="top">
		<td width="10%">
			<a href="/dieroller.php?action=view_roll&r=$roll_detail[Roll_ID]" target="_blank">Details</a>
		</td>
		<td width="35%">
			$roll_detail[Character_Name] $roll_detail[Description] <br> 
			Dice: $roll_detail[Dice] $wp $pp $chance $eight_again $nine_again $no_ten_again $ones_remove $rote_action<br>
			Time: $roll_detail[Roll_Date]
		</td>
		<td width="20%"> 
			Successes: $roll_detail[Num_of_Successes]<br> Result: $roll_detail[Note] <br>
		</td>
		<td width="35%">
			$roll_detail[Result]
		</td>
	</tr>
EOQ;
}

$rolls .= "</table>";

$requestRepository = new RequestRepository();
$openRequests = $requestRepository->ListOpenRequestsForCharacter($characterId);

$requests = array('0' => 'None');
foreach ($openRequests as $request) {
    $requests[$request['id']] = $request['title'];
}

// BETA die roller drop downs
//$options = array("None", "---ATTRIBUTES:", "Intelligence", "Wits", "Resolve", "Strength", "Dexterity", "Stamina", "Presence", "Manipulation", "Composure", "---SKILLS:", "Academics", "Animal Ken", "Athletics", "Brawl", "Computer", "Crafts", "Drive", "Empathy", "Expression", "Firearms", "Intimidation", "Investigation", "Larceny", "Medicine", "Occult", "Persuasion", "Politics", "Science", "Socialize", "Stealth", "Streetwise", "Subterfuge", "Survival", "Weaponry", "---OTHER:", "Power Stat");
/*$dropdown1 = buildMultiSelect("", $options, $options, "dieroll_part_1", 7, true);
$dropdown2 = buildMultiSelect("", $options, $options, "dieroll_part_2", 7, true);
$dropdown3 = buildSelect("", $options, $options, "dieroll_part_3");*/
/*************************************************************************************
 * POST handling
 *************************************************************************************/

/*************************************************************************************
 * Build View
 *************************************************************************************/

/*************************************************************************************
 * The View Content
 *************************************************************************************/

require_once('helpers/character_menu.php');
/* @var array $characterMenu */
$menu = MenuHelper::GenerateMenu($characterMenu);
ob_start();
?>
<?php echo $menu; ?>
    <table>
        <tr style="vertical-align: top;">
            <td style="width: 60%">
                <form method="post"
                      action="<?php echo $_SERVER['PHP_SELF']; ?>?action=character&character_id=<?php echo $characterId; ?>">
                    Name: <input type="text" name="character_name" size="20" maxlength="35"
                                 value="<?php echo $character['character_name']; ?>">
                    Action: <input type="text" name="action" size="20" maxlength="50" value="">
                    Dice: <input type="text" name="dice" size="3" maxlength="2" value=""><br>
                    10-Again: <input type="radio" name="reroll" value="10again" checked> &nbsp;-&nbsp;
                    9-Again: <input type="radio" name="reroll" value="9again"> &nbsp;-&nbsp;
                    8-Again: <input type="radio" name="reroll" value="8again"> &nbsp;-&nbsp;
                    No Rerolls: <input type="radio" name="reroll" value="none"><br/>
                    Chance Die: <input type="checkbox" name="chance_die" value="y">
                    Rote Action: <input type="checkbox" name="is_rote" value="y"><br>
                    <?php echo $extra_row; ?><br>
                    Attach to Roll: <?php echo FormHelper::Select($requests, 'request_id', ''); ?><br/>
                    <input type="hidden" name="current_form" value="<?php echo $current_form; ?>">
                    <input id="submit-die-roller" type="submit" name="submit_die_roller" value="Roll Dice/Refresh">
                </form>
            </td>
            <td style="text-align: center;width: 40%">
                <form method="post"
                      action="<?php echo $_SERVER['PHP_SELF']; ?>?action=character&character_id=<?php echo $characterId; ?>&log_npc=<?php echo $log_npc; ?>">
                    <table style="border: 2px groove #223344;">
                        <tr style="vertical-align: top;">
                            <td width="45%">
                                Health: <?php echo $health; ?><br>
                                Temp. Health: <input type="text" name="temporary_health_levels"
                                                     value="<?php echo $temporary_health_levels; ?>"
                                                     style="width:40px;"/><br/>
                                <br>
                                Wounds: <?php echo $wound_representation; ?><br>
                                - Agg: <input type="text" name="wounds_agg" value="<?php echo $wounds_agg; ?>" size="3"
                                              maxlength="2"><br>
                                - Lethal: <input type="text" name="wounds_lethal" value="<?php echo $wounds_lethal; ?>"
                                                 size="3" maxlength="2"><br>
                                - Bashing: <input type="text" name="wounds_bashing"
                                                  value="<?php echo $wounds_bashing; ?>" size="3" maxlength="2"><br>
                                <br>
                                <input type="submit" name="submit_update_stats" value="Update Stats">
                            </td>
                            <td width="10%">
                            </td>
                            <td width="45%">
                                Status: <?php echo $status; ?><br>
                                <?php echo $werewolf_form; ?>
                                <br>
                                Willpower:<br>
                                - Permanent: <?php echo $character['willpower_perm']; ?><br>
                                - Current: <?php echo $willpower_temp; ?><br>
                                <?php echo $extra_spend_willpower; ?>
                                <br>
                                <?php echo $extra_status; ?>
                            </td>
                        </tr>
                    </table>
                </form>
            </td>
        </tr>
    </table>
    <div style="text-align: center;">
        <h2>Past Rolls</h2>
    </div>
    <br>
    <div>
        <form method="get" action="dieroller.php">
            Page <input type="text" value="<?php echo $page; ?>" name="page" style="width:30px;"/>
            of <?php echo $pages; ?>
            <input type="hidden" name="action" value="character"/>
            <input type="hidden" name="character_id" value="<?php echo $characterId; ?>"/>
            <input type="hidden" name="log_npc" value="$log_npc"/>
            <input type="submit" value="Go to Page"/>
            <?php if ($showPrev): ?>
                <a href="dieroller.php?action=character&character_id=<?php echo $characterId; ?>&log_npc=<?php echo $log_npc; ?>&page=<?php echo($page - 1); ?>">
                    &lt; &lt; Prev</a>
            <?php endif; ?>
            <?php if ($showNext): ?>
                <a href="dieroller.php?action=character&character_id=<?php echo $characterId; ?>&log_npc=<?php echo $log_npc; ?>&page=<?php echo($page + 1); ?>">Next
                    &gt; &gt;</a>
            <?php endif; ?>
        </form>

        <?php echo $rolls; ?>

        <form method="get" action="dieroller.php">
            Page <input type="text" value="<?php echo $page; ?>" name="page" style="width:30px;"/>
            of <?php echo $pages; ?>
            <input type="hidden" name="action" value="character"/>
            <input type="hidden" name="character_id" value="<?php echo $characterId; ?>"/>
            <input type="hidden" name="log_npc" value="$log_npc"/>
            <input type="submit" value="Go to Page"/>
            <?php if ($showPrev): ?>
                <a href="dieroller.php?action=character&character_id=<?php echo $characterId; ?>&log_npc=<?php echo $log_npc; ?>&page=<?php echo($page - 1); ?>">
                    &lt; &lt; Prev</a>
            <?php endif; ?>
            <?php if ($showNext): ?>
                <a href="dieroller.php?action=character&character_id=<?php echo $characterId; ?>&log_npc=<?php echo $log_npc; ?>&page=<?php echo($page + 1); ?>">Next
                    &gt; &gt;</a>
            <?php endif; ?>
        </form>
    </div>
    <script>
        $(function () {
            var submitted = true;
            $('form').submit(function () {
                if (submitted) {
                    submitted = true;
                    return true;
                }
                else {
                    return false;
                }
            })
        });
    </script>
<?php
$page_content = ob_get_clean();
