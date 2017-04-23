<?php
$character_id = $_GET['c'] + 0;

$options = array("Attributes", "Skills", "Merits", "In Clan Disciplines", "Out of Clan Disciplines", "Devotions", "Specialties");

$select1 = buildMultiSelect("", $options, $options, "select_1", 7, true);
$page_content = <<<EOQ
<form method="post" action="" id="die_roller">
<div style="min-width:400px;">
<table>
	<tr>
		<td colspan="3" style="text-align:center;min-width:400px;">
			<a href="#" id="reset_link">Reset Roll</a>
		</td>
	</tr>
	<tr>
		<td>
			Type
		</td>
		<td>
			<div style="display:none;" id="options_header">
				Options
			</div>
		</td>
		<td>
			<div style="display:none;" id="options_header2">
				Dice to Roll
			</div>
		</td>
	</tr>
	<tr>
		<td style="vertical-align:top;">
			$select1
		</td>
		<td style="vertical-align:top;">
			<select name="select_2" id="select_2" style="display:none;font-size:20px;" size="9">
			</select>
		</td>
		<td style="vertical-align:top;">
			<input type="text" value="0" name="roll_total" id="roll_total" style="width:40px;display:none;">
			<div id="roll_description"></div>
		</td>
	</tr>
</table>
</div>
</form>
<script type="text/javascript">
	var stats = {
		"Attributes": {
			"Composure": 2,
			"Dexterity": 2,
			"Intelligence": 3,
			"Manipulation": 3,
			"Presence": 4,
			"Resolve": 3,
			"Stamina": 3, 
			"Strength": 2,
			"Wits": 3
			
		},
		"Skills": {
			"Academics": 2,
			"Athletics": 1,
			"Computer": -3,
			"Occult": -3
		},
		"Merits": {
			"Allies (Street)": 3,
			"Contacts (Bankers, Priests, Clowns)": 3,
			"Haven (Location)": 3,
			"Language (Chinese)": 1,
			"Resources (investments)": 2,
			"Status (City)": 3,
			"Status (Sanctified)": 4
		},
		"In Clan Disciplines": {
			"Animalism": 1,
			"Dominate": 3,
			"Resiliance": 3
		},
		"Out of Clan Disciplines": {
			"Auspex": 1,
			"Majesty": 4
		}
	};
	$(document).ready(function() {
		$("#select_1").click(function(e) {
			var option = $("#select_1").val();
			
			var select2 = $("#select_2");
			select2.empty();
			if(stats[option]) {
				for(item in stats[option]) {
					select2.append($("<option></option>").val(stats[option][item]).text(item));
				}
			}
			else {
				for(var i = 1; i < 9; i++) {
					select2.append($("<option></option>").val(i).text(option + " " + i));
				}
			}
			select2.show();
			$("#options_header").show();
			$("#options_header2").show();
			$("#roll_total").show();
		});
		
		$("#select_2").click(function(e) {
			var total = parseInt($("#roll_total").val()) + parseInt($(this).val());
			$("#roll_total").val(total);
			$("#roll_description").html($("#roll_description").html() + $("#select_2 option:selected").text() + ' ' + $("#select_2").val() + "<br />");
		});
		
		$("#reset_link").click(function(e) {
			$("#select_2").hide();
			$("#options_header").hide();
			$("#options_header2").hide();
			$("#roll_total").val("0").hide();
			$("#roll_description").html("");
		});
	});
</script>
EOQ;
?>