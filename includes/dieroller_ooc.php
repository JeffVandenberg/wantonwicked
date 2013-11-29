<?php
$page_title = "General Die Roller";

// test if doing an update or dice roll
if(isset($_POST['submit_die_roller']))
{
	// test to see if they are making a die roll they are making
	// they are attempting a roll, get all of the relevant details
	$character_name = htmlspecialchars($_POST['character_name']);
	$description = htmlspecialchars($_POST['action']);
	$dice = $_POST['dice'] + 0;
	$ten_again = (($_POST['reroll'] == '10again') || ($_POST['reroll'] == '9again') || ($_POST['reroll'] == '8again')) ? "Y" : "N";
	$nine_again = (($_POST['reroll'] == '9again') || ($_POST['reroll'] == '8again')) ? "Y" : "N";
	$eight_again = ($_POST['reroll'] == '8again') ? "Y" : "N";
	$one_cancel = (isset($_POST['1cancel'])) ? "Y" : "N";
	$chance_die = (isset($_POST['chance_die'])) ? "Y" : "N";
	$is_rote = (isset($_POST['is_rote'])) ? "Y" : "N";
	$used_wp = (isset($_POST['spend_willpower'])) ? "Y" : "N";
	$used_pp = (isset($_POST['spend_pp'])) ? "Y" : "N";
	
	// check for bias
	$bias = "normal";
	if(substr($description, 0, 1) == "+")
	{
		$description = substr($description, 1, strlen($description) -1);
		$bias = "high";
	}
	if(substr($description, 0, 1) == "-")
	{
		$description = substr($description, 1, strlen($description) -1);
		$bias = "low";
	}
	
	if(($used_wp == 'Y') && ($chance_die == 'N'))
	{
		$dice += 3;
	}
	
	if (($used_pp == 'Y') && ($chance_die == 'N'))
	{
		$dice += 2;
	}
	
	// validate
	$dice = ($dice < 0 ) ? -$dice : $dice;
	$dice = ($dice > 40) ? 40 : $dice;
	$dice = ($chance_die == 'Y') ? 1 : $dice;
	
	$character_name = (trim($character_name) == "") ? "Someone" : $character_name;
	$description = (trim($description) == "") ? "does something sneaky" : $description;
	
	if($dice)
	{
		$result = rollWoDDice($dice, $ten_again, $nine_again, $eight_again, $one_cancel, $chance_die, $bias, $is_rote == 'Y');
		
		$now = date('Y-m-d h:i:s');
		
		$insert_query = "insert into wod_dierolls values (null, 0, '$now', '$character_name', '$description', $dice, '$ten_again', '$nine_again', '$eight_again', '$one_cancel', '$used_wp', '$used_pp', '$result[result]', '$result[note]', $result[num_of_successes], '$chance_die', '$bias', '$is_rote');";
		
		//echo $insert_query;
		$insert_result = mysql_query($insert_query) or die(mysql_error());
	}
}


// get past rolls
$roll_query = "select * from wod_dierolls order by roll_id desc limit 20;";
$roll_result = mysql_query($roll_query) or die(mysql_error());

$rolls = <<<EOQ
<table border="0" class="normal_text" width="100%">
EOQ;

$i = 0;
while($roll_detail = mysql_fetch_array($roll_result, MYSQL_ASSOC))
{
	$row_color = (($i++)%2) ? "#443a33" : "";
	$wp = "";
	$pp = "";
	$chance = "";
	$eight_again = "";
	$nine_again = "";
	$no_ten_again = "";
	$rote_action = "";
	$ones_remove = "";
	
	if($roll_detail['Used_WP'] == 'Y')
	{
		$wp = "(WP)";
	}
	
	if($roll_detail['Used_PP'] == 'Y')
	{
		$pp = "(BP)";
	}
	
	if($roll_detail['Chance_Die'] == 'Y')
	{
		$chance = "(Chance Die)";
	}
	
	if($roll_detail['1_Cancel'] == 'Y')
	{
		$ones_remove = "(1's Remove)";
	}
	
	if($roll_detail['Is_Rote'] == 'Y')
	{
		$rote_action = "(Rote Action)";
	}
		
	if(($roll_detail['8_Again'] == 'Y') && ($roll_detail['9_Again'] == 'Y') && ($roll_detail['10_Again'] == 'Y'))
	{
		$eight_again = "(8-Again)";
	}
	
	if(($roll_detail['8_Again'] == 'N') && ($roll_detail['9_Again'] == 'Y') && ($roll_detail['10_Again'] == 'Y'))
	{
		$nine_again = "(9-Again)";
	}
	
	if(($roll_detail['8_Again'] == 'N') && ($roll_detail['9_Again'] == 'N') && ($roll_detail['10_Again'] == 'N'))
	{
		$no_ten_again = "(No 10-Again)";
	}
	
	$rolls .= <<<EOQ
<tr bgcolor="$row_color" valign="top">
	<td width="5%">
		<a href="/dieroller.php?action=view_roll&r=$roll_detail[Roll_ID]" target="_blank">Link to Roll</a>
	</td>
	<td width="35%">
		$roll_detail[Character_Name] $roll_detail[Description] <br> 
		Dice: $roll_detail[Dice] $wp $pp $chance $eight_again $nine_again $no_ten_again $ones_remove $rote_action<br>
		Time: $roll_detail[Roll_Date]
	</td>
	<td width="20%"> 
		Successes: $roll_detail[Num_of_Successes]<br> Result: $roll_detail[Note] <br>
	</td>
	<td width="40%">
		$roll_detail[Result]
	</td>
</tr>
EOQ;
}

$rolls .= "</table>";

$page_content = <<<EOQ
<div align="center">
	<font size="+1">General Wanton Wicked Die Roller</font>
</div>
<br>
<table border="0" width="100%" class="normal_text">
	<tr valign="top">
		<td align="center" width="60%">
			<form method="post" action="$_SERVER[PHP_SELF]?action=ooc">
			Name: <input type="text" name="character_name" size="20" maxlength="35" value="">
			Action: <input type="text" name="action" size="20" maxlength="50" value="">
			Dice: <input type="text" name="dice" size="3" maxlength="2" value=""><br>
			10-Again: <input type="radio" name="reroll" value="10again" checked> &nbsp;-&nbsp;
			9-Again: <input type="radio" name="reroll" value="9again"> &nbsp;-&nbsp;
			8-Again: <input type="radio" name="reroll" value="8again"> &nbsp;-&nbsp;
			No Rerolls: <input type="radio" name="reroll" value="none"> &nbsp;-&nbsp;
			1's Remove: <input type="checkbox" name="1cancel" value="y"> &nbsp;-&nbsp;
			Chance Die: <input type="checkbox" name="chance_die" value="y"> &nbsp;-&nbsp;
			Rote Action: <input type="checkbox" name="is_rote" value="y"><br>
			<input type="submit" name="submit_die_roller" value="Roll Dice/Refresh">
			</form>
		</td>
	</tr>
	<tr>
	  <td>
	    <hr>
	  </td>
	</tr>
	<tr valign="top">
		<td>
			<div align="center">
				<font size="+1">Past Rolls</font>
			</div>
			<br>
			$rolls
		</td>
	</tr>
</table>
</form>
EOQ;
