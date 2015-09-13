<?php
include_once 'includes/classes/tenacy/domain.php';

$domain = new Domain();
$reportData = $domain->GetCharactersInDomains();

$page_title = "Character Domain Report";

$page_content = <<<EOQ
<div class="paragraph">
	<a href="/abp.php">Back to ABP Home</a>
</div>
<div class="tableRowHeader" style="width:458px;">
	<div class="tableRowHeaderCell firstCell cell" style="width:190px;">
		Character Name
	</div>
	<div class="tableRowHeaderCell cell" style="width:150px;">
		Domain
	</div>
	<div class="tableRowHeaderCell cell" style="width:100px;">
		&nbsp;
	</div>
</div>
EOQ;

$row = 0;
$previousCharacter = "";
foreach($reportData as $item)
{
	if($item['character_name'] != $previousCharacter)
	{
		$characterName = $item['character_name'];
		$previousCharacter = $item['character_name'];
	}
	else
	{
		$characterName = '&nbsp;';
		$row++;
	}
	$rowAlt = (($row++)%2) ? "Alt" : "";
	
	$page_content .= <<<EOQ
<div class="tableRow$rowAlt" style="clear:both;width:458px;" id="territoryRow$territoryDetail[id]">
	<div class="firstCell cell" style="width:190px;">
		$characterName
	</div>
	<div class="cell" style="width:150px;">
		$item[territory_name]
	</div>
	<div class="cell" style="width:100px;">
		&nbsp;
	</div>
</div>
EOQ;
}

?>