<?php
$page_title = 'Power and Merit Search';

$powerType = htmlspecialchars($_POST['powerType']);
$powerName = htmlspecialchars($_POST['powerName']);
$powerNote = htmlspecialchars($_POST['powerNote']);

$searchResults = "";
if($_POST['action'])
{
	$sql = <<<EOQ
SELECT
	character_name,
	character_type,
	C.character_id,
	powerType AS power_type,
	powerName AS power_name,
	powerNote AS power_note,
	PowerLevel AS power_level
FROM
	wod_characters AS C
	LEFT JOIN wod_characters_powers AS CP ON C.character_id = CP.characterId
WHERE
	C.is_sanctioned = 'Y'
	AND C.is_deleted = 'N'
	AND C.cell_id = '$userdata[cell_id]'
	AND CP.powerType = '$powerType'
	AND CP.powerName LIKE '%$powerName%'
	AND CP.powerNote LIKE '%$powerNote%'
ORDER BY
	character_type,
	character_name
EOQ;
	$rows = ExecuteQueryData($sql);
	
	$i = 0;
	
	$searchResults = <<<EOQ
<h1>Search Results</h1>
<div class="tableRowHeader" style="width:746px;">
	<div class="tableRowHeaderCell firstCell cell" style="width:180px;">
		Character Name
	</div>
	<div class="tableRowHeaderCell cell" style="width:100px;">
		Character Type
	</div>
	<div class="tableRowHeaderCell cell" style="width:150px;">
		Power Name
	</div>
	<div class="tableRowHeaderCell cell" style="width:200px;">
		Power Note
	</div>
	<div class="tableRowHeaderCell cell" style="width:40px;">
		Level
	</div>
	<div class="tableRowHeaderCell cell" style="width:40px;">
		&nbsp;
	</div>
</div>
EOQ;

	foreach($rows as $row)
	{
		$rowAlt = (($i++)%2) ? "Alt" : "";
		
		$searchResults .= <<<EOQ
<div class="tableRow$rowAlt" style="width:746px;">
	<div class="firstCell cell" style="width:180px;">
		$row[character_name]
	</div>
	<div class="cell" style="width:100px;">
		$row[character_type]
	</div>
	<div class="cell" style="width:150px;">
		$row[power_name]
	</div>
	<div class="cell" style="width:200px;">
		$row[power_note]
		&nbsp;
	</div>
	<div class="cell" style="width:40px;">
		$row[power_level]
	</div>
	<div class="cell" style="width:40px;">
		<a href="/view_sheet.php?action=st_view_xp&view_character_id=$row[character_id]">View</a>
	</div>
</div>
EOQ;

	}
}

$powerTypes = array("Merit", "ICDisc", "OOCDisc", "Devotion", "Derangement");
$powerTypeNames = array("Merit", "In-Clan Discipline", "Out-of-Clan Disc.", "Devotion/Ritual/Misc.", "Derangement");

$powerTypeSelect = buildSelect($powerType, $powerTypes, $powerTypeNames, "powerType");

$page_content = <<<EOQ
<h1>Power &amp; Merit Search</h1>
<form method="post" action="/st_tools.php?action=power_search">
<div class="tableRowHeader" style="width:574px;">
	<div class="tableRowHeaderCell firstCell cell" style="width:150px;">
		Power Type
	</div>
	<div class="tableRowHeaderCell cell" style="width:150px;">
		Type
	</div>
	<div class="tableRowHeaderCell cell" style="width:150px;">
		Name
	</div>
	<div class="tableRowHeaderCell cell" style="width:100px;">
		&nbsp;
	</div>
</div>
<div class="tableRow" style="clear:both;width:574px;">
	<div class="firstCell cell" style="width:150px;height:26px;">
		$powerTypeSelect
	</div>
	<div class="cell" style="width:150px;">
		<input type="text" value="$powerName" name="powerName" style="width:95%;" />
	</div>
	<div class="cell" style="width:150px;">
		<input type="text" value="$powerNote" name="powerNote" style="width:95%;" />
	</div>
	<div class="cell" style="width:100px;">
		<input type="submit" value="Search" name="action" />
	</div>
</div>
</form>
$searchResults
EOQ;
?>