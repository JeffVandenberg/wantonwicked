<?php

$page_title = "Create Character V2 - Test / Pre-Alpha";
$characterName = "";

$characterNameInput = BuildInput(array("type" => "text", "name" => "characterName", "options" => array("class" => "track-length", "maxlength" => 40)));

$characterType = "Mortal";
$characterTypes = array("Mortal" => "Mortal", "Vampire" => "Vampire", "Werewolf" => "Werewolf", "Mage" => "Mage");

$options = array("type" => 'select', 'value' => $characterType, 'values' => $characterTypes, 'name' => 'characterType', 'options' => array());
$characterTypeSelect = BuildInput($options);

$conceptInput = BuildInput(array("type" => "text", "name" => "concept", "options" => array("class" => "long-text track-length", "maxlength" => 100)));

$virtues = array("Charity" => "Charity", "Faith" => "Faith", "Fortitude" => "Fortitude", "Hope" => "Hope", "Justice" => "Justice", "Prudence" => "Prudence", "Temperance" => "Temperance");
$options = array("type" => 'select', 'value' => "", 'values' => $virtues, 'name' => 'virtue', 'options' => array());
$virtueSelect = BuildInput($options);

$vices = array("Envy" => "Envy", "Gluttony" => "Gluttony", "Greed" => "Greed", "Lust" => "Lust", "Pride" => "Pride", "Sloth" => "Sloth", "Wrath" => "Wrath");
$options = array("type" => 'select', 'value' => "", 'values' => $vices, 'name' => 'vice', 'options' => array());
$viceSelect = BuildInput($options);

$locations = array("San Diego" => "San Diego", "The City" => "The City", "Side Game" => "Side Game");
$options = array("type" => 'select', 'value' => "", 'values' => $locations, 'name' => 'location', 'options' => array());
$locationSelect = BuildInput($options);

$characterAgeInput = BuildInput(array("type" => "text", "name" => "characterAge", "options" => array("class" => "short-text", "maxlength" => 3)));

$attributes = array("intelligence" => 2, "wits" => 2, "resolve" => 2, "strength" => 2, "dexterity" => 2, "stamina" => 2, "presence" => 2, "manipulation" => 2, "composure" => 2);

foreach($attributes as $key => $value) {
	$options = array("type" => 'dots', 'value' => $value, 'numberOfDots' => 7, 'editable' => true, 'name' => $key,  'group' => 'attribute', 'minValue' => 1, 'options' => array());
	${$key . "Input"} = buildInput($options);
};

$skills = array("academics" => 0, "animalKen" => 0, "athletics" => 0, "brawl" => 0, "computer" => 0, "crafts" => 0, "drive"  => 0, "empathy"  => 0, "expression"  => 0, "firearms"  => 0, "intimidate"  => 0, "investigation"  => 0, "larceny"  => 0, "medicine"  => 0, "occult"  => 0, "persuasion"  => 0, "politics"  => 0, "science"  => 0, "socialize"  => 0, "stealth"  => 0, "streetwise"  => 0, "subterfuge"  => 0, "survival"  => 0, "weaponry" => 0);

foreach($skills as $key => $value) {
	$options = array("type" => 'dots', 'value' => $value, 'numberOfDots' => 7, 'editable' => true, 'name' => $key,  'group' => 'skill', 'minValue' => 0, 'options' => array());
	${$key . "Input"} = buildInput($options);
};


$merits = array(
	0 => array('id' => 1, 'name' => '', 'note' => '', 'value' => 0), 
	1 => array('id' => 2, 'name' => '', 'note' => '', 'value' => 0), 
	2 => array('id' => 3, 'name' => '', 'note' => '', 'value' => 0), 
	3 => array('id' => 4, 'name' => '', 'note' => '', 'value' => 0), 
	4 => array('id' => 5, 'name' => '', 'note' => '', 'value' => 0)
);

$meritList = "";
foreach($merits as $key => $value) {
	$meritNameInput = BuildInput(array("type" => "text", "name" => "meritName[]", 'id' => 'merit-name' . $key, 'value' => $value['name'], "options" => array("class" => "medium-text item-name", "maxlength" => 40)));
	$meritNoteInput = BuildInput(array("type" => "text", "name" => "meritNote[]", 'id' => 'merit-note' . $key, 'value' => $value['note'], "options" => array("class" => "medium-text item-note", "maxlength" => 40)));
	$options = array("type" => 'dots', 'value' => $value['value'], 'numberOfDots' => 7, 'editable' => true, 'name' => 'meritValue[]', 'id' => 'merit-value' . $key, 'group' => 'merit', 'minValue' => 0, 'options' => array());
	$meritValueInput = buildInput($options);
	
	$meritList .= <<<EOQ
	<tr id="merit$key">
		<td>
			$meritNameInput
		</td>
		<td>
			$meritNoteInput
		</td>
		<td>
			$meritValueInput
			<img src="img/slash.png" class="remove-merit" />
		</td>
	</tr>
EOQ;
}

$flaws = array(
	0 => array('id' => 1, 'name' => '', 'note' => '', 'value' => 0), 
	1 => array('id' => 2, 'name' => '', 'note' => '', 'value' => 0)
);

$flawList = "";
foreach($flaws as $key => $value) {
	$flawNameInput = BuildInput(array("type" => "text", "name" => "flawName[]", 'id' => 'flaw-name' . $key, 'value' => $value['name'], "options" => array("class" => "item-name", "maxlength" => 40)));
	
	$flawList .= <<<EOQ
	<tr id="flaw$key">
		<td>
			$flawNameInput
		</td>
		<td>
			<img src="img/slash.png" class="remove-flaw" />
		</td>
	</tr>
EOQ;
}

$description = "";
$descriptionInput = BuildInput(array("type" => "text", "name" => "description", "options" => array("class" => "long-text track-length", "maxlength" => 250)));

$publicEffects = "";
$publicEffectsInput = BuildInput(array("type" => "text", "name" => "publicEffects", "options" => array("class" => "long-text track-length", "maxlength" => 250)));

$carriedEquipment = "";
$carriedEquipmentInput = BuildInput(array("type" => "text", "name" => "carriedEquipment", "options" => array("class" => "long-text track-length", "maxlength" => 250)));

$otherEquipment = "";
$otherEquipmentInput = BuildInput(array("type" => "text", "name" => "otherEquipment", "options" => array("class" => "long-text track-length", "maxlength" => 250)));

$background = "";
$backgroundInput = BuildInput(array("type" => "textarea", "name" => "background", "options" => array("class" => "long-text", "rows" => 10)));

$beliefs = "";
$beliefsInput = BuildInput(array("type" => "textarea", "name" => "beliefs", "options" => array("class" => "long-text", "rows" => 6)));

ob_start();
?>

<div style="margin: 10px 0 10px 0;">In development. If you experience issues, please talk to Jeff V.</div>
<div id="error-message" style="display:none"></div>
<div id="character" style="min-width:870px;">
	<ul>
		<li><a href="#character-tab-1" id="intro-tab">Intro</a></li>
		<li><a href="#character-tab-2" id="incentive-tab">Incentive XP</a></li>
		<li><a href="#character-tab-9" id="template-tab">Templates</a></li>
		<li><a href="#character-tab-3" id="stats-tab">Stats</a></li>
		<li><a href="#character-tab-4" id="biography-tab">Biography</a></li>
		<li><a href="#character-tab-5" id="overview-tab">Overview</a></li>
		<li><a href="#character-tab-6" id="xp-request-tab">XP Requests</a></li>
		<li><a href="#character-tab-7" id="tools-tab">Tools</a></li>
		<li><a href="#character-tab-8" id="st-tools-tab">ST Info</a></li>
	</ul>
	<div id="character-tab-1" style="overflow:auto;">
		Basic Character information such as name, character type, etc.<br />
		<br />
		<table style="width:100%;border:none;" cellpadding="3">
			<tr>
				<td style="width:50%;">
					<span class="form-field helper-tip" tip="This is a required field.">Character Name:</span>
					<br />
					<?php echo $characterNameInput; ?>
				</td>
				<td style="width:50%;">
					<span class="form-field helper-tip" tip="Actual age of the character.">Age:</span>
					<br />
					<?php echo $characterAgeInput; ?>
				</td>
			</tr>
			<tr>
				<td colspan="2">
					<span class="form-field helper-tip" tip="Provide a short description of the character you're wanting to play">Concept:</span>
					<br />
					<?php echo $conceptInput; ?>
				</td>
			</tr>
			<tr>
				<td style="width:50%">
					<span class="form-field helper-tip" tip="The currently allowed character types in Wanton Wicked.">Character Type:</span><br />
					<?php echo $characterTypeSelect; ?>
				</td>
				<td style="width:50%;">
					<span class="form-field">Location:</span>
					<br />
					<?php echo $locationSelect; ?>
				</td>
			</tr>
			<tr>
				<td style="width:50%;">
					<span class="form-field">Virtue:</span>
					<br />
					<?php echo $virtueSelect; ?>
				</td>
				<td style="width:50%;">
					<span class="form-field">Vice:</span>
					<br />
					<?php echo $viceSelect; ?>
				</td>
			</tr>
		</table>
	</div>
	<div id="character-tab-2">
		<b><span id="incentive-character-type"></span></b><br />
		<br />
		Incentive XP choices to encourge certain character concepts and types.<br />
		<br />
		<select id="incentive-xp-select"></select>
		<input type="button" id="incentive-xp-add" value="Add">
	</div>
	<div id="character-tab-3">
		<div style="margin: 10px 0 10px 0;">Options for: <span class="form-field" id="stats-character-type-text"></span></div>
		<table style="width:100%;" cellspacing="0" cellpadding="0">
			<tr>
				<th colspan="3" class="header-row">
					Current XP Pools
				</th>
			</tr>
			<tr>
				<th colspan="3" class="header-row">
					Attribute XP: <input type="text" name="attributeXp" id="attribute-xp" value="0" class="short-text" readonly> 
					Skill XP: <input type="text" name="skillXp" id="skill-xp" value="0" class="short-text" readonly> 
					Merit XP: <input type="text" name="meritXp" id="merit-xp" value="0" class="short-text" readonly> 
					Supernatural XP: <input type="text" name="supernaturalXp" id="supernatural-xp" value="0" class="short-text" readonly> 
					General XP: <input type="text" name="generalXp" id="general-xp" value="20" class="short-text" readonly>
				</th>
			</tr>
		</table>
		<table style="width:100%;" cellspacing="0" cellpadding="0">
			<tr>
				<th colspan="6" class="header-row">
					Attributes
				</th>
			</tr>
			<tr>
				<td width="13%;">
					Intelligence
				</td>
				<td width="20%;">
					<?php echo $intelligenceInput; ?>
				</td>
				<td width="13%;">
					Strength
				</td>
				<td width="21%;">
					<?php echo $strengthInput; ?>
				</td>
				<td width="13%;">
					Presence
				</td>
				<td width="20%;">
					<?php echo $presenceInput; ?>
				</td>
			</tr>
			<tr>
				<td width="13%;">
					Wits
				</td>
				<td width="20%;">
					<?php echo $witsInput; ?>
				</td>
				<td width="13%;">
					Dexterity
				</td>
				<td width="21%;">
					<?php echo $dexterityInput; ?>
				</td>
				<td width="13%;">
					Manipulation
				</td>
				<td width="20%;">
					<?php echo $manipulationInput; ?>
				</td>
			</tr>
			<tr>
				<td width="13%;">
					Resolve
				</td>
				<td width="20%;">
					<?php echo $resolveInput; ?>
				</td>
				<td width="13%;">
					Stamina
				</td>
				<td width="21%;">
					<?php echo $staminaInput; ?>
				</td>
				<td width="13%;">
					Composure
				</td>
				<td width="20%;">
					<?php echo $composureInput; ?>
				</td>
			</tr>
		</table>
		<table style="width:100%;" cellspacing="0" cellpadding="0">
			<tr>
				<th colspan="6" class="header-row">
					Skills
				</th>
			</tr>
			<tr style="vertical-align: top;">
				<td width="13%;">
					Academics
					<img src="img/green_plus.gif" class="add-specialty" stat="academics" />
				</td>
				<td width="20%;">
					<div id="academics-wrapper">
					<?php echo $academicsInput; ?>
					</div>
				</td>
				<td width="13%;">
					Athletics
					<img src="img/green_plus.gif" class="add-specialty" stat="athletics" />
				</td>
				<td width="21%;">
					<div id="athletics-wrapper">
					<?php echo $athleticsInput; ?>
					</div>
				</td>
				<td width="13%;">
					Animal Ken
					<img src="img/green_plus.gif" class="add-specialty" stat="animal-ken" />
				</td>
				<td width="20%;">
					<div id="animal-ken-wrapper">
					<?php echo $animalKenInput; ?>
					</div>
				</td>
			</tr>
			<tr style="vertical-align: top;">
				<td width="13%;">
					Computer
					<img src="img/green_plus.gif" class="add-specialty" stat="computer" />
				</td>
				<td width="20%;">
					<div id="computer-wrapper">
					<?php echo $computerInput; ?>
					</div>
				</td>
				<td width="13%;">
					Brawl
					<img src="img/green_plus.gif" class="add-specialty" stat="brawl" />
				</td>
				<td width="21%;">
					<div id="brawl-wrapper">
					<?php echo $brawlInput; ?>
					</div>
				</td>
				<td width="13%;">
					Empathy
					<img src="img/green_plus.gif" class="add-specialty" stat="empathy" />
				</td>
				<td width="20%;">
					<div id="empathy-wrapper">
					<?php echo $empathyInput; ?>
					</div>
				</td>
			</tr>
			<tr style="vertical-align: top;">
				<td width="13%;">
					Crafts
					<img src="img/green_plus.gif" class="add-specialty" stat="crafts" />
				</td>
				<td width="20%;">
					<div id="crafts-wrapper">
					<?php echo $craftsInput; ?>
					</div>
				</td>
				<td width="13%;">
					Drive
					<img src="img/green_plus.gif" class="add-specialty" stat="drive" />
				</td>
				<td width="21%;">
					<div id="drive-wrapper">
					<?php echo $driveInput; ?>
					</div>
				</td>
				<td width="13%;">
					Expression
					<img src="img/green_plus.gif" class="add-specialty" stat="expression" />
				</td>
				<td width="20%;">
					<div id="expression-wrapper">
					<?php echo $expressionInput; ?>
					</div>
				</td>
			</tr>
			<tr style="vertical-align: top;">
				<td width="13%;">
					Investigation
					<img src="img/green_plus.gif" class="add-specialty" stat="investigation" />
				</td>
				<td width="20%;">
					<div id="investigation-wrapper">
					<?php echo $investigationInput; ?>
					</div>
				</td>
				<td width="13%;">
					Firearms
					<img src="img/green_plus.gif" class="add-specialty" stat="firearms" />
				</td>
				<td width="21%;">
					<div id="firearms-wrapper">
					<?php echo $firearmsInput; ?>
					</div>
				</td>
				<td width="13%;">
					Intimidate
					<img src="img/green_plus.gif" class="add-specialty" stat="intimidate" />
				</td>
				<td width="20%;">
					<div id="intimidate-wrapper">
					<?php echo $intimidateInput; ?>
					</div>
				</td>
			</tr>
			<tr style="vertical-align: top;">
				<td width="13%;">
					Medicine
					<img src="img/green_plus.gif" class="add-specialty" stat="medicine" />
				</td>
				<td width="20%;">
					<div id="medicine-wrapper">
					<?php echo $medicineInput; ?>
					</div>
				</td>
				<td width="13%;">
					Larceny
					<img src="img/green_plus.gif" class="add-specialty" stat="larceny" />
				</td>
				<td width="21%;">
					<div id="larceny-wrapper">
					<?php echo $larcenyInput; ?>
					</div>
				</td>
				<td width="13%;">
					Persuasion
					<img src="img/green_plus.gif" class="add-specialty" stat="persuasion" />
				</td>
				<td width="20%;">
					<div id="persuasion-wrapper">
					<?php echo $persuasionInput; ?>
					</div>
				</td>
			</tr>
			<tr style="vertical-align: top;">
				<td width="13%;">
					Occult
					<img src="img/green_plus.gif" class="add-specialty" stat="occult" />
				</td>
				<td width="20%;">
					<div id="occult-wrapper">
					<?php echo $occultInput; ?>
					</div>
				</td>
				<td width="13%;">
					Stealth
					<img src="img/green_plus.gif" class="add-specialty" stat="stealth" />
				</td>
				<td width="21%;">
					<div id="stealth-wrapper">
					<?php echo $stealthInput; ?>
					</div>
				</td>
				<td width="13%;">
					Socialize
					<img src="img/green_plus.gif" class="add-specialty" stat="socialize" />
				</td>
				<td width="20%;">
					<div id="socialize-wrapper">
					<?php echo $socializeInput; ?>
					</div>
				</td>
			</tr>
			<tr style="vertical-align: top;">
				<td width="13%;">
					Politics
					<img src="img/green_plus.gif" class="add-specialty" stat="politics" />
				</td>
				<td width="20%;">
					<div id="politics-wrapper">
					<?php echo $politicsInput; ?>
					</div>
				</td>
				<td width="13%;">
					Survival
					<img src="img/green_plus.gif" class="add-specialty" stat="survival" />
				</td>
				<td width="21%;">
					<div id="survival-wrapper">
					<?php echo $survivalInput; ?>
					</div>
				</td>
				<td width="13%;">
					Streetwise
					<img src="img/green_plus.gif" class="add-specialty" stat="streetwise" />
				</td>
				<td width="20%;">
					<div id="streetwise-wrapper">
					<?php echo $streetwiseInput; ?>
					</div>
				</td>
			</tr>
			<tr style="vertical-align: top;">
				<td width="13%;">
					Science
					<img src="img/green_plus.gif" class="add-specialty" stat="science" />
				</td>
				<td width="20%;">
					<div id="science-wrapper">
					<?php echo $scienceInput; ?>
					</div>
				</td>
				<td width="13%;">
					Weaponry
					<img src="img/green_plus.gif" class="add-specialty" stat="weaponry" />
				</td>
				<td width="21%;">
					<div id="weaponry-wrapper">
					<?php echo $weaponryInput; ?>
					</div>
				</td>
				<td width="13%;">
					Subterfuge
					<img src="img/green_plus.gif" class="add-specialty" stat="subterfuge" />
				</td>
				<td width="20%;">
					<div id="subterfuge-wrapper">
					<?php echo $subterfugeInput; ?>
					</div>
				</td>
			</tr>
		</table>
		<table style="width:100%;" border="0" cellspacing="0" cellpadding="0">
			<tr>
				<th colspan="2" class="header-row">
					Abilities
				</th>
			</tr>
			<tr>
				<th colspan="1" class="header-row" width="50%">
					Merits
					<img src="img/green_plus.gif" class="add-merit" />
				</th>
				<th colspan="1" class="header-row" width="50%">
					Flaws
					<img src="img/green_plus.gif" class="add-flaw"/>
				</th>
			</tr>
			<tr style="vertical-align:top;">
				<td>
					<table id="merit-list" cellspacing="0" style="width:100%;margin:auto;">
						<?php echo $meritList; ?>
					</table>
				</td>
				<td>
					<table id="flaw-list" cellspacing="0" style="width:20%;margin:auto;">
						<?php echo $flawList; ?>
					</table>
				</td>
			</tr>
			<tr>
				<th colspan="2" class="header-row" width="33%">
					Extra
				</th>
			</tr>
			<tr>
				<td colspan="2" id="extra-content">
				</td>
			</tr>
		</table>
		<br />
		<br />
	</div>
	<div id="character-tab-4">
		<table style="width:100%;border:none;">
			<tr>
				<td>
					<span class="form-field helper-tip" tip="The character's physical description.">Description:</span>
					<br />
					<?php echo $descriptionInput; ?>
				</td>
			</tr>
			<tr>
				<td>
					<span class="form-field helper-tip" tip="Obvious things that most other characters could notice easily. Striking Looks, Status, and other similar effects.">Public Effects:</span>
					<br />
					<?php echo $publicEffectsInput; ?>
				</td>
			</tr>
			<tr>
				<td>
					<span class="form-field helper-tip" tip="Equipment the character caries on them regularly.">Carried Equipment:</span>
					<br />
					<?php echo $carriedEquipmentInput; ?>
				</td>
			</tr>
			<tr>
				<td>
					<span class="form-field helper-tip" tip="Other Equipment that a character tends to keep at home.">Other Equipment:</span>
					<br />
					<?php echo $otherEquipmentInput; ?>
				</td>
			</tr>
			<tr>
				<td>
					<span class="form-field helper-tip" tip="What are your character's beliefs? Make them actionable. Make them drive play in game.">Beliefs:</span>
					<br />
					<?php echo $beliefsInput; ?>
				</td>
			</tr>
			<tr>
				<td>
					<span class="form-field helper-tip" tip="A history of the character. Including a bullet-point version of the history is encouraged.">Background:</span>
					<br />
					<?php echo $backgroundInput; ?>
				</td>
			</tr>
		</table>
		<br />
		<br />
	</div>
	<div id="character-tab-5" style="overflow:auto;">
		View of the complete Sheet.<br />
		<br />
		<div class="firstCell cell" style="width:400px;">
			<b>Character Name:</b> <span id="view-character-name"></span>
		</div>
	</div>
	<div id="character-tab-6">
		Outstanding and Past XP Requests (New Feature!)<br /><br />
		XP Request Mode:
		Yes: <input type="radio" name="xpRequest" id="xp-request-yes" value="yes" />
		No: <input type="radio" name="xpRequest" value="no" checked />
	</div>
	<div id="character-tab-7">
		Character specific tools (Link to wiki, ABP, etc).
	</div>
	<div id="character-tab-8">
		ST Information (Sanctioning status, etc.)
	</div>
	<div id="character-tab-9">
		Template Character Options/Builds. Will be enforced on character creation
	</div>
</div>
<div id="modal-dialog" title="Updating..." style="display:none;"><p id="modal-dialog-message"></p></div>
<div id="xp-request-modal-dialog" title="XP Request" style="display:none;">
<b>Type</b>: <span id="xp-request-modal-group"></span><br />
<br />
<b>Item</b>: <span id="xp-request-modal-item"></span><br />
<br />
<b>Value</b>: <span id="xp-request-modal-value"></span><br />
<br />
<b>Your Notes</b>:
<textarea id="xp-request-modal-notes" rows="6" cols="40"></textarea>
<br />
</div>
<div id="floater-div" style="position:absolute;width:250px;background-color:#333333;border:solid 2px #ffffff;display:none;padding:3px;"></div>

<script type="text/javascript">
	var character = {
	};
	character.attributeXp = 180;
	character.skillXp = 105;
	character.meritXp = 30;
	character.supernaturalXp = 0;
	character.generalXp = 30;
	
	character.currentAttributeXp = 0;
	character.currentSkillXp = 0;
	character.currentMeritXp = 0;
	character.currentSupernaturalXp = 0;
	character.currentGeneralXp = 0;
	
	character.freeSkillSpecialties = 3;
	
	$(document).ready(function() {
		$("#character").tabs();
		//$("#character").tabs("disable", 1);
		//$("#character").tabs("disable", 2);
		//$("#character").tabs("select", 3);
		$(".hidden-input").hide();
		$("#merit-list input.item-name").watermark('Merit Name');
		$("#merit-list input.item-note").watermark('Merit Note');
		$("#flaw-list input.item-name").watermark('Flaw');
		UpdateAttributeXp();
		UpdateSkillXp();
		UpdateMeritXp();
		UpdateSupernaturalXp();
		
		character.templates = new Array();
		character.templates[0] = {};
		character.templates[0].name = 'Police 1';
		character.templates[0].attribute = {};
		character.templates[0].attribute.intelligence = 2;
		
		$("input, textarea").live("focus", function(e) {
			$(this).css("border", "solid 3px #2277cc");
		}).live("blur", function(e) {
			$(this).css("border", "none");
		}).css("border", "none");
		
		$("select").focus(function(e) {
			$(this).css("border", "3px solid #2277cc").css("height", "28px");
		}).blur(function(e) {
			$(this).css("border-width", "0px").css("height", "22px");
		})
		.css("border-width", "0px")
		.css("background-color", "#ffffff")
		.css("height", "22px");
		
		$("#character-type").change(function(e) {
			$("#modal-dialog-message").html("Updating character sheet to be configured for " + $(this).val() + ". Note this is doing nothing right now.");
			$("#modal-dialog").dialog({
				height: 200,
				modal: true
			});
			$.get("/view_sheet.php?action=get_fragment&character_type=" + $(this).val(), UpdateSheet)
		});
		
		$(".helper-tip").live("mouseenter", function(e) {
			$("#floater-div").html($(this).attr('tip'));
			var position = $(this).offset();
			$("#floater-div").css("left", position.left);
			$("#floater-div").css("top", position.top - $("#floater-div").outerHeight(true));
			$("#floater-div").show();
			//alert($(this).attr('tip'));
		});
		
		$(".helper-tip").live("mouseleave", function(e) {
			$("#floater-div").hide();
		});
		
		$(".track-length").each(function() {
			var header = $(this).prev().prev();
			var text = $("<span></span>").attr("id", $(this).attr("id") + "-characters-remaining").css('margin-left', '6px').html(($(this).attr("maxlength") - $(this).val().length) + " characters");
			header.after(text);
		}).live("keyup", function(e) {
			$("#" + $(this).attr("id") + "-characters-remaining").html(($(this).attr("maxlength") - $(this).val().length) + " characters");
		});
		
		$("#incentive-tab").click(function(e) {
			$("#incentive-character-type").html($("#character-type").val());
			RefreshIncentiveOptions();
		});
		
		$("#incentive-xp-add").click(function(e) {
			var selected = $("#incentive-xp-select :selected");
			var data = selected.data();
			if(data.characterLink) {
				data.extraName = prompt("Please provide character name:", "");
			}
			if(data.locationLink) {
				data.extraName = prompt("Please Provide location name:", "");
			}
			AppendToIncentiveList(data);
		});
		
		$("#stats-tab").click(function(e) {
			$("#stats-character-type-text").html($("#character-type").val());
			/*alert('load character costs module for: ' + $("#character-type").val());*/
		});
		
		$("#overview-tab").click(function(e) {
			$("#view-character-name").html($("#character-name").val());
		});
		
		$(document).keyup(function (e) {
			if($(".current-dots").length > 0) {
				switch(e.keyCode) {
					case 37: 
						// left
						//alert('decrease attribute');
						break;
					case 39:
						// right
						//alert('increase attribute');
						break;
				}
			}
		});
		
		$(".clickable-dot").live("click", function(e) {
			//$(".current-dots").removeClass("current-dots");
			//$(this).parent().addClass("current-dots");
			var group = $(this).attr("group");
			var id = $(this).attr("stat");
			var value = $(this).attr("value");
			if(ValidateAgainstTemplates(group, id, value)) {
				UpdateValue(group, id, value);
				if($("#xp-request-yes").prop('checked')) {
					CreateRequest(group, id, value, this);
				}
			}
		});
		
		$(".add-specialty").live("click", function(e) {
			var name = $(this).attr("stat");
			var newSpecialty = $("<div></div>").attr("id", name +"-spec" + $("." + name + "-specialty").length).addClass(name + "-specialty");
			var textBox = $("<input />").attr("type", "text").addClass("medium-text item-name skill-specialty").attr("name", name + "-spec-name[]").attr("maxLength", 30);
			var hiddenId = $("<input />").attr("type", "hidden").attr("name", name + "-spec-id[]").val(0);
			var remove = $("<img />").attr("src", "img/red_x.png").addClass("remove-specialty");
			newSpecialty.append("Spec: ");
			newSpecialty.append(textBox);
			newSpecialty.append(hiddenId);
			newSpecialty.append(remove);
			$("#" + name + "-wrapper").append(newSpecialty);
		});
		
		$(".remove-specialty").live("click", function(e) {
			var row = $(this).parent();
			row.find('.item-name').val('delete');
			row.hide();
			UpdateSkillXp();
		});
		
		$(".add-merit").click(function(e) {
			var newRow = $("#merit-list tr").length;
			var row = $("<tr></tr>").attr("id", "merit" + newRow);
			var cell = $("<td></td>");
			var input = $("<input />").attr('name', 'meritName[]').attr('id', 'merit-name' + newRow).addClass('medium-text item-name').watermark("Merit Name");
			cell.append(input);
			row.append(cell);
			
			var cell = $("<td></td>");
			var input = $("<input />").attr('note', 'meritNote[]').attr('id', 'merit-note' + newRow).addClass('medium-text item-note').watermark("Merit Note");
			cell.append(input);
			row.append(cell);
			
			var cell = $("<td></td>");
			var input = CreateDots('meritValue[]', 'merit-value' + newRow, 0, 'merit', 0, 7);
			var clearRow = $("<img />").attr("src", "img/slash.png").addClass("remove-merit");
			cell.append(input);
			cell.append($("<span></span>").html(" "));
			cell.append(clearRow);
			row.append(cell);
			
			$("#merit-list").append(row);
		});
		
		$(".remove-merit").live("click", function(e) {
			var row = $(this).parent().parent();
			row.find(".item-name").val("delete");
			row.find(".hidden-input").val(0);
			row.hide();
			UpdateMeritXp();
		});
		
		$(".add-flaw").click(function(e) {
			var newRow = $("#flaw-list tr").length;
			var row = $("<tr></tr>").attr("id", "flaw" + newRow);
			var cell = $("<td></td>");
			var input = $("<input />").attr('name', 'flawName[]').attr('id', 'flaw-name' + newRow).addClass('item-name').watermark("Flaw");
			cell.append(input);
			row.append(cell);
			
			var cell = $("<td></td>");
			var clearRow = $("<img />").attr("src", "img/slash.png").addClass("remove-flaw");
			cell.append(clearRow);
			row.append(cell);
			
			$("#flaw-list").append(row);
		});
		
		$(".remove-flaw").live("click", function(e) {
			var row = $(this).parent().parent();
			row.find(".item-name").val("delete");
			row.hide();
		});
		
		$(".skill-specialty").live("blur", function(e) {
			UpdateSkillXp();
		});
		
		$("#xp-request-yes").click(function(e) {
			$("#character").tabs("select", 3);
		});
	});
	
	function UpdateSheet(data) {
		character.supernaturalXp = data.SupernaturalXp
		UpdateSupernaturalXp();
		$("#extra-content").html(data.ExtraContent);
		$("#modal-dialog").dialog("close");
	}
	
	function UpdateValue(group, id, value) {
		$("#" + id).val(value);
		var i = 1;
		while($("#" + id + i).length > 0) {
			if(i <= value) {
				$("#" + id + i).attr("src", "img/mortal_filled.gif");
			}
			else {
				$("#" + id + i).attr("src", "img/empty.gif");
			}
			i++;
		}
		UpdateXP(group, id, value);
	}
	
	function CreateDots(name, id, value, group, minValue, maxDots) {
		var dots = $("<span></span>");
		
		for(var i = 1; i <= maxDots; i++) {
			var src = "img/empty.gif";
			if(i <= value) {
				src = "img/mortal_filled.gif";
			}
			
			var dot = $("<img />").attr('src', src).addClass("clickable-dot").attr("id", id + i).attr("group", group).attr("stat", id).attr("value", i);
			dots.append(dot);
		}
		
		var hiddenInput = $("<input />").attr("type", "text").addClass("small-text hidden-input " + group).attr("name", name).attr("id", id).attr("value", value).hide();
		dots.append(hiddenInput);
		
		if(minValue === 0) {
			var clearX = $("<img />").attr("src", 'img/red_x.png').attr('stat', id).attr('group', group).addClass("clickable-dot").attr("value", 0);
			dots.append(clearX);
		}
		
		return dots;
	};
	
	function UpdateXP(group, stat, value) {
		switch(group) {
			case "attribute":
				UpdateAttributeXp();
				break;
			case "skill":
				UpdateSkillXp();
				break;
			case "merit":
				UpdateMeritXp();
				break;
			case "supernatural":
				UpdateSupernaturalXp();
				break;
		}
	}
	
	function UpdateAttributeXp() {
		var xp = character.attributeXp;
		$("input.attribute").each(function(index, element) {
			var val = parseInt($(this).val());
			xp -= (val * (val +1) / 2) * 5;
		});
		character.currentAttributeXp = xp;
		if(xp < 0) {
			xp = 0;
		}
		$("#attribute-xp").val(xp);
		UpdateGeneralXp();
	}
	
	function UpdateSkillXp() {
		var xp = character.skillXp;
		$("input.skill").each(function(index, element) {
			var val = parseInt($(this).val());
			xp -= (val * (val +1) / 2) * 3;
		});
		
		var skillSpecs = 0;
		$(".skill-specialty").each(function(index, element) {
			if(($.trim($(this).val()) !== '') 
				&& ($(this).val().toLowerCase() !== 'delete')) {
				skillSpecs++;
			}
		});
		
		if(skillSpecs > character.freeSkillSpecialties) {
			xp -= ((skillSpecs - character.freeSkillSpecialties) * 3);
		}
		
		character.currentSkillXp = xp;
		if(xp < 0) {
			xp = 0;
		}
		$("#skill-xp").val(xp);
		UpdateGeneralXp();
	}
	
	function UpdateMeritXp() {
		var xp = character.meritXp;
		$("input.merit").each(function(index, element) {
			var val = parseInt($(this).val());
			xp -= (val * (val +1) / 2) * 2;
		});
		character.currentMeritXp = xp;
		if(xp < 0) {
			xp = 0;
		}
		$("#merit-xp").val(xp);
		UpdateGeneralXp();
	}
	
	function UpdateSupernaturalXp() {
		//alert('unimplemented');
		var xp = character.supernaturalXp;
		character.currentSupernaturalXp = xp;
		
		$("#supernatural-xp").val(xp);
	}
	
	function UpdateGeneralXp() {
		var xp = character.generalXp;
		if(character.currentAttributeXp < 0) {
			xp += character.currentAttributeXp;
		}
		if(character.currentSkillXp < 0) {
			xp += character.currentSkillXp;
		}
		if(character.currentMeritXp < 0) {
			xp += character.currentMeritXp;
		}
		if(character.currentSupernaturalXp < 0) {
			xp += character.currentSupernaturalXp;
		}
		$("#general-xp").val(xp);
	}
	
	function RefreshIncentiveOptions() {
		var options = GetOptionsForType($("#character-type").val());
		$("#incentive-xp-select").empty();
		for(var option in options) {
			$("#incentive-xp-select").append(
				$("<option></option>")
					.html(FormatDataForIncentiveList(options[option]))
					.val(options[option].id)
					.data(options[option])
			);
		}
	}
	
	function FormatDataForIncentiveList(data) {
		var string = data.name + " - Provides Attribute: " + data.statXp + " Skill: " + data.skillXp + " Merit: " + data.meritXp + " General: " + data.generalXp;
		if(data.characterLink) {
			string += " Character Link";
		}
		
		if(data.locationLink) {
			string += " Location Link";
		}
		return string;
	}
	
	function AppendToIncentiveList(data) {
		alert("adding option: " + data.name);
	}
	
	function GetOptionsForType(characterType) {
		var options = {
			0: {
				name: "Youth",
				id: 1,
				statXp: 5,
				skillXp: 0,
				meritXp: 2,
				generalXp: 5,
				characterLink: false,
				locationLink: false
			},
			1: {
				name: "Experience",
				id: 2,
				statXp: 0,
				skillXp: 0,
				meritXp: 0,
				generalXp: 10,
				characterLink: false,
				locationLink: false
			},
			2: {
				name: "Embraced by IC",
				id: 3,
				statXp: 0,
				skillXp: 0,
				meritXp: 4,
				generalXp: 0,
				characterLink: true,
				locationLink: false
			},
			3: {
				name: "Involved with Organization",
				id: 4,
				statXp: 0,
				skillXp: 3,
				meritXp: 0,
				generalXp: 0,
				characterLink: false,
				locationLink: true
			}
		};
		
		return options;
	}
	
	function ValidateAgainstTemplates(group, stat, value) {
		var isValid = true;
		
		for(var i = 0; i < character.templates.length; i++) {
			if(character.templates[i][group]) {
				if(character.templates[i][group][stat] > value) {
					isValid = false;
					$("#error-message").html(character.templates[i].name + ' does not allow for ' + stat + ' to go below ' + character.templates[i][group][stat] + '.').show(200).delay(2000).hide(200);
				}
			}
		}
		
		return isValid;
	}
	
	function CreateRequest(group, stat, value, element) {
		if(value > 0) {
			var name = stat;
			if((group !== "attribute") && (group !== "skill")) {
				name = $(element).parent().parent().find(".item-name").val();
				if($(element).parent().parent().find(".item-note").length > 0) {
					name = name + ' - ' + $(element).parent().parent().find(".item-note").val();
				}
			}
			$("#xp-request-modal-group").html(group);
			$("#xp-request-modal-item").html(name);
			$("#xp-request-modal-value").html(value);
			$("#xp-request-modal-notes").val('');
			$("#xp-request-modal-dialog").dialog({
				height: 350,
				width: 400,
				modal: true
			});
		}
	}
</script>

<?php
$page_content = ob_get_contents();
ob_end_clean();
?>