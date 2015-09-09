<?php
use classes\core\helpers\Request;
use classes\core\repository\Database;

$page_title = "Suspend/Unsuspend Venue";

if (Request::IsPost()) {
    // update venue
    $character_type = Request::GetValue('character_type');
    $is_suspended = Request::GetValue('is_suspended');
    $query = <<<EOQ
UPDATE
	characters
SET
	is_suspended = ?
WHERE
	character_type = ?
	AND City = 'Savannah'
EOQ;
    $params = array(
        $is_suspended,
        $character_type
    );

    Database::GetInstance()->Query($query)->Execute($params);
}

$suspended_venues_query = <<<EOQ
SELECT
	DISTINCT
	Character_Type
FROM
	characters
WHERE
	is_suspended = 1
ORDER BY
	character_type
EOQ;
$rows = Database::GetInstance()->Query($suspended_venues_query)->All();

ob_start();
?>

    <p>
        <label>Currently Suspended Venues</label>
        <?php foreach ($rows as $row): ?>
            <?php echo $row['Character_Type'] ?><br/>
        <?php endforeach; ?>
    </p>

    <form method="post">
        <label for="character_type">Suspend Venue</label>
        <input type="text" id="character_type" name="character_type" style="width:200px;"/>
        <label>Suspend Venue</label>
        Yes: <input type="radio" value="1" name="is_suspended">
        No: <input type="radio" value="0" name="is_suspended" checked>
        <input type="submit" name="action" value="Submit"/>
    </form>

<?php
$page_content = ob_get_clean();
