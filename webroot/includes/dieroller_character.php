<?php
/* @var array $userdata */
// get character id
use classes\character\helper\CharacterHelper;
use classes\character\repository\CharacterRepository;
use classes\core\helpers\FormHelper;
use classes\core\helpers\MenuHelper;
use classes\core\helpers\Request;
use classes\core\helpers\Response;
use classes\core\helpers\SessionHelper;
use classes\core\helpers\UserdataHelper;
use classes\core\repository\Database;
use classes\core\repository\RepositoryManager;
use classes\dice\repository\DiceRepository;
use classes\dice\WodDice;
use classes\log\CharacterLog;
use classes\log\data\ActionType;
use classes\request\repository\RequestRepository;
use classes\character\data\Character;

$characterId = Request::getValue('character_id', 0);

$characterRepository = RepositoryManager::GetRepository('classes\character\data\Character');
/* @var CharacterRepository $characterRepository */
$diceRepository = RepositoryManager::GetRepository('classes\dice\data\Dice');
/* @var DiceRepository $diceRepository */

$character = $characterRepository->FindById($characterId);
if ($character === false) {
    Response::redirect('/');
}
/* @var Character $character */
if ($character['is_npc'] == 'Y') {
    if (!UserdataHelper::IsSt($userdata)) {
        CharacterLog::LogAction($characterId, ActionType::INVALID_ACCESS, 'Attempted access to character interface',
            $userdata['user_id']);
        SessionHelper::SetFlashMessage("You're not authorized to view that character.");
        Response::redirect('/');
    }
} else {
    if (($character['user_id'] != $userdata['user_id']) && !UserdataHelper::IsAdmin($userdata)) {
        CharacterLog::LogAction($characterId, ActionType::INVALID_ACCESS, 'Attempted access to character interface',
            $userdata['user_id']);
        SessionHelper::SetFlashMessage("You're not authorized to view that character.");
        Response::redirect('/');
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
$current_form = Request::getValue('current_form', SessionHelper::Read('current_form', "Hishu"));
$showOnlyMyRolls = Request::getValue('show_only_my_rolls', false);

SessionHelper::Write('current_form', $current_form);

$max_power_points = CharacterHelper::getMaxPowerPoints($character['power_stat']);
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
$page_title = 'Die Roller/Status for: ' . $character['character_name'];
$contentHeader = $page_title;

// test if doing an update or dice roll
if (isset($_POST['submit_die_roller'])) {
    // test to see if they are making a die roll they are making
    // they are attempting a roll, get all of the relevant details
    $character_name = htmlspecialchars($_POST['character_name']);
    $description = htmlspecialchars($_POST['action']);
    $dice = $_POST['dice'] + 0;
    $ten_again = (($_POST['reroll'] == '10again') || ($_POST['reroll'] == '9again') || ($_POST['reroll'] == '8again'))
        ? "Y" : "N";
    $nine_again = (($_POST['reroll'] == '9again') || ($_POST['reroll'] == '8again')) ? "Y" : "N";
    $eight_again = ($_POST['reroll'] == '8again') ? "Y" : "N";
    $one_cancel = (isset($_POST['1cancel'])) ? "Y" : "N";
    $chance_die = (isset($_POST['chance_die'])) ? "Y" : "N";
    $is_rote = (isset($_POST['is_rote'])) ? "Y" : "N";
    $used_wp = (isset($_POST['spend_willpower'])) ? "Y" : "N";
    $used_pp = (isset($_POST['spend_pp'])) ? "Y" : "N";
    $rollType = Request::getValue('roll_type');
    $extendedWillpower = Request::getValue('extended_willpower');
    $numberOfRolls = Request::getValue('number_of_rolls', 1);

    // check for bias
    $bias = "normal";

    if (substr($description, 0, 1) == "+") {
        $description = substr($description, 1, strlen($description) - 1);
        $bias = "high";
    }
    if (substr($description, 0, 1) == "-") {
        $description = substr($description, 1, strlen($description) - 1);
        $bias = "low";
    }

    // validation and preprocessing
    $willpowerSpent = 0;
    if ($rollType == 0) {
        // normal roll
        $numberOfRolls = 1;
        if ($used_wp == 'Y') {
            $willpowerSpent = 1;
        }
    } else {
        // extended roll
        $willpowerSpent = $extendedWillpower;
        if ($numberOfRolls > 20) {
            $numberOfRolls = 20;
        }
    }

    $now = date('Y-m-d h:i:s');
    $description = (trim($description) == "") ? "does something sneaky" : $description;

    for ($i = 0; $i < $numberOfRolls; $i++) {
        $diceForRoll = $dice;
        $usedWillpowerForRoll = $i < $willpowerSpent;

        if (($usedWillpowerForRoll) && ($chance_die == 'N')) {
            $diceForRoll += 3;
        }
        if (($used_pp == 'Y') && ($chance_die == 'N')) {
            $diceForRoll += 2;
        }

        // validate
        $diceForRoll = ($diceForRoll < 0) ? -$diceForRoll : $diceForRoll;
        $diceForRoll = ($diceForRoll > 40) ? 40 : $diceForRoll;
        $diceForRoll = ($chance_die == 'Y') ? 1 : $diceForRoll;

        $character_name = (trim($character_name) == "") ? "Someone" : $character_name;
        $rollDescription = $description;

        if ($rollType == 1) {
            $rollDescription .= ' Roll #' . ($i + 1);
        }

        if ($diceForRoll) {
            $wodDice = new WodDice();
            $result = $wodDice->rollWoDDice($diceForRoll, $ten_again, $nine_again, $eight_again, $one_cancel, $chance_die, $bias,
                $is_rote == 'Y');

            $query = "
INSERT INTO wod_dierolls (Character_ID, Roll_Date, Character_Name, Description, Dice, 10_Again, 9_Again, 8_Again,
1_Cancel, Used_WP, Used_PP, Result, Note, Num_of_Successes, Chance_Die, Bias, Is_Rote)
VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?);
";
            $params = array(
                $characterId, $now, $character_name, $rollDescription, $diceForRoll, $ten_again, $nine_again,
                $eight_again, $one_cancel, ($usedWillpowerForRoll) ? 'Y' : 'N', $used_pp, $result['result'],
                $result['note'], $result['num_of_successes'], $chance_die, $bias, $is_rote
            );

            Database::getInstance()->query($query)->execute($params);
            $rollId = Database::getInstance()->getInsertId();

            // update relevant stats
            if (($usedWillpowerForRoll) || ($used_pp == 'Y')) {
                $update_query = "UPDATE characters SET ";
                if ($usedWillpowerForRoll) {
                    $update_query .= "willpower_temp = willpower_temp - 1, ";
                    $willpower_temp--;
                }

                if ($used_pp == 'Y') {
                    $update_query .= "power_points = power_points - 1, ";
                    $power_points--;
                }

                $update_query = substr($update_query, 0, strlen($update_query) - 2);
                $update_query .= " where id = ?;";
                $params = array($characterId);
                Database::getInstance()->query($update_query)->execute($params);
            }

            // test if attaching to a request
            if (Request::getValue('request_id')) {
                $requestRepository = new RequestRepository();
                $requestRepository->AttachRollToRequest(Request::getValue('request_id'), $rollId);
                $requestRepository->TouchRecord(Request::getValue('request_id'), $userdata['user_id']);
            }
        }
    }

    Response::redirect('dieroller.php?action=character&character_id=' . $characterId);
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
    $health = $health + $temporary_health_levels;

    if ($power_points > $max_power_points) {
        $power_points = $max_power_points;
    }

    $willpower_temp = (isset($_POST['extra_spend_willpower'])) ? --$willpower_temp : $willpower_temp;

    $params = array(
        $wounds_agg,
        $wounds_lethal,
        $wounds_bashing,
        $power_points,
        $willpower_temp,
        $temporary_health_levels,
        $characterId
    );
    $update_query = <<<EOQ
UPDATE
	characters
set 
	wounds_agg = ?,
	wounds_lethal = ?,
	wounds_bashing = ?,
	power_points = ?,
	willpower_temp = ?,
	temporary_health_levels = ?
where 
	id = ?
EOQ;
    $update_result = Database::getInstance()->query($update_query)->execute($params);

    Response::redirect('dieroller.php?action=character&character_id=' . $characterId);
}

$extra_row = "";
$extra_spend_willpower = "";
if ($willpower_temp > 0) {
    $extra_row .= <<<EOQ
Spend Willpower on Roll:
    <input type="checkbox" name="spend_willpower" id="spend-willpower" value="y" />
    <input type="text" name="extended_willpower" id="extended-willpower" size="2" value="0" style="display:none;" />
    &nbsp;&nbsp;
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
    $extra_status = <<<EOQ
Essence: <input type="text" name="power_points" value="$power_points" size="3" maxlength="2">
EOQ;
    $forms = [
        "Hishu" => "Hishu",
        "Dalu" => "Dalu",
        "Gauru" => "Gauru",
        "Urshul" => "Urshul",
        "Urhan" => "Urhan",
    ];
    $form_select = FormHelper::Select($forms, 'current_form', $current_form);
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
    $status = "Dead (Game over man! Game Over!)<br>";
    $found_status = true;
}

if (!$found_status && (($wounds_agg + $wounds_lethal) >= $health)) {
    if ($character['character_type'] == 'Vampire') {
        $status = "On the ground in Torpor(No Actions)<br>";
    } else {
        $status = "On the ground bleeding (No Actions)<br>";
    }
    $found_status = true;
}

if (!$found_status && (($wounds_agg + $wounds_lethal + $wounds_bashing) >= $health)) {
    if ($character['character_type'] == 'Vampire') {
        $status = "Gravely wounded<br>";
    } else {
        $status = "Ready to Pass out (Stamina Rolls Necessary)<br>";
    }

    $found_status = true;
}

// make wound representation
$wound_representation = "";
for ($i = 1; $i <= $health; $i++) {
    if ($i <= $wounds_agg) {
        $wound_representation .= '<img src="img/wound_agg.gif" width="13" height="13">';
    } else {
        if ($i <= ($wounds_agg + $wounds_lethal)) {
            $wound_representation .= '<img src="img/wound_lethal.gif" width="13" height="13">';
        } else {
            if ($i <= ($wounds_agg + $wounds_lethal + $wounds_bashing)) {
                $wound_representation .= '<img src="img/wound_bashing.gif" width="13" height="13">';
            } else {
                $wound_representation .= '<img src="img/wound_empty.gif" width="13" height="13">';
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
$page = Request::getValue('page', 1);
if ($page < 1) {
    $page = 1;
}

$count_data = $diceRepository->findCountOfRolls($showOnlyMyRolls, $characterId);

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
$rollData = $diceRepository->loadRolls($showOnlyMyRolls, $characterId, $page, $page_size);

$rolls = <<<EOQ
<table border="0" class="normal_text" width="100%" cellspacing="0" cellpadding="4">
EOQ;

foreach ($rollData as $i => $roll_detail) {
    $row_color = ($i % 2) ? "#443a33" : "#000000";
    $wp = "";
    $pp = "";
    $chance = "";
    $eight_again = "";
    $nine_again = "";
    $no_ten_again = "";
    $rote_action = "";
    $ones_remove = "";

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
foreach ($openRequests as $r) {
    $requests[$r['id']] = $r['title'];
}

/*************************************************************************************
 * POST handling
 *************************************************************************************/

/*************************************************************************************
 * Build View
 *************************************************************************************/

/*************************************************************************************
 * The View Content
 *************************************************************************************/

require_once('menus/character_menu.php');
/* @var array $characterMenu */
$menu = MenuHelper::generateMenu($characterMenu);
$rollTypes = array(
    0 => 'Simple Roll',
    1 => 'Extended Roll'
);
$rerolls = array(
    '10again' => '10 Again',
    '9again' => '9 Again',
    '8again' => '8 Again',
    'none' => 'No Rerolls'
);
ob_start();
?>
    <style xmlns:border="http://www.w3.org/1999/xhtml">
        .diceroller label {
            display: inline;
        }
    </style>
<?php echo $menu; ?>
    <table class="diceroller">
        <tr style="vertical-align: top;">
            <td style="width: 60%; text-align: center;">
                <form method="post"
                      action="<?php echo $_SERVER['PHP_SELF']; ?>?action=character&character_id=<?php echo $characterId; ?>">
                    <div class="row">
                        <div class="small-12 medium-6 columns">
                            <?php echo FormHelper::Select($rollTypes, 'roll_type', 0, array(
                                'label' => 'Roll Type'
                            )); ?>
                        </div>
                        <div class="small-12 medium-6 columns" id="number-of-rolls-cell">
                            <?php echo FormHelper::Text('number_of_rolls', 1, array(
                                'label' => true,
                                'size' => 2
                            )); ?>
                        </div>
                        <div class="small-12 medium-5 columns">
                            <?php echo FormHelper::Text('character_name', $character['character_name'], array(
                                'size' => 20,
                                'maxlength' => 35,
                                'label' => 'Name'
                            )); ?>
                        </div>
                        <div class="small-12 medium-5 columns">
                            <label>
                                Action
                                <input type="text" name="action" size="20" maxlength="50" value=""/>
                            </label>
                        </div>
                        <div class="small-12 medium-2 columns">
                            <label>
                                Dice
                                <input type="text" name="dice" size="3" maxlength="2" value=""/>
                            </label>
                        </div>
                        <div class="small-12 medium-6 columns">
                            <?php echo FormHelper::Select($rerolls, 'reroll', '10again', array(
                                'label' => 'Reroll',
                            )); ?>
                        </div>
                        <div class="small-12 medium-6 columns">
                            <label>
                                Chance
                                <input type="checkbox" name="chance_die" value="y">
                            </label>
                            <label>
                                Rote
                                <input type="checkbox" name="is_rote" value="y">
                            </label>
                        </div>
                        <?php if ($extra_row): ?>
                            <div><?php echo $extra_row; ?></div>
                        <?php endif; ?>
                        <div class="small-12 columns">
                            Attach to Request: <?php echo FormHelper::Select($requests, 'request_id', Request::getValue('request_id')); ?>
                        </div>
                    </div>
                    <input type="hidden" name="current_form" value="<?php echo $current_form; ?>">
                    <button id="submit-die-roller" type="submit" name="submit_die_roller" class="button">Roll Dice/Refresh</button>
                </form>
            </td>
            <td style="text-align: center;width: 40%">
                <form method="post"
                      action="<?php echo $_SERVER['PHP_SELF']; ?>?action=character&character_id=<?php echo $characterId; ?>">
                    <table style="border: 2px groove #223344;">
                        <tr style="vertical-align: top;">
                            <td width="45%">
                                Health: <?php echo $health; ?><br>
                                Temp. Health: <input type="text" name="temporary_health_levels"
                                                     value="<?php echo $temporary_health_levels; ?>"
                                                     style="display:inline;width:40px;"/><br/>
                                <br>
                                Wounds: <?php echo $wound_representation; ?><br>
                                Agg: <input type="text" name="wounds_agg" value="<?php echo $wounds_agg; ?>" size="3"
                                              maxlength="2" style="display:inline;width:40px;"><br>
                                Lethal: <input type="text" name="wounds_lethal" value="<?php echo $wounds_lethal; ?>"
                                                 size="3" maxlength="2" style="display:inline;width:40px;"><br>
                                Bashing: <input type="text" name="wounds_bashing" value="<?php echo $wounds_bashing; ?>"
                                                size="3" maxlength="2" style="display:inline;width:40px;"><br>
                                <button type="submit" name="submit_update_stats" class="button">Update Stats</button>
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
    <div id="dice-roll-display">
        <form method="get" action="dieroller.php" id="dice-page-form">
            Page <input type="text" value="<?php echo $page; ?>" name="page" style="width:30px;display:inline;"/>
            of <?php echo $pages; ?>
            <input type="hidden" name="action" value="character"/>
            <input type="hidden" name="character_id" value="<?php echo $characterId; ?>"/>
            <?php echo FormHelper::Hidden('show_only_my_rolls', $showOnlyMyRolls); ?>
            <input type="submit" value="Go to Page"/>
            <?php if ($showPrev): ?>
                <a href="dieroller.php?action=character&show_only_my_rolls=<?php echo $showOnlyMyRolls; ?>&character_id=<?php echo $characterId; ?>&page=<?php echo($page - 1); ?>">
                    &lt; &lt; Prev</a>
            <?php endif; ?>
            <?php if ($showNext): ?>
                <a href="dieroller.php?action=character&show_only_my_rolls=<?php echo $showOnlyMyRolls; ?>&character_id=<?php echo $characterId; ?>&page=<?php echo($page + 1); ?>">Next
                    &gt; &gt;</a>
            <?php endif; ?>
            <div class="checkbox" style="display:inline;">
                <?php echo FormHelper::Checkbox('show_only_my_rolls', 1, $showOnlyMyRolls, array(
                    'label' => 'Only Show My Rolls',
                    'id' => 'show-only-my-rolls-check'
                )); ?>
            </div>
        </form>

        <?php echo $rolls; ?>
    </div>
    <script>
        $(function () {
            var submitted = false;
            $('form').submit(function () {
                if (!submitted) {
                    var extendedWillpower = $("#extended-willpower").val();
                    var numberOfRolls = $('#number-of-rolls').val();

                    if (!isNaN(parseInt(extendedWillpower)) && (!isNaN(parseInt(numberOfRolls)))) {
                        if (parseInt(extendedWillpower) > parseInt(numberOfRolls)) {
                            $("#extended-willpower").val(numberOfRolls)
                        }
                    }
                    submitted = true;
                    return true;
                }
                else {
                    return false;
                }
            });
            $("#roll-type").change(function () {
                if ($(this).val() == '0') {
                    // simple roll hide extended roll willpower
                    $("#extended-willpower").hide();
                    $("#spend-willpower").show().attr('disabled', false);
                    $("#number-of-rolls-cell").hide();
                }
                else {
                    // show extended roll willpower
                    $("#extended-willpower").show();
                    $("#spend-willpower").hide().attr('disabled', true);
                    $("#number-of-rolls-cell").css('display', 'inline');
                }
            });
            $("#show-only-my-rolls-check").click(function () {
                $("#dice-page-form").submit();
            });
        });
    </script>
<?php
$page_content = ob_get_clean();
