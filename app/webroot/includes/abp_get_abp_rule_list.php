<?php
use classes\abp\Rules;

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

$ruleList .= Rules::CreateRuleList($result);

echo <<<EOQ
$ruleList
<script type="text/javascript">	
	$(document).ready(function(){
		$("a.overlayPanelAction").click(function(e) {
			e.preventDefault();
			$("#rulePaneContent").load($(this).attr('href'), function() { $("#rulePane").css("display", "block") });
		});
		$("a.actionLink").click(function() {
			if(confirm('Are you sure you want to delete: ' + $(this).attr('ruleName') + '?'))
			{
				$.ajax({
					url: $(this).attr('href'),
					type: 'post',
					data: {id: $(this).attr('id')},
					success: refreshAbpRuleList
				});
			}
			return false;
		});
	});
</script>
EOQ;
?>