<?php
$page_title = 'Power and Merit Search';

$power_type = htmlspecialchars($_POST['power_type']);
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
	power_type,
	power_name,
	power_note,
	power_level
FROM
	characters AS C
	LEFT JOIN character_powers AS CP ON C.character_id = CP.characterId
WHERE
	C.is_sanctioned = 'Y'
	AND C.is_deleted = 'N'
	AND CP.power_type = '$power_type'
	AND CP.power_name LIKE '%$powerName%'
	AND CP.power_note LIKE '%$powerNote%'
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

$power_types = array("Merit", "ICDisc", "OOCDisc", "Devotion", "Derangement");
$power_typeNames = array("Merit", "In-Clan Discipline", "Out-of-Clan Disc.", "Devotion/Ritual/Misc.", "Derangement");

$power_typeSelect = buildSelect($power_type, $power_types, $power_typeNames, "power_type");

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
		$power_typeSelect
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