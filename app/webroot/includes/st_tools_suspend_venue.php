<?php
$page_title = "Suspend/Unsuspend Venue";

if(isset($_POST['action']))
{
	// update venue
	$character_type = $_POST['character_type'];
	$is_suspended = $_POST['is_suspended'] + 0;
	$query = <<<EOQ
UPDATE
	characters
SET
	is_suspended = $is_suspended
WHERE
	character_type = '$character_type'
	AND City = 'Savannah'
EOQ;
	
	ExecuteNonQuery($query);
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
$rows = ExecuteQueryData($suspended_venues_query);

ob_start();
?>

<p>
<label>Currently Suspended Venues</label>
<?php foreach($rows as $row): ?>
<?php echo $row['Character_Type'] ?><br />
<?php endforeach; ?>
</p>

<form method="post">
	<label for="character_type">Suspend Venue</label>
	<input type="text" id="character_type" name="character_type" style="width:200px;" />
	<label>Suspend Venue</label>
	Yes: <input type="radio" value="1" name="is_suspended">
	No: <input type="radio" value="0" name="is_suspended" checked>
	<input type="submit" name="action" value="Submit" />
</form>

<?php
$page_content = ob_get_clean();
?>