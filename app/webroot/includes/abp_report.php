<?php
$page_title = "ABP Report";
$orderBy = "character_name";

if(isset($_GET['sort']))
{
	switch($_GET['sort'])
	{
		case 'abp':
			$orderBy = 'average_power_points';
			break;
	}	
}

$query = <<<EOQ
SELECT
	character_id,
	character_name,
	average_power_points
FROM
	characters
WHERE
	is_sanctioned = 'Y'
	AND is_deleted = 'N'
	AND is_npc = 'N'
	AND city = 'San Diego'
	AND character_type = 'Vampire'
ORDER BY
	$orderBy
EOQ;

$characters = ExecuteQueryData($query);
ob_start();
?>

<table style="border:none;" cellpadding="3">
	<tr>
		<th>
			<a href="?action=report&sort=name">Character</a>
		</th>
		<th>
			<a href="?action=report&sort=abp">ABP</a>
		</th>
		<th></th>
	</tr>
<?php foreach($characters as $character): ?>
	<tr>
		<td>
			<?php echo $character['character_name']; ?>
		</td>
		<td>
			<?php echo $character['average_power_points']; ?>
		</td>
		<td>
			<a href="view_sheet.php?action=st_view_xp&view_character_id=<?php echo $character['character_id']; ?>" target="_blank">View</a>
		</td>
	</tr>
<?php endforeach; ?>
</table>

<?php
$page_content = ob_get_contents();
ob_end_clean();