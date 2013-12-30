<?
include 'common_functions.php';

$character_types = array("Mortal", "Vampire", "Werewolf", "Mage");
$character_type_select = buildSelect("Vampire", $character_types, $character_types, "character_type");

$strength_dots = makeDots("strength", 7, 1);
$dexterity_dots = makeDots("dexterity", 7, 1);
$stamina_dots = makeDots("stamina", 7, 1);

$presence_dots = makeDots("presence", 7, 1);
$manipulation_dots = makeDots("manipulation", 7, 1);
$composure_dots = makeDots("composure", 7, 1);

$intelligence_dots = makeDots("intelligence", 7, 1);
$wits_dots = makeDots("wits", 7, 1);
$resolve_dots = makeDots("resolve", 7, 1);

$alertness_dots = makeDots("alertness");
$athletics_dots = makeDots("athletics");
$brawl_dots = makeDots("brawl");
$dodge_dots = makeDots("dodge");
$empathy_dots = makeDots("empathy");
$expression_dots = makeDots("expression");
$intimidation_dots = makeDots("intimidation");
$leadership_dots = makeDots("leadership");
$streetwise_dots = makeDots("streetwise");
$subterfuge_dots = makeDots("subterfuge");

$animal_ken_dots = makeDots("animal_ken");
$crafts_dots = makeDots("crafts");
$drive_dots = makeDots("drive");
$etiquette_dots = makeDots("etiquette");
$firearms_dots = makeDots("firearms");
$melee_dots = makeDots("melee");
$performance_dots = makeDots("performance");
$security_dots = makeDots("security");
$stealth_dots = makeDots("stealth");
$survival_dots = makeDots("survival");

$academics_dots = makeDots("academics");
$computer_dots = makeDots("computer");
$finance_dots = makeDots("finance");
$investigation_dots = makeDots("investigation");
$law_dots = makeDots("law");
$linguistics_dots = makeDots("linguistics");
$medicine_dots = makeDots("medicine");
$occult_dots = makeDots("occult");
$politics_dots = makeDots("politics");
$science_dots = makeDots("science");

$power_trait_dots = makeDots("power_trait", 10, 1);
$willpower_perm_dots = makeDots("willpower_perm", 10);
$willpower_temp_dots = makeDots("willpower_temp", 10);
$humanity_dots = makeDots("humanity", 10, 7);
$blood_dots = makeDots("blood", 20, 10);
$health_dots = makeDots("health", 15);

$locations = array("New Orleans", "Denver", "Boston", "Chicago");
$location_select = buildSelect("New Orleans", $locations, $locations, "location");

$virtues = array("Charity", "Faith", "Fortitude", "Hope", "Justice", "Prudence", "Temperance");
$virtue_select = buildSelect("", $virtues, $virtues, "virtue");

$vices = array("Envy", "Gluttony", "Greed", "Lust", "Pride", "Sloth", "Wrath");
$vice_select = buildSelect("", $vices, $vices, "vice");

$clans = array("Daeva", "Gangrel", "Mekhet", "Nosferatu", "Ventrue");
$clan_select = buildSelect("", $clans, $clans, "splat1");

$covenants = array("Carthian", "Circle of the Crone", "Invictus", "Lancea Sanctum", "Ordo Dracul", "Unaligned");
$covenant_select = buildSelect("", $covenants, $covenants, "splat2");

$icon_ids = array(1000, 1001, 1002, 1003, 1004);
$icon_names = array("Daeva", "Gangrel", "Mekhet", "Nosferatu", "Ventrue");
$icon_select = buildSelect("", $icon_ids, $icon_names, "icon");

$sexes = array("Male", "Female");
$sex_select = buildSelect("", $sexes, $sexes, "sex");

?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
	<title>nWoD Vampire Character Sheet</title>
  <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
  <link rel="stylesheet" href="styles/stat_sheet_basic.css" type="text/css">
</head>
<script language="javascript">
function changeDots (tag_name, value, number_of_dots, remove)
{
	// if is the same value then set to 0
	if((value == document.getElementById(tag_name).value) && remove)
	{
		value = 0;
	}
	
	// cycle through the dots to fill up the values up to the selected value
	for(i = 1; i <= Number(number_of_dots); i++)
	{
		if(i <= value)
		{
			document.getElementById(tag_name+i).src="images/filled.gif";
		}
		else
		{
			document.getElementById(tag_name+i).src="images/empty.gif";
		}
	}
	
	document.getElementById(tag_name).value = value;
}

function updateTraits()
{
	// willpower
	var resolve = document.getElementById("resolve").value;
	var composure = document.getElementById("composure").value;
	changeDots("willpower_perm", Number(resolve)+Number(composure), 10, false);
	changeDots("willpower_temp", Number(resolve)+Number(composure), 10, false);
	
	// health
	var stamina = document.getElementById("stamina").value;
	var size = document.getElementById("size").value;
	changeDots("health", Number(stamina) + Number(size), 15, false);
	
	// defence
	var wits = document.getElementById("wits").value;
	var dexterity = document.getElementById("dexterity").value;
	var defence = wits; 
	
	if (dexterity < wits)
	{
		defence = dexterity;
	}
	document.getElementById("defence").value = defence;
	
	// initiative
	var initiative = Number(dexterity) + Number(composure); 
	document.getElementById("initiative_mod").value = initiative;
	
	// speed
	var strength = document.getElementById("strength").value;
	var speed = Number(size) + Number(strength) + Number(dexterity);
	
	document.getElementById("speed").value = speed;
}
</script>
<body bgcolor="#000000" text="#ff0000" LINK="#ff3333" VLINK="#ff0000" ALINK="#ffffff">
<form name="character_sheet" id="character_sheet" method="POST" action="<?=$_SERVER['PHP_SELF']?>">
<div align="center">
<table id="character_table">
<tr>
</tr>
<tr>
<td>
<table bgcolor="#505050" border="0" cellpadding="1" cellspacing="0" width="100%">
	<tr>
	  <td>
			<table border="0" cellpadding="2" cellspacing="1" class="normal_text" width="100%">
				<tr>
					<td bgcolor="#000000">
						<b>Name</b>
					</td>
					<td bgcolor="#000000" colspan="3">
						<input type="text" name="character_name" id="character_name" value="" size="30" maxlength="50" class="small_text">
					</td>
					<td bgcolor="#000000">
						<b>Character Type</b>
					</td>
					<td bgcolor="#000000" colspan="4">
						<?=$character_type_select?>
					</td>
				</tr>
				<tr>
					<td bgcolor="#000000">
						<b>Location</b>
					</td>
					<td bgcolor="#000000" colspan="3">
						<?=$location_select?>
					</td>
					<td bgcolor="#000000">
						<b>Sex:</b>
					</td>
					<td bgcolor="#000000" colspan="4">
						<?=$sex_select?>
					</td>
				</tr>
				<tr>
					<td bgcolor="#000000"><b> Virtue</b></td>
					<td bgcolor="#000000" colspan="3"><?=$virtue_select?></td>
					<td bgcolor="#000000"><b>Vice</b></td>
					<td bgcolor="#000000" colspan="4"><?=$vice_select?></td>
				</tr>
				<tr>
					<td bgcolor="#000000">
						<b>Clan</b>
					</td>
					<td bgcolor="#000000" colspan="3">
						<?=$clan_select?>
					</td>
					<td bgcolor="#000000">
						<b>Bloodline</b>
					</td>
					<td bgcolor="#000000" colspan="4">
						None
					</td>
				</tr>
				<tr>
					<td bgcolor="#000000">
						<b>Covenant</b>
					</td>
					<td bgcolor="#000000" colspan="3">
						<?=$covenant_select?>
					</td>
					<td bgcolor="#000000">
						<b>Icon</b>
					</td>
					<td bgcolor="#000000" colspan="4">
						<?=$icon_select?>
					</td>
				</tr>
				<tr>
					<td bgcolor="#000000">
						<b>Apparant Age</b>
					</td>
					<td bgcolor="#000000" colspan="3">
						<input type="text" name="apparent_age" id="apparent_age" value="18+" size="4" maxlength="3">
					</td>
					<td bgcolor="#000000">
						<b>True Age</b>
					</td>
					<td bgcolor="#000000" colspan="4">
						<input type="text" name="age" id="age" value="18+" size="4" maxlength="4">
					</td>
				</tr>
			</table>
		</td>
	</tr>
</table>
<table bgcolor="#505050" border="0" cellpadding="1" cellspacing="0" width="100%">
	<tr>
	  <td>
			<table border="0" cellpadding="2" cellspacing="1" class="normal_text" width="100%">
				<tr>
					<td colspan="9">
						<IMG SRC="images/empty.gif" HEIGHT="5" border="0">
					</td>
				</tr>
				<tr>
					<td bgcolor="#000000">
						<b>Concept</b>
					</td>
					<td bgcolor="#000000" colspan="8">
						<input type="text" name="concept" id="concept" value="Concept" size="50" maxlength="200">
					</td>
				</tr>
				<tr>
					<td bgcolor="#000000" valign="TOP">
						<b>Description</b>
					</td>
					<td bgcolor="#000000" valign="TOP" colspan="8">
						<input type="text" name="description" id="description" value="Description" size="50" maxlength="200">
					</td>
				</tr>
				<tr>
					<td bgcolor="#000000" valign="TOP">
						<b>URL</b>
					</td>
					<td bgcolor="#000000" valign="TOP" colspan="8">
						<input type="text" name="url" id="url" value="url" size="50" maxlength="100">
					</td>
				</tr>
				<tr>
					<td bgcolor="#000000" valign="TOP">
						<b>Daily Equipment</b>
					</td>
					<td bgcolor="#000000" valign="TOP" colspan="8">
						<input type="text" name="daily_equipment" id="daily_equipment" value="Daily Equipment" size="50" maxlength="200">
					</td>
				</tr>
				<tr>
					<td bgcolor="#000000" valign="TOP">
						<b>Other Equipment</b>
					</td>
					<td bgcolor="#000000" valign="TOP" colspan="8">
						<input type="text" name="other_equipment" id="other_equipment" value="Other Equipment" size="50" maxlength="200">
					</td>
				</tr>
				<tr>
					<td bgcolor="#000000" valign="TOP">
						<b>Haven</b>
					</td>
					<td bgcolor="#000000" valign="TOP" colspan="8">
						<input type="text" name="haven" id="haven" value="Haven" size="50" maxlength="200">
					</td>
				</tr>
				<tr>
					<td bgcolor="#000000" valign="TOP">
						<b>Exit Line</b>
					</td>
					<td bgcolor="#000000" valign="TOP" colspan="8">
						<input type="text" name="exit_line" id="exit_line" value="Exit Line" size="50" maxlength="200">
					</td>
				</tr>
			</table>
		</td>
	</tr>
</table>
<table bgcolor="#505050" border="0" cellpadding="1" cellspacing="0" width="100%">
	<tr>
	  <td>
			<table border="0" cellpadding="2" cellspacing="1" class="normal_text" width="100%">
				<tr>
					<td colspan="6">
						<IMG SRC="images/empty.gif" HEIGHT="5" border="0">
					</td>
				</tr>
				<tr>
					<td align="CENTER" colspan="6" bgcolor="#000000">
						<b>Attributes</b>
					</td>
				</tr>
				<tr>
					<td bgcolor="#000000">
						<b>
							Intelligence
						</b>
					</td>
					<td bgcolor="#000000">
						<?=$intelligence_dots?>
					</td>
					<td bgcolor="#000000">
						<b>
							Strength
						</b>
					</td>
					<td bgcolor="#000000">
						<?=$strength_dots?>
					</td>
					<td bgcolor="#000000">
						<b>
							Presence
						</b>
					</td>
					<td bgcolor="#000000">
						<?=$presence_dots?>
					</td>
				</tr>
				<tr>
					<td bgcolor="#000000">
						<b>
							Wits
						</b>
					</td>
					<td bgcolor="#000000">
						<?=$wits_dots?>
					</td>
					<td bgcolor="#000000">
						<b>
							Dexterity
						</b>
					</td>
					<td bgcolor="#000000">
						<?=$dexterity_dots?>
					</td>
					<td bgcolor="#000000">
						<b>
							Manipulation
						</b>
					</td>
					<td bgcolor="#000000">
						<?=$manipulation_dots?>
					</td>
				</tr>
				<tr>
					<td bgcolor="#000000">
						<b>
							Resolve
						</b>
					</td>
					<td bgcolor="#000000">
						<?=$resolve_dots?>
					</td>
					<td bgcolor="#000000">
						<b>
							Stamina
						</b>
					</td>
					<td bgcolor="#000000">
						<?=$stamina_dots?>
					</td>
					<td bgcolor="#000000">
						<b>
							Composure
						</b>
					</td>
					<td bgcolor="#000000">
						<?=$composure_dots?>
					</td>
				</tr>
			</table>
		</td>
	</tr>
</table>
<table bgcolor="#505050" border="0" cellpadding="1" cellspacing="0" width="100%">
	<tr>
	  <td>
			<table border="0" cellpadding="2" cellspacing="1" class="normal_text" width="100%">
				<tr>
					<td colspan="9">
						<IMG SRC="images/empty.gif" HEIGHT="5" border="0">
					</td>
				</tr>
				<tr>
					<td align="CENTER" colspan="9" bgcolor="#000000">
						<b>Abilities</b>
					</td>
				</tr>
				<tr>
					<td bgcolor="#000000">
						<b>Alertness</b>
					</td>
					<td bgcolor="#000000">
						<input type="text" name="alertness_spec" id="alertness_spec" value="" size="15" maxlength="40" class="small_text">
					</td>
					<td bgcolor="#000000">
						<?=$alertness_dots?>
					</td>
					<td bgcolor="#000000">
						<b>Animal Ken</b>
					</td>
					<td bgcolor="#000000">
						<input type="text" name="animal_ken_spec" id="animal_ken_spec" value="" size="15" maxlength="40" class="small_text">
					</td>
					<td bgcolor="#000000">
						<?=$animal_ken_dots?>
					</td>
					<td bgcolor="#000000">
						<b>Academics</b>
					</td>
					<td bgcolor="#000000">
						<input type="text" name="academics_spec" id="academics_spec" value="" size="15" maxlength="40" class="small_text">
					</td>
					<td bgcolor="#000000">
						<?=$academics_dots?>
					</td>
				</tr>
				<tr>
					<td bgcolor="#000000">
						<b>Athletics</b>
					</td>
					<td bgcolor="#000000">
						<input type="text" name="athletics_spec" id="athletics_spec" value="" size="15" maxlength="40" class="small_text">
					</td>
					<td bgcolor="#000000">
						<?=$athletics_dots?>
					</td>
					<td bgcolor="#000000">
						<b>Crafts</b>
					</td>
					<td bgcolor="#000000">
						<input type="text" name="crafts_spec" id="crafts_spec" value="" size="15" maxlength="40" class="small_text">
					</td>
					<td bgcolor="#000000">
						<?=$crafts_dots?>
					</td>
					<td bgcolor="#000000">
						<b>Computer</b>
					</td>
					<td bgcolor="#000000">
						<input type="text" name="computer_spec" id="computer_spec" value="" size="15" maxlength="40" class="small_text">
					</td>
					<td bgcolor="#000000">
						<?=$computer_dots?>
					</td>
				</tr>
				<tr>
					<td bgcolor="#000000">
						<b>Brawl</b>
					</td>
					<td bgcolor="#000000">
						<input type="text" name="brawl_spec" id="brawl_spec" value="" size="15" maxlength="40" class="small_text">
					</td>
					<td bgcolor="#000000">
						<?=$brawl_dots?>
					</td>
					<td bgcolor="#000000">
						<b>Drive</b>
					</td>
					<td bgcolor="#000000">
						<input type="text" name="drive_spec" id="drive_spec" value="" size="15" maxlength="40" class="small_text">
					</td>
					<td bgcolor="#000000">
						<?=$drive_dots?>
					</td>
					<td bgcolor="#000000">
						<b>Finance</b>
					</td>
					<td bgcolor="#000000">
						<input type="text" name="finance_spec" id="finance_spec" value="" size="15" maxlength="40" class="small_text">
					</td>
					<td bgcolor="#000000">
						<?=$finance_dots?>
					</td>
				</tr>
				<tr>
					<td bgcolor="#000000">
						<b>Dodge</b>
					</td>
					<td bgcolor="#000000">
						<input type="text" name="dodge_spec" id="dodge_spec" value="" size="15" maxlength="40" class="small_text">
					</td>
					<td bgcolor="#000000">
						<?=$dodge_dots?>
					</td>
					<td bgcolor="#000000">
						<b>Etiquette</b>
					</td>
					<td bgcolor="#000000">
						<input type="text" name="etiquette_spec" id="etiquette_spec" value="" size="15" maxlength="40" class="small_text">
					</td>
					<td bgcolor="#000000">
						<?=$etiquette_dots?>
					</td>
					<td bgcolor="#000000">
						<b>Investigation</b>
					</td>
					<td bgcolor="#000000">
						<input type="text" name="investigation_spec" id="investigation_spec" value="" size="15" maxlength="40" class="small_text">
					</td>
					<td bgcolor="#000000">
						<?=$investigation_dots?>
					</td>
				</tr>
				<tr>
					<td bgcolor="#000000">
						<b>Empathy</b>
					</td>
					<td bgcolor="#000000">
						<input type="text" name="empathy_spec" id="empathy_spec" value="" size="15" maxlength="40" class="small_text">
					</td>
					<td bgcolor="#000000">
						<?=$empathy_dots?>
					</td>
					<td bgcolor="#000000">
						<b>Firearms</b>
					</td>
					<td bgcolor="#000000">
						<input type="text" name="firearms_spec" id="firearms_spec" value="" size="15" maxlength="40" class="small_text">
					</td>
					<td bgcolor="#000000">
						<?=$firearms_dots?>
					</td>
					<td bgcolor="#000000">
						<b>Law</b>
					</td>
					<td bgcolor="#000000">
						<input type="text" name="law_spec" id="law_spec" value="" size="15" maxlength="40" class="small_text">
					</td>
					<td bgcolor="#000000">
						<?=$law_dots?>
					</td>
				</tr>
				<tr>
					<td bgcolor="#000000">
						<b>Expression</b>
					</td>
					<td bgcolor="#000000">
						<input type="text" name="expression_spec" id="expression_spec" value="" size="15" maxlength="40" class="small_text">
					</td>
					<td bgcolor="#000000">
						<?=$expression_dots?>
					</td>
					<td bgcolor="#000000">
						<b>Melee</b>
					</td>
					<td bgcolor="#000000">
						<input type="text" name="melee_spec" id="melee_spec" value="" size="15" maxlength="40" class="small_text">
					</td>
					<td bgcolor="#000000">
						<?=$melee_dots?>
					</td>
					<td bgcolor="#000000">
						<b>Linguistics</b>
					</td>
					<td bgcolor="#000000">
						<input type="text" name="linguistics_spec" id="linguistics_spec" value="" size="15" maxlength="40" class="small_text">
					</td>
					<td bgcolor="#000000">
						<?=$linguistics_dots?>
					</td>
				</tr>
				<tr>
					<td bgcolor="#000000">
						<b>Intimidation</b>
					</td>
					<td bgcolor="#000000">
						<input type="text" name="intimidation_spec" id="intimidation_spec" value="" size="15" maxlength="40" class="small_text">
					</td>
					<td bgcolor="#000000">
						<?=$intimidation_dots?>
					</td>
					<td bgcolor="#000000">
						<b>Performance</b>
					</td>
					<td bgcolor="#000000">
						<input type="text" name="performance_spec" id="performance_spec" value="" size="15" maxlength="40" class="small_text">
					</td>
					<td bgcolor="#000000">
						<?=$performance_dots?>
					</td>
					<td bgcolor="#000000">
						<b>Medicine</b>
					</td>
					<td bgcolor="#000000">
						<input type="text" name="medicine_spec" id="medicine_spec" value="" size="15" maxlength="40" class="small_text">
					</td>
					<td bgcolor="#000000">
						<?=$medicine_dots?>
					</td>
				</tr>
				<tr>
					<td bgcolor="#000000">
						<b>Leadership</b>
					</td>
					<td bgcolor="#000000">
						<input type="text" name="leadership_spec" id="leadership_spec" value="" size="15" maxlength="40" class="small_text">
					</td>
					<td bgcolor="#000000">
						<?=$leadership_dots?>
					</td>
					<td bgcolor="#000000">
						<b>Security</b>
					</td>
					<td bgcolor="#000000">
						<input type="text" name="security_spec" id="security_spec" value="" size="15" maxlength="40" class="small_text">
					</td>
					<td bgcolor="#000000">
						<?=$security_dots?>
					</td>
					<td bgcolor="#000000">
						<b>Occult</b>
					</td>
					<td bgcolor="#000000">
						<input type="text" name="occult_spec" id="occult_spec" value="" size="15" maxlength="40" class="small_text">
					</td>
					<td bgcolor="#000000">
						<?=$occult_dots?>
					</td>
				</tr>
				<tr>
					<td bgcolor="#000000">
						<b>Streetwise</b>
					</td>
					<td bgcolor="#000000">
						<input type="text" name="streetwise_spec" id="streetwise_spec" value="" size="15" maxlength="40" class="small_text">
					</td>
					<td bgcolor="#000000">
						<?=$streetwise_dots?>
					</td>
					<td bgcolor="#000000">
						<b>Stealth</b>
					</td>
					<td bgcolor="#000000">
						<input type="text" name="stealth_spec" id="stealth_spec" value="" size="15" maxlength="40" class="small_text">
					</td>
					<td bgcolor="#000000">
						<?=$stealth_dots?>
					</td>
					<td bgcolor="#000000">
						<b>Politics</b>
					</td>
					<td bgcolor="#000000">
						<input type="text" name="politics_spec" id="politics_spec" value="" size="15" maxlength="40" class="small_text">
					</td>
					<td bgcolor="#000000">
						<?=$politics_dots?>
					</td>
				</tr>
				<tr>
					<td bgcolor="#000000">
						<b>Subterfuge</b>
					</td>
					<td bgcolor="#000000">
						<input type="text" name="subterfuge_spec" id="subterfuge_spec" value="" size="15" maxlength="40" class="small_text">
					</td>
					<td bgcolor="#000000">
						<?=$subterfuge_dots?>
					</td>
					<td bgcolor="#000000">
						<b>Survival</b>
					</td>
					<td bgcolor="#000000">
						<input type="text" name="survival_spec" id="survival_spec" value="" size="15" maxlength="40" class="small_text">
					</td>
					<td bgcolor="#000000">
						<?=$survival_dots?>
					</td>
					<td bgcolor="#000000">
						<b>Science</b>
					</td>
					<td bgcolor="#000000">
						<input type="text" name="science_spec" id="science_spec" value="" size="15" maxlength="40" class="small_text">
					</td>
					<td bgcolor="#000000">
						<?=$science_dots?>
					</td>
				</tr>
			</table>
		</td>
	</tr>
</table>
<table bgcolor="#505050" border="0" cellpadding="1" cellspacing="0" width="100%">
	<tr>
	  <td>
			<table border="0" cellpadding="2" cellspacing="1" class="normal_text" width="100%">
				<tr>
					<td colspan="6">
						<IMG SRC="images/empty.gif" HEIGHT="5" border="0">
					</td>
				</tr>
				<tr>
				  <th bgcolor="#000000" colspan="6">
				  	Other Traits
				  </th>
				</tr>
				<tr>
					<td bgcolor="#000000">
						Health
					</td>
					<td bgcolor="#000000" colspan="2">
						<?=$health_dots?>
					</td>
				  <td colspan="1" bgcolor="#000000">
				    Wounds
				  </td>
				  <td colspan="2" bgcolor="#000000">
				    Bashing: 0 Lethal: 0 Agg: 0
				  </td>
				</tr>
				<tr>
					<td bgcolor="#000000">
						Blood Potency
					</td>
					<td bgcolor="#000000" colspan="2">
						<?=$power_trait_dots?>
					</td>
				  <td colspan="1" bgcolor="#000000">
				  	Size
				  </td>
				  <td colspan="2" bgcolor="#000000">
				  	<input type="text" name="size" size="3" maxlength="2" value="5">
				  </td>
				</tr>
				<tr>
				  <td colspan="1" bgcolor="#000000">
				  	Humanity
				  </td>
				  <td colspan="2" bgcolor="#000000">
				  	<?=$humanity_dots?>
				  </td>
				  <td colspan="1" bgcolor="#000000">
				  	Defence
				  </td>
				  <td colspan="2" bgcolor="#000000">
				  	<input type="text" name="defence" size="3" maxlength="2" value="0">
				  </td>
				</tr>
				<tr>
				  <td bgcolor="#000000">
				  	Willpower Perm
				  </td>
				  <td colspan="2" bgcolor="#000000">
				  	<?=$willpower_perm_dots?>
				  </td>
				  <td colspan="1" bgcolor="#000000">
				  	Initiative Mod
				  </td>
				  <td colspan="2" bgcolor="#000000">
				  	<input type="text" name="initiative_mod" id="initiative_mod" size="3" maxlength="2" value="0">
				  </td>
				</tr>
				<tr>
				  <td bgcolor="#000000">
				  	Willpower Temp
				  </td>
				  <td colspan="2" bgcolor="#000000">
				  	<?=$willpower_temp_dots?>
				  </td>
				  <td colspan="1" bgcolor="#000000">
				  	Speed
				  </td>
				  <td colspan="2" bgcolor="#000000">
				  	<input type="text" name="speed" id="speed" size="3" maxlength="2" value="0">
				  </td>
				</tr>
				<tr>
				  <td bgcolor="#000000">
				  	Blood
				  </td>
				  <td colspan="2" bgcolor="#000000">
				  	<?=$blood_dots?>
				  </td>
				  <td colspan="1" bgcolor="#000000">
				  	Armor
				  </td>
				  <td colspan="2" bgcolor="#000000">
				  	<input type="text" name="armor" id="armor" size="5" maxlength="4" value="0">
				  </td>
				</tr>
			</table>
		</td>
	</tr>
</table>
<table bgcolor="#505050" border="0" cellpadding="1" cellspacing="0" width="100%">
	<tr>
	  <td>
			<table border="0" cellpadding="2" cellspacing="1" class="normal_text" width="100%">
				<tr>
					<td colspan="9">
						<IMG SRC="images/empty.gif" HEIGHT="5" border="0">
					</td>
				</tr>
				<tr>
					<td colspan="5" bgcolor="#000000" width="50%">
						<span class="highlight">Merits/Flaws</span><br>
						<textarea rows="6" style="width:100%"></textarea>
					</td>
					<td colspan="4" bgcolor="#000000" width="60%">
						<span class="highlight">Disciplines/Rituals/Devotions/Other</span><br>
						<textarea rows="6" style="width:100%"></textarea>
					</td>
				</tr>
				<tr>
					<td colspan="5" bgcolor="#000000">
						<span class="highlight">Goals & History</span><br>
						<textarea rows="8" style="width:100%"></textarea>
					</td>
					<td colspan="4" bgcolor="#000000">
						<span class="highlight">Personal Notes</span><br>
						<textarea rows="8" style="width:100%"></textarea>
					</td>
				</tr>
			</table>
		</td>
	</tr>
</table>
<table bgcolor="#505050" border="0" cellpadding="1" cellspacing="0" width="100%">
	<tr>
	  <td>
			<table border="0" cellpadding="2" cellspacing="1" class="normal_text" width="100%">
				<tr>
					<td colspan="9">
						<IMG SRC="images/empty.gif" HEIGHT="5" border="0">
					</td>
				</tr>
				<tr>
				  <td colspan="9" bgcolor="#000000">
				  	Login Note to Player:
				  	<input type="text" name="login_note" id="login_note" size="50" maxlength="250" class="small_text" style="width:100%">
				  </td>
				</tr>
				<tr>
					<td bgcolor="#000000">
						Experience
					</td>
					<td bgcolor="#000000">
						<input type="textbox" name="experience" id="experience" value="1.5" size="5" maxlength="5" class="small_text">
					</td>
					<td bgcolor="#000000">
						Head Sanctioned
					</td>
					<td bgcolor="#000000">
						Yes:
						<input type="radio" name="head_sanctioned"><br>
						No:
						<input type="radio" name="head_sanctioned">
					</td>
					<td bgcolor="#000000">
						ST Sanctioned
					</td>
					<td bgcolor="#000000">
						Yes:
						<input type="radio" name="st_sanctioned"><br>
						No:
						<input type="radio" name="st_sanctioned">
					</td>
					<td bgcolor="#000000">
						Asst Sanctioned
					</td>
					<td bgcolor="#000000" colspan="2">
						Yes:
						<input type="radio" name="asst_sanctioned"><br>
						No:
						<input type="radio" name="asst_sanctioned">
					</td>
				</tr>
				<tr class="small_text">
					<td bgcolor="#000000">
						Last ST Updated
					</td>
					<td bgcolor="#000000">
						JeffV
					</td>
					<td bgcolor="#000000">
						When ST Updated
					</td>
					<td bgcolor="#000000">
						2004-08-13<br>17:35:42
					</td>
					<td bgcolor="#000000">
						Last Asst Updated
					</td>
					<td bgcolor="#000000">
						Raistlin
					</td>
					<td bgcolor="#000000">
						When Asst Updated
					</td>
					<td bgcolor="#000000" colspan="2">
						2004-08-13<br>18:42:02
					</td>
				</tr>
				<tr class="small_text">
					<td bgcolor="#000000">
						First Login
					</td>
					<td bgcolor="#000000">
						2004-07-15<br>13:01:15
					</td>
					<td bgcolor="#000000">
						Last Login
					</td>
					<td bgcolor="#000000">
						2004-08-15<br>17:32:42
					</td>
					<td bgcolor="#000000">
						First IP
					</td>
					<td bgcolor="#000000">
						204.15.169.166
					</td>
					<td bgcolor="#000000">
						Last IP
					</td>
					<td bgcolor="#000000" colspan="2">
						204.168.147.141
					</td>
				</tr>
				<tr>
					<td colspan="4" bgcolor="#000000">	
						Sheet Updates<br>
						Past Updates<br>
						<textarea name="old_updates" cols="50" rows="7" class="small_text" style="width:100%"></textarea>
					</td>
					<td rowspan="2" bgcolor="#000000">
					</td>
					<td colspan="4" bgcolor="#000000">
						ST Notes<br>
						Past Notes<br>
						<textarea name="old_notes" cols="50" rows="7" class="small_text" style="width:100%"></textarea>
					</td>
				</tr>
				<tr>
					<td colspan="4" bgcolor="#000000">	
						New Updates<br>
						<textarea name="new_updates" cols="50" rows="7" class="small_text" style="width:100%"></textarea>
					</td>
					<td colspan="4" bgcolor="#000000">
						New Notes<br>
						<textarea name="old_notes" cols="50" rows="7" class="small_text" style="width:100%"></textarea>
					</td>
				</tr>
				<tr>
					<td colspan="9" bgcolor="#000000" align="center">
						<input type="submit" name="Update" id="Update" value="Update">
					</td>
				</tr>
			</table>
		</tr>
	</td>
</table>
</td>
</tr>
</table>
</div>
</form>
<b1><!--Pngtw_FUeNoNy0EOgCAMBMAfURFj4z+8G8SNGEMh2tjvy2lOQ0WU1vic0MFTCH7mzrSMzGRmzqJoFbvSjcMJlAw7pSqK/r5Y2vZmQF3L7QeZLhn2--></b1></body>
</html>