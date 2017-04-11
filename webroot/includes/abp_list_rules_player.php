<?php

use classes\abp\Rules;
use classes\core\repository\Database;

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

$rules = Database::getInstance()->query($sql)->all();

$page_content = <<<EOQ
<h2>
	Rule Information
</h2>
<div id="abpRuleList">
EOQ;

$page_content .= Rules::CreateRuleList($rules, false);

$page_content .= <<<EOQ
</div>
EOQ;
