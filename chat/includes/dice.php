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

if(!isset($_SESSION['user_id'])) {
    die();
}

$response = array(
    'status' => false,
    'message' => 'Unknown action'
);

switch($_POST['action']) {
    case 'roll':
        $command = preg_replace('/\s+/', ' ', html_entity_decode($_POST['command']));

        $matches = array();
        $count = preg_match('"[\w\s]+"', $command, $matches);
        if($count == 0) {
            $response['message'] = 'The format for the command is /nick "my action" <dice> [WP] [Blood]';
            break;
        }
        $action = $matches[0];
        $command = trim(str_replace('"'.$action.'"', '', $command));

        $spaceIndex = strpos($command, ' ');
        if($spaceIndex === false) {
            $dice = $command;
            $command = "";
        }
        else {
            $dice = substr($command, 0, $spaceIndex);
            $command = substr($command, $spaceIndex);
        }

        if((int)$dice === 0) {
            $response['message'] = 'Text dice are not supported.. yet.';
            break;
        }

        $spendWP = (strpos($command, 'WP') !== false);
        $spendPP = (strpos($command, 'Blood') !== false);

        if($spendWP) {
            $dice += 3;
        }

        if($spendPP) {
            $dice +=2;
        }

        $ten_again = 'Y';
        $nine_again = 'N';
        $eight_again = 'N';
        $one_cancel = 'N';
        $chance_die = 'N';
        $isRote = false;

        require_once('../../cgi-bin/rollWoDDice.php');

        $result = rollWoDDice($dice, $ten_again, $nine_again, $eight_again, $one_cancel, $chance_die, $bias, $is_rote);

        $now = date('Y-m-d h:i:s');
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
        if($query->execute($params)) {
            $response = array(
                'status' => true,
                'message' => ' ' . $action . ' got ' . $result['num_of_successes'] . ' successes.'
            );
            if($spendWP && ($characterId > 0)) {
                $sql = "update wod_characters set willpower_temp = willpower_temp -1 where character_id = ?";
                $dbh->prepare($sql)->execute(array($characterId));
            }
            if($spendPP && ($characterId > 0)) {
                $sql = "update wod_characters set power_points = power_points - 1 where character_id = ?";
                $dbh->prepare($sql)->execute(array($characterId));
            }
        }
        break;
}

header('content-type: application/json');
echo json_encode($response);