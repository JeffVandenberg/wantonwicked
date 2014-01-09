<?php
$power_types = array("Merit", "ICDisc", "OOCDisc", "Devotion", "Derangement");
$power_typeNames = array("Merit", "In-Clan Discipline", "Out-of-Clan Disc.", "Devotion/Ritual/Misc.", "Derangement");

$power_typeSelect = buildSelect("", $power_types, $power_typeNames, "power_type");

$page_content = <<<EOQ
<h2>Create ABP Rule</h2>

<form id="createRuleForm">
<div class="formInput">
	<label>Rule Name:</label>
	<input type="text" name="ruleName" id="ruleName" value="" />
</div>
<div class="formInput">
	<label>Power Type:</label>
	$power_typeSelect
</div>
<div class="formInput">
	<label>Power Name:</label>
	<input type="text" name="powerName" id="powerName" value=""><br />
</div>
<div class="formInput">
	<label>Power Note:</label>
	<input type="text" name="powerNote" id="powerNote" value=""><br />
</div>
<div class="formInput">
	<label>Is Shared:</label>
	<input type="checkbox" name="isShared" id="isShared" value="y"><br />
</div>
<div class="formInput">
	<label>Multiplier:</label>
	<input type="text" name="multiplier" id="multiplier" value="0"><br />
</div>
<div class="formInput">
	<label>Modifier:</label>
	<input type="text" name="modifier" id="modifier" value="0"><br />
</div>
<div class="formInput">
	<input type="button" name="formSubmit" id="formSubmit" value="Create rule" />
</div>
</form>
<script language="javascript">
	$(document).ready(function(){
		$('input:text').keypress(function(e){
			if(e.keyCode == 13)
			{
				return false;
			}
		});
		$('#formSubmit').click(function(){
			var errors = '';
			if($.trim($('#ruleName').val()) == '')
			{
				errors += " - Enter a name for the Rule.\\r\\n";
			}
			if($.trim($('#power_type').val()) == '')
			{
				errors += ' - Enter a power Type (Merit, ICDisc, OOCDisc, Devotion, Derangement).\\r\\n';
			}
			if($.trim($('#powerName').val()) == '')
			{
				errors += ' - Enter a Power Name.\\r\\n';
			}
			
			if(errors == '')
			{
				$.ajax({
					url: "/abp.php?action=add_rule_post",
					data: $('#createRuleForm').serialize(),
					type: "post",
					dataType: "html",
					success: function(response, status, request) {
						refreshAbpRuleList(response);
						$("#rulePane").css("display", "none") ;
					},
					error: function(request, message, exception) {
						alert('There was an error submitting the request. Please try again.');
					}
				});
			}
			else
			{
				alert('Please correct the following errors: \\r\\n' + errors);
			}
		});
	});
</script>
EOQ;
?>