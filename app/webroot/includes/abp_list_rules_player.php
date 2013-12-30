<?php
include 'includes/components/rule_list.php';

$page_title = "Rules List";

$sql = <<<EOQ
SELECT
	*
FROM
	territory_rules
WHERE
	is_active = 1
	AND territory_type_id = 1
ORDER BY
	power_type,
	rule_name
EOQ;

$result = ExecuteQuery($sql);

$page_content = <<<EOQ
<h2>
	Rule Information
</h2>
<div id="abpRuleList">
EOQ;

$page_content .= CreateRuleList($result, false);

$page_content .= <<<EOQ
</div>
EOQ;
?>