<?php
ini_set('display_errors',1);
include 'cgi-bin/dbconnect.php';
include 'includes/database/mysql.php';

$showSplatToggle = false;
$characterType = '';
if($_GET['character_type'] != null) {
	$characterType = mysql_real_escape_string($_GET['character_type']);
	$showSplatToggle = true;
	$splat = (isset($_GET['splat'])) ? mysql_real_escape_string($_GET['splat']) : 'splat1';
	
	$query = <<<EOQ
SELECT
	character_type,
	$splat,
	count(*) as population,
	avg(intelligence) as `int`,
	avg(wits) as wits,
	avg(resolve) as res,
	avg(strength) as str,
	avg(dexterity) as dex,
	avg(stamina) as sta,
	avg(presence) as pres,
	avg(manipulation) as manip,
	avg(composure) as comp,
	avg(academics) as academics,
	avg(computer) as computer,
	avg(crafts) as crafts,
	avg(investigation) as investigation,
	avg(medicine) as medicine,
	avg(occult) as occult,
	avg(politics) as politics,
	avg(science) as science,
	avg(athletics) as athletics,
	avg(brawl) as brawl,
	avg(drive) as drive,
	avg(firearms) as firearms,
	avg(larceny) as larceny,
	avg(stealth) as stealth,
	avg(survival) as survival,
	avg(weaponry) as weaponry,
	avg(animal_ken) as animal_ken,
	avg(empathy) as empathy,
	avg(expression) as expression,
	avg(intimidation) as intimidation,
	avg(persuasion) as persuasion,
	avg(socialize) as socialize,
	avg(streetwise) as streetwise,
	avg(subterfuge) as subterfuge,
	avg(speed) as speed,
	avg(health) as health,
	avg(willpower_perm) as willpower_perm,
	avg(morality) as morality,
	avg(total_experience) as total_experience
FROM
	wod_characters
WHERE
	is_sanctioned = 'N'
	AND is_npc = 'N'
	AND first_login > '2010-08-01'
	AND character_type = '$characterType'
GROUP BY
	character_type,
	$splat
EOQ;
}
else {
$query = <<<EOQ
SELECT
	character_type,
	count(*) as population,
	avg(intelligence) as `int`,
	avg(wits) as wits,
	avg(resolve) as res,
	avg(strength) as str,
	avg(dexterity) as dex,
	avg(stamina) as sta,
	avg(presence) as pres,
	avg(manipulation) as manip,
	avg(composure) as comp,
	avg(academics) as academics,
	avg(computer) as computer,
	avg(crafts) as crafts,
	avg(investigation) as investigation,
	avg(medicine) as medicine,
	avg(occult) as occult,
	avg(politics) as politics,
	avg(science) as science,
	avg(athletics) as athletics,
	avg(brawl) as brawl,
	avg(drive) as drive,
	avg(firearms) as firearms,
	avg(larceny) as larceny,
	avg(stealth) as stealth,
	avg(survival) as survival,
	avg(weaponry) as weaponry,
	avg(animal_ken) as animal_ken,
	avg(empathy) as empathy,
	avg(expression) as expression,
	avg(intimidation) as intimidation,
	avg(persuasion) as persuasion,
	avg(socialize) as socialize,
	avg(streetwise) as streetwise,
	avg(subterfuge) as subterfuge,
	avg(speed) as speed,
	avg(health) as health,
	avg(willpower_perm) as willpower_perm,
	avg(morality) as morality,
	avg(total_experience) as total_experience
FROM
	wod_characters
WHERE
	is_sanctioned = 'N'
	AND is_npc = 'N'
	AND first_login > '2010-08-01'
GROUP BY
	character_type
EOQ;
}
$rows = ExecuteQueryData($query);
?>
<style>
* {
	font-family: arial, sans-serif;
}
table {
	border-collapse: collapse;
}
th {
	padding: 3px;
	background-color: #999;
	text-transform: uppercase;
}
td {
	padding: 3px;
}
tr:nth-child(even) {
	background-color: #ccd;
}

</style>

<?php if($showSplatToggle): ?>
<div style="text-align:center;">
	<a href="stat_report.php">Back</a> - 
	Group By: 
	<a href="stat_report.php?character_type=<?php echo $characterType; ?>&splat=splat1">Splat 1</a> - 
	<a href="stat_report.php?character_type=<?php echo $characterType; ?>&splat=splat2">Splat 2</a>
</div>
<?php endif; ?>
<table>
<?php foreach($rows as $i => $row): ?>
	<?php if($i == 0): ?>
	<tr>
		<?php foreach(array_keys($row) as $columnName): ?>
			<th>
				<?php echo $columnName; ?>
			</th>
		<?php endforeach; ?>
	</tr>
	<?php endif; ?>
	<tr>
		<?php foreach($row as $key => $value): ?>
		<td>
			<?php if($key == 'character_type'): ?>
				<a href="/app/webroot/stat_report.php?character_type=<?php echo $value; ?>"><?php echo $value; ?></a>
			<?php else: ?>
				<?php if(is_numeric($value)): ?>
					<?php echo round($value, 2); ?>
				<?php else: ?>
					<?php echo $value; ?>
				<?php endif; ?>
			<?php endif; ?>
		</td>
		<?php endforeach; ?>
	</tr>
<?php endforeach; ?>
</table>