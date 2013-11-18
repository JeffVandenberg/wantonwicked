<?
include 'cgi-bin/makeDotsXP.php';

$index = $_GET['index'] +0;
$type = $_GET['type'];
$character_type = $_GET['character_type'];
$edit_xp = ($_GET['edit_xp'] == 'true');
$element_type = $_GET['element_type'];

$fragment = "";

switch($type)
{
  case 'merit':
    $edit_powers = true;
  	$merit_dots = makeDotsXP("merit${index}", $element_type, $character_type, 7, 0, $edit_powers, false, $edit_xp);
  	
  	$fragment .= <<<EOQ
<input type="text" name="merit${index}_name" id="merit${index}_name" size="15" class="normal_input">
<input type="text" name="merit${index}_note" id="merit${index}_note" size="20" maxlength="30" class="normal_input">
$merit_dots
<input type="hidden" name="merit${index}_id" id="merit${index}_id" value="0">
<br>
EOQ;
    break;
    
  case 'specialty':
    $js = "";
    if($edit_xp)
    {
      $js = " onChange=\"updateXP($element_type)\" ";
    }
    $skill_list_proper = array("Academics", "Computer", "Crafts", "Investigation", "Medicine", "Occult", "Politics", "Science", "Athletics", "Brawl", "Drive", "Firearms", "Larceny", "Stealth", "Survival", "Weaponry", "Animal Ken", "Empathy", "Expression", "Intimidation", "Persuasion", "Socialize", "Streetwise", "Subterfuge");
    $specialties_dropdown = buildSelect("", $skill_list_proper, $skill_list_proper, "skill_spec${index}_selected", "class=\"normal_input\"");
    
    $fragment = <<<EOQ
$specialties_dropdown 
<input type="text" name="skill_spec${index}" id="skill_spec${index}" class="normal_input" $js>
<input type="hidden" name="skill_spec${index}_id" id="skill_spec${index}_id" value="0">
<br>
EOQ;
    break;

  case 'icdisc':
  	$discipline_dots = makeDotsXP("icdisc${index}", $element_type, $character_type, 7, 0, true, false, $edit_xp);
  	
  	$fragment .= <<<EOQ
<input type="text" name="icdisc${index}_name" id="icdisc${index}_name" size="15" class="normal_input">
$discipline_dots
<input type="hidden" name="icdisc${index}_id" id="icdisc${index}_id" value="0">
<br>
EOQ;
    break;

  case 'oocdisc':
  	$discipline_dots = makeDotsXP("oocdisc${index}", $element_type, $character_type, 7, 0, true, false, $edit_xp);
  	
  	$fragment .= <<<EOQ
<input type="text" name="oocdisc${index}_name" id="oocdisc${index}_name" size="15" class="normal_input">
$discipline_dots
<input type="hidden" name="oocdisc${index}_id" id="oocdisc${index}_id" value="0">
<br>
EOQ;
    break;
    
  case 'devotion':
    $supernatural_xp_js = "";
    if($edit_xp)
    {
      $supernatural_xp_js = " onChange=\"updateSupernaturalXP($element_type)\" ";
    }
    
    $fragment .= <<<EOQ
<input type="text" name="devotion${index}_name" id="devotion${index}_name" size="15" class="$input_class">
<input type="text" name="devotion{$index}_cost" id="devotion{$index}_cost" size="3" maxlength="2" class="normal_input" $supernatural_xp_js>
<input type="hidden" name="devotion${index}_id" id="devotion${index}_id" value="0">
<br>
EOQ;
  
  case 'vampattselect':
    $fragment = <<<EOQ
-- Bonus Attribute:
<select name="bonus_attribute_select" id="bonus_attribute_select" onChange="displayBonusDot();">
</select>
EOQ;
    break;
    
  default:
}

echo $fragment;
die();
?>