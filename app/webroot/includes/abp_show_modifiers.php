<?php
use classes\character\repository\CharacterRepository;

include_once 'includes/classes/character/character.php';

$characterId = $_GET['character_id'] + 0;

$abp = new ABP();
$baseAbp = $abp->GetBaseABP($characterId);
$extraDomains = $abp->GetExtraDomains($characterId);
$otherModifiers = $abp->GetOtherModifiers($characterId);
$humanityModifier = $abp->GetHumanityModifier($characterId);

$repository = new CharacterRepository();
$characterDetail = $character->GetById($characterId);


$totalABP = $baseAbp + $extraDomains + $humanityModifier;
$otherModifiersList = "";
foreach($otherModifiers as $modifier)
{
	$name = $modifier->GetModifierName();
	$value = $modifier->GetModifierValue();
	
	$totalABP += $value;
	
	$verb = ($value < 0) ? "removes" : "adds";
	$value = abs($value);
	$otherModifiersList .= <<<EOQ
$name which $verb $value to your ABP.<br />
EOQ;
}

$page_title = "Your ABP Modifiers";

$page_content = <<<EOQ
<div class="paragraph">
Breakdown of ABP Modifiers for $characterDetail[character_name]:
</div>

<div class="paragraph">
<label>
	Base from Tenancy
</label>
$baseAbp
<label>
	Extra Domains where you have Tenacy
</label>
$extraDomains
<label>
Humanity Modifier
</label>
$humanityModifier
<label>
	Other Modifiers
</label>
$otherModifiersList
</div>

<div class="paragraph">
	Final ABP Value: $totalABP
</div>
EOQ;
?>