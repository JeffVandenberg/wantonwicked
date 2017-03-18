<?php
$page_title = "ABP Management";

$page_content = <<<EOQ
<h1>ABP Management</h1>
<div class="paragraph">
	Here are tools to help manage ABP. It's still in progress. 
</div>
<div class="paragraph">
	<a href="/territory.php?action=list">Manage Domains</a>
</div>
<div class="paragraph">
	<a href="/abp.php?action=character_report">List Where characters are feeding</a>
</div>
<div class="paragraph">
	<a href="/abp.php?action=list_rules">Manage ABP Rules</a>
</div>
<div class="paragraph">
	<a href="/abp.php?action=recalculate">Force ABP Recalculation</a> (for all Vampires)
</div>
<div class="paragraph">
	<a href="/abp.php?action=report">ABP Report</a> (for all Vampires)
</div>
EOQ;
?>