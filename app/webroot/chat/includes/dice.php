<?php
/**
 * Created by PhpStorm.
 * User: jvandenberg
 * Date: 11/27/13
 * Time: 3:30 PM
 */

require_once("ini.php");
require_once("session.php");
require_once("config.php");
require_once("functions.php");
require_once('../../cgi-bin/rollWoDDice.php');
require_once('../../cgi-bin/SysRand.php');


if (!isset($_SESSION['user_id'])) {
    die();
}

$response = array(
    'status' => false,
    'message' => 'Unknown action'
);

switch ($_POST['action']) {
    case 'roll':
        $command = preg_replace('/\s+/', ' ', html_entity_decode($_POST['command']));
        $command = trim($command);

        $matches = array();
        $count = preg_match('/^"[^".]+"/', $command, $matches);
        if ($count == 0) {
            $response['message'] = 'The format for the command is /dice roll "my action" <dice> [WP] [Blood]';
            break;
        }
        $action = $matches[0];
        $command = trim(str_replace($action, '', $command));
        $action = trim($action, '"');
        $command = strtolower($command);

        $spaceIndex = strpos($command, ' ');
        if ($spaceIndex === false) {
            $dice = $command;
            $command = "";
        }
        else {
            $dice = substr($command, 0, $spaceIndex);
            $command = substr($command, $spaceIndex);
        }

        if ((int)$dice === 0) {
            $response['message'] = 'Text dice are not supported.. yet.';
            break;
        }

        if ($dice < 0) {
            $dice = -$dice;
        }

        if ($dice > 30) {
            $dice = 30;
        }

        $spendWP = (strpos($command, 'wp') !== false);
        $spendPP = (strpos($command, 'blood') !== false);

        if ($spendWP) {
            $dice += 3;
        }

        if ($spendPP) {
            $dice += 2;
        }

        $ten_again = (strpos($command, 'no10again') !== false) ? 'N' : 'Y';
        $nine_again = (strpos($command, '9again') !== false) ? 'Y' : 'N';
        $eight_again = (strpos($command, '8again') !== false) ? 'Y' : 'N';
        $one_cancel = (strpos($command, '1cancel') !== false) ? 'Y' : 'N';
        $chance_die = (strpos($command, 'chance') !== false) ? 'Y' : 'N';
        $isRote = (strpos($command, 'rote') !== false) ? true : false;

        $result = rollWoDDice($dice, $ten_again, $nine_again, $eight_again, $one_cancel, $chance_die, $bias, $isRote);

        $now = date('Y-m-d H:i:s');
        //$bias = 'normal';
        $characterId = ($_SESSION['user_type_id'] == 3) ? $_SESSION['userid'] : '0';

        $sql = <<<EOQ
INSERT INTO
    wod_dierolls
    (
        character_id,
        roll_date,
        character_name,
        description,
        dice,
        10_again,
        9_again,
        8_again,
        1_cancel,
        used_wp,
        used_pp,
        result,
        note,
        num_of_successes,
        chance_die,
        bias,
        is_rote
    )
VALUES
    (
        :characterId,
        :rollDate,
        :characterName,
        :description,
        :dice,
        :tenAgain,
        :nineAgain,
        :eightAgain,
        :oneCancel,
        :usedWP,
        :usedPP,
        :result,
        :note,
        :successes,
        :chance,
        :bias,
        :rote
    )
EOQ;

        $params = array(
            'characterId' => $characterId,
            'rollDate' => $now,
            'characterName' => htmlspecialchars($_SESSION['display_name']),
            'description' => htmlspecialchars($action),
            'dice' => $dice,
            'tenAgain' => $ten_again,
            'nineAgain' => $nine_again,
            'eightAgain' => $eight_again,
            'oneCancel' => $one_cancel,
            'usedWP' => ($spendWP) ? 'Y' : 'N',
            'usedPP' => ($spendPP) ? 'Y' : 'N',
            'result' => $result['result'],
            'note' => $result['note'],
            'successes' => $result['num_of_successes'],
            'chance' => $chance_die,
            'bias' => 'normal',
            'rote' => ($isRote) ? 'Y' : 'N'
        );

        $dbh = db_connect();
        $query = $dbh->prepare($sql);
        if ($query->execute($params)) {
            $rollId = $dbh->lastInsertId();
            $successText = ($result['num_of_successes'] == 1) ? 'success' : 'successes';
            $response = array(
                'status' => true,
                'message' => ' attempted ' . $action . ' with ' . $dice . ' dice and got ' . $result['num_of_successes'] . ' ' . $successText . '. ' .
                '<a href="/dieroller.php?action=view_roll&r=' . $rollId . '" target="_blank" class="chat-viewable">View Roll</a>'
            );
            if ($spendWP && ($characterId > 0)) {
                $sql = "update characters set willpower_temp = willpower_temp -1 where id = ?";
                $dbh->prepare($sql)->execute(array($characterId));
            }
            if ($spendPP && ($characterId > 0)) {
                $sql = "update characters set power_points = power_points - 1 where id = ?";
                $dbh->prepare($sql)->execute(array($characterId));
            }
        }
        break;
    case 'initiative':
        $command = preg_replace('/\s+/', ' ', html_entity_decode($_POST['command']));
        $command = strtolower(trim($command));
        $command = trim(str_replace(array('initiative', 'init'), '', $command));

        $mod = 0;
        if($command != '') {
            $mod = (int) number_format($command);
        }

        if ($_SESSION['user_type_id'] == 3) {
            // load character for modifier
            $sql = "select initiative_mod from characters where id = ?";
            $dbh = db_connect();
            $query = $dbh->prepare($sql);
            $query->execute(array($_SESSION['userid']));
            $row = $query->fetch();
            $mod += $row['initiative_mod'];
        }

        $result = rollWoDDice('1', 'N', 'N', 'N', 'N', 'N', 'normal', false);

        $sql = <<<EOQ
INSERT INTO
    wod_dierolls
    (
        character_id,
        roll_date,
        character_name,
        description,
        dice,
        10_again,
        9_again,
        8_again,
        1_cancel,
        used_wp,
        used_pp,
        result,
        note,
        num_of_successes,
        chance_die,
        bias,
        is_rote
    )
VALUES
    (
        :characterId,
        :rollDate,
        :characterName,
        :description,
        :dice,
        :tenAgain,
        :nineAgain,
        :eightAgain,
        :oneCancel,
        :usedWP,
        :usedPP,
        :result,
        :note,
        :successes,
        :chance,
        :bias,
        :rote
    )
EOQ;

        $characterId = ($_SESSION['user_type_id'] == 3) ? $_SESSION['userid'] : '0';
        $now = date('Y-m-d H:i:s');
        $params = array(
            'characterId' => $characterId,
            'rollDate' => $now,
            'characterName' => htmlspecialchars($_SESSION['display_name']),
            'description' => 'Initiative + ' . $mod,
            'dice' => 1,
            'tenAgain' => 'N',
            'nineAgain' => 'N',
            'eightAgain' => 'N',
            'oneCancel' => 'N',
            'usedWP' => 'N',
            'usedPP' => 'N',
            'result' => $result['result'],
            'note' => $result['note'],
            'successes' => $result['num_of_successes'],
            'chance' => 'N',
            'bias' => 'normal',
            'rote' => 'N'
        );

        $dbh = db_connect();
        $query = $dbh->prepare($sql);
        if ($query->execute($params)) {
            $rollId = $dbh->lastInsertId();
            $response = array(
                'status' => true,
                'message' => ' rolled Initiative +' .$mod.' and got ' . ($result['result'] + $mod) . '. ' .
                '<a href="/dieroller.php?action=view_roll&r=' . $rollId . '" target="_blank" class="chat-viewable">View Roll</a>'
            );
        }
        break;
}

header('content-type: application/json');
echo json_encode($response);