<?php

use classes\abp\Rules;

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
<div id="rulePane" class="overlayInputPane" style="display:none;">
	<div id="rulePaneClose" class="overlayInputPaneClose">
		Close
	</div>
	<div id="rulePaneContent">
	</div>
</div>
<div class="paragraph">
	<a href="/abp.php">Back to ABP Home</a>
</div>
<h2>
	Rule Information
</h2>
<div class="paragraph">
	<a href="/abp.php?action=add_rule" class="overlayPanelAction" id="createRuleLink">Create Rule</a>
</div>
<div id="abpRuleList">
EOQ;

$page_content .= Rules::CreateRuleList($result);

$page_content .= <<<EOQ
</div>
<script type="text/javascript">	
	$(document).ready(function(){
		$("a.overlayPanelAction").click(function(e) {
			e.preventDefault();
			$("#rulePaneContent").load($(this).attr('href'), function() { $("#rulePane").css("display", "block") });
		});
		$("#rulePaneClose").click(function(e){
			$("#rulePane").css("display", "none");
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
		$(document).keypress(function(e){
			if(e.keyCode == 27)
			{
				$("#rulePane").css("display", "none");
			}
		});
		$(document).keydown(function(e){
			if(e.keyCode == 27)
			{
				$("#rulePane").css("display", "none");
			}
		});
	});
</script>
EOQ;
?>