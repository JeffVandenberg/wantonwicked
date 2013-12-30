<?php
/**
 * Created by PhpStorm.
 * User: jvandenberg
 * Date: 11/26/13
 * Time: 12:22 PM
 */

require_once('config.php');
require_once('session.php');
require_once('db.php');
require_once('functions.php');

if($_POST['action'] == 'add') {
    // add user room
    $roomName = $_POST['roomName'];
    $roomPass = $_POST['roomPass'];
    $roomId = 0;
    $success = false;
    $message = 'Unknown error';

    if ($roomName != '') {
        // password encryption
        if ($roomPass != '') {
            $roomPass = md5($roomPass);
        }

        // check room exists
        $count = 0;
        try {
            $dbh = db_connect();
            $params = array(
                'newRoomName' => makeSafe($roomName)
            );

            $query = "SELECT id
					  FROM prochatrooms_rooms
					  WHERE roomname = :newRoomName
					  LIMIT 1
					  ";
            $action = $dbh->prepare($query);
            $action->execute($params);

            while($row = $action->fetch(PDO::FETCH_ASSOC)) {
                $roomId = $row['id'];
            }
        }
        catch (PDOException $e) {
            $error = "Action: Check Room Exists\n";
            $error .= "File: " . basename(__FILE__) . "\n";
            $error .= 'PDOException: ' . $e->getCode() . '-' . $e->getMessage() . "\n\n";

            debugError($error);
        }

        if ($roomId == 0) {
            // if room doesnt exist
            /*if (validChars($_POST['newRoomName'])) {
                die("invalid room name");
            }*/

            // create room
            try {
                $dbh = db_connect();
                $roomId = getTime();

                $params = array(
                    'id' => $roomId,
                    'roomname' => makeSafe($roomName),
                    'roomowner' => $_SESSION['user_id'],
                    'roompassword' => makeSafe($roomPass),
                    'roomusers' => '0',
                    'roomcreated' => $roomId,
                );

                $query = "INSERT INTO prochatrooms_rooms
                                    (
                                        id,
                                        roomname,
                                        roomowner,
                                        roompassword,
                                        roomusers,
                                        roomcreated,
                                        room_type_id,
                                        is_active
                                    )
                                    VALUES
                                    (
                                        :id,
                                        :roomname,
                                        :roomowner,
                                        :roompassword,
                                        :roomusers,
                                        :roomcreated,
                                        1,
                                        1
                                    )
                                    ";
                $action = $dbh->prepare($query);
                $action->execute($params);
                $dbh = null;
                $success = true;
            } catch (PDOException $e) {
                $error = "Action: Add New Room\n";
                $error .= "File: " . basename(__FILE__) . "\n";
                $error .= 'PDOException: ' . $e->getCode() . '-' . $e->getMessage() . "\n\n";

                debugError($error);
            }

        }
        else {
            try {
                $dbh = db_connect();
                $params = array(
                    'roomcreated' => getTime(),
                    'id' => $roomId
                );
                $query = "UPDATE prochatrooms_rooms
						  SET roomcreated = :roomcreated
						  WHERE id = :id
						  ";
                $action = $dbh->prepare($query);
                $action->execute($params);
                $dbh = null;
                $success = true;
            } catch (PDOException $e) {
                $error = "Action: Update Room\n";
                $error .= "File: " . basename(__FILE__) . "\n";
                $error .= 'PDOException: ' . $e->getCode() . '-' . $e->getMessage() . "\n\n";

                debugError($error);
            }
        }
    }
    header('content-type:application/json');
    echo json_encode(array('status' => $success, 'message' => $message, 'roomId' => $roomId));
}