<?php
/**
 * Created by JetBrains PhpStorm.
 * User: JeffVandenberg
 * Date: 11/16/13
 * Time: 7:00 AM
 * To change this template use File | Settings | File Templates.
 */

include("ini.php");
include("session.php");
include("config.php");
include("functions.php");

$dbh = db_connect();

$query = <<<EOQ
update prochatrooms_rooms AS R set roomusers = (
    SELECT
        count(*)
    FROM
        prochatrooms_users AS U
    WHERE
        U.room = R.id
        AND U.online = 1
)
EOQ;

$dbh->query($query)->execute();
?>
Cleaned rooms. They should be removed shortly.