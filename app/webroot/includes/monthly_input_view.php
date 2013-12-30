<?
$page_title = "Submit Monthly Input";

/*while(list($key, $value) = each($_POST))
{
	echo "POST: $key: $value<br>";
}*/

// try to get id
$input_id = (isset($_POST['input_id'])) ? $_POST['input_id'] +0 : 0;
$input_id = (isset($_GET['input_id'])) ? $_GET['input_id'] +0 : $input_id;

// what page are people on and which they have come from
// -1 = cancel, 0 = intro, 1 & 2 = info, 3 = review , 4 = submit
$this_page = (isset($_POST['this_page'])) ? $_POST['this_page'] : "Page 0";
$last_page = (isset($_POST['last_page'])) ? $_POST['last_page'] : "Page 0";

// get character id
$character_id = (isset($_POST['character_id'])) ? $_POST['character_id'] +0 : 0;
$character_id = (isset($_GET['character_id'])) ? $_GET['character_id'] +0 : $character_id;

// validate that we are working on an actual character
$character_query = "select wod.*, l.* from (wod_characters as wod inner join login_character_index as lci on wod.character_id = lci.character_id) inner join login as l on lci.login_id = l.id where wod.character_id = $character_id and lci.login_id = $userdata[user_id];";
$character_query = "select wod.*, l.* from (wod_characters as wod inner join login_character_index as lci on wod.character_id = lci.character_id) inner join login as l on lci.login_id = l.id where wod.character_id = 1;";
$character_result = mysql_query($character_query) or die(mysql_error());

if(mysql_num_rows($character_result))
{
	$character_detail = mysql_fetch_array($character_result, MYSQL_ASSOC);
}
else
{
	die('invalid character request.');
}

if($input_id == 0)
{
	// create a new record and save the ID
	$input_detail = "";
	$input_detail['num_recommendations'] = 2;
}


// update information
switch($last_page)
{
	default:
		break;
}

// determine which page to show
switch($this_page)
{
	case "Page 0":
		$page_content .= <<<EOQ
<span class="highlight">Intro Page</span><br>
<br>
This is the monthly character input form, which helps the STs keep up to date with what is going on with your characters. It also helps to form part of the feedback loop for the site, so that way we can know what players are thinking.<br>
<br>
<form method="post" action="$_SERVER[PHP_SELF]?action=view">
<input type="hidden" name="input_id" value="$input_id">
<input type="hidden" name="last_page" value="Page 0">
<input type="hidden" name="character_id" value="$character_id">
<input type="submit" name="this_page" value="Page 1">
</form>
EOQ;
		break;
		
	case "Page 1":
		// Process the proper number of recommendation
		$choices = array(0,1,2,3,4,5,6);
		$num_recommendations = buildSelect($input_detail['Num_Recommendations'], $choices, $choices, "num_recommendations");
	  $page_3_button = "";
	
	  if ($input_detail['reviewed'] == 'Y')
	  {
	    $page_3_button = <<<EOQ
<input type="submit" name="this_page" value="Page 3">
EOQ;

	  }
		$page_content .= <<<EOQ
<span class="highlight">Monthly Character Report Page (1 of 3)</span><br>
<form method="post" action="$_SERVER[PHP_SELF]?action=view">
<table border="0" cellpadding="2" cellspacing="3" width="100%" class="normal_text">
  <tr>
    <td width="50%">
      <span class="highlight">Character Name</span>: $character_detail[Character_Name]
    </td>
    <td width="50%">
      <span class="highlight">Profile Name</span>: $character_detail[Name]
    </td>
  </tr>
  <tr>
    <td>
      <span class="highlight">Character Type</span>: $character_detail[Character_Type]
    </td>
    <td>
      <span class="highlight">Email</span>: $character_detail[Email]
    </td>
  </tr>
</table>
<table border="0" cellpadding="2" cellspacing="3" width="100%" class="normal_text">
  <tr>
    <td colspan="5">
      <span class="highlight">Character Development Questions</span>
    </td>
  </tr>
  <tr>
    <th>
      <span class="highlight">Question</span>
    </th>
    <th>
      <span class="highlight">Yes</span>
    </th>
    <th>
      <span class="highlight">No</span>
    </th>
    <th>
      <span class="highlight">If yes:</span>
    </th>
    <th>
      <span class="highlight">Fill out below if Yes.</span>
    </th>
  </tr>
  <tr valign="top">
    <td>
      Did your character learn something fundamentally new?
    </td>
    <td>
      <input type="radio" name="dev_1" value="Y" $dev_1_yes>
    </td>
    <td>
      <input type="radio" name="dev_1" value="N" $dev_1_no>
    </td>
    <td>
      What sort of resourcefulness was used?<br>
      What did your character learn?<br>
      How did your character learn this?
    </td>
    <td>
      <textarea name="dev_1_text" rows="5" cols="40" wrap="physical">$dev_1_text</textarea>
    </td>
  </tr>
  <tr valign="top">
    <td>
      Did you appropriately play your character, concept, Merits/Flaws?
    </td>
    <td>
      <input type="radio" name="character" value="Y" $dev_2_yes>
    </td>
    <td>
      <input type="radio" name="character" value="N" $dev_2_no>
    </td>
    <td>
      <b>Concept:</b> <i>$character_detail[Concept]</i><br>
      <hr>
      What have you done to live up to your concept?<br>
      How have your Merits and Flaws helped or hendered you?
    </td>
    <td>
      <textarea name="dev_2_text" rows="5" cols="40" wrap="physical">$dev_2_text</textarea>
    </td>
  </tr>
  <tr valign="top">
    <td>
      Did your character have any major social interactions?
    </td>
    <td>
      <input type="radio" name="dev_3" value="Y" $dev_3_yes]>
    </td>
    <td>
      <input type="radio" name="dev_3" value="N" $dev_3_no]>
    </td>
    <td>
      How have you supported your Clan in Court?<br>
      How have you been a credit to Family and Clan?<br>
      <hr>
      Ronin: How have you ensured your continued survival?<br>
    </td>
    <td>
      <textarea name="dev_3_text" rows="5" cols="40" wrap="physical">$dev_3_text</textarea>
    </td>
  </tr>
  <!--
  <tr>
    <td colspan="5">
      <span class="highlight">WW: Potential Bonus System</span>
    </td>
  </tr>
  <tr>
    <td colspan="5">
    	Don't know if we want something here for WantonWicked (Worth 1-3 additional XP)<br>
      Example from FRO L5R: This will be a way to potentially gain a rank in a skill for free. there will be more posted about this later. but if you demonstrate that you have done enough to train a skill, learn it from someone else, have demonstrated good use of the skill and such. A GM can give you a rank in a skill, once a month, for free. This can only be done via this page.  Say what Level of the skill you are seeking? What have you done to justify learning them?
    </td>
  </tr>
  -->
  <tr>
    <td colspan="5" align="center">
      <textarea name="skill_advance" rows="7" cols="90" wrap="physical">$_SESSION[skill_advancement_message]</textarea>
    </td>
  </tr>
  <tr>
    <td colspan="5">
      <span class="highlight">Extra Credit Awards</span>
    </td>
  </tr>
  <tr>
    <td colspan="5">
      Did you submit any extra credit this month for Wanton Wicked? This includes artwork for the sites, fiction, or articles. Also, were you awarded any XP by STs during the course of the month for a scene?
    </td>
  </tr>
  <tr>
    <td colspan="5" align="center">
      <textarea name="extra_credit" rows="7" cols="90" wrap="physical">$_SESSION[extra_credit]</textarea>
    </td>
  </tr>
  <tr>
    <td colspan="5">
      <span class="highlight">Player Award Recommendations</span> (Part 1)
    </td>
  </tr>
  <tr>
    <td colspan="5">
      How many players will you be recommending for XP bonuses for particularly good roleplaying on page 2?
      $num_recommendations
    </td>
  </tr>
</table>
<table border="0" cellpadding="2" cellspacing="3" width="100%" class="normal_text">
  <tr align="center">
    <td align="center">
			<input type="hidden" name="input_id" value="$input_id">
			<input type="hidden" name="last_page" value="Page 1">
			<input type="hidden" name="character_id" value="$character_id">
			<input type="submit" name="this_page" value="Page 2">
			$page_3_button
			<input type="submit" name="this_page" value="Cancel">
		</td>
  </tr>
</table>
</form>
EOQ;
		break;
		
	case "Page 2":
		$recommendations = "";
		$recommendation_parts = explode("+++", $input_detail['Recommendations']);
		
		for($i = 0; $i < 2 /*$input_detail['Num_Recommendations']*/; $i++)
		{
			$temp_name = $recommendation_parts[$i*2];
			$temp_message = $recommendation_parts[$i*2+1];
			$recommendations .= <<<EOQ
	<tr>
	  <td align="center">
	    <input name="char_name$i" type="text" size="20" maxlength="35" value="$temp_name">
	  </td>
	  <td align="center">
	    <textarea name="rec_message$i" rows="4" cols="50">$temp_message</textarea>
	  </td>
	</tr>
EOQ;
		}
		
		if($recommendations == "")
		{
			$recommendations = <<<EOQ
	<tr>
		<td colspan="2">
			You're not making any recommendations
		</td>
	</tr>
EOQ;
		}
		
		$page_content .= <<<EOQ
<span class="highlight">Character Experience Submission Page (2 of 3)</span><br>
<form method="post" action="$_SERVER[PHP_SELF]?action=view">
<table border="0" cellpadding="2" cellspacing="3" width="100%" class="normal_text">
  <tr>
    <td width="50%">
      <span class="highlight">Character Name</span>: $character_detail[Character_Name]
    </td>
    <td width="50%">
      <span class="highlight">Profile Name</span>: $character_detail[Name]
    </td>
  </tr>
  <tr>
    <td>
      <span class="highlight">Character Type</span>: $character_detail[Character_Type]
    </td>
    <td>
      <span class="highlight">Email</span>: $character_detail[Email]
    </td>
  </tr>
</table>
<table border="0" cellpadding="2" cellspacing="3" width="100%" class="normal_text">
  <tr>
    <th colspan="3">
      Player Recommendations
    </th>
  </tr>
  <tr>
    <th>
      Character Name
    </th>
    <th>
      Comments
    </th>
  </tr>
	$recommendations
  <tr>
    <td colspan="3">
      <span class="highlight">Storytellers Questions</span><br>
      This section of questions is for the benefit of your Storytellers and is
      optional. However, it does make your STss more likely to do things for
      you and your character because they will know what you have done and
      your thoughts on the game. There is no need to make obscenely long
      posts in here, however. Express what needs to be said, and nothing more.
    </td>
  </tr>
  <tr>
    <td colspan="3" align="center">
      What were the major events that happened to your character this month?
    </td>
  </tr>
  <tr>
    <td colspan="3" align="center">
      <textarea name="events_text" rows="7" cols="90" wrap="physical">$_SESSION[events_message]</textarea>
    </td>
  </tr>
  <tr>
    <td colspan="3" align="center">
      What sort of internalizations or inner thoughts were a focus this month from these events?<br>
      This is where you would place your character's opinions and thoughts on the events of the month.
    </td>
  </tr>
  <tr>
    <td colspan="3" align="center">
      <textarea name="thoughts_text" rows="7" cols="90" wrap="physical">$_SESSION[thoughts_message]</textarea>
    </td>
  </tr>
  <tr>
    <td colspan="3" align="center">
      What were your primary social character interactions this month?<br>
      This allows you to expand on the previous page as need be.<br>
      What have you done to further your clan? What interactions have you had with other clans?
    </td>
  </tr>
  <tr>
    <td colspan="3" align="center">
      <textarea name="social_text" rows="7" cols="90" wrap="physical">$_SESSION[social_message2]</textarea>
    </td>
  </tr>
  <tr>
    <td colspan="3" align="center">
      What are your character's short-term and long-term goals?<br>
      If any goals were reached this month, how did you  do it?<br>
      Did it change your character's outlook?
    </td>
  </tr>
  <tr>
    <td colspan="3" align="center">
      <textarea name="goals_text" rows="7" cols="90" wrap="physical">$_SESSION[goals_message]</textarea>
    </td>
  </tr>
  <tr>
    <td colspan="3" align="center">
      What are your thoughts as a player?<br>
      Is there something you want to see in the course of play?<br>
      Some idea for a plot? Something that does or does not work well?
    </td>
  </tr>
  <tr>
    <td colspan="3" align="center">
      <textarea name="misc_text" rows="7" cols="90" wrap="physical">$_SESSION[misc_message]</textarea>
    </td>
  </tr>
  <tr>
    <td colspan="3" align="center">
			<input type="hidden" name="input_id" value="$input_id">
			<input type="hidden" name="last_page" value="Page 1">
			<input type="hidden" name="character_id" value="$character_id">
      <input type="submit" name="this_page" value="Page 1">
      <input type="submit" name="this_page" value="Page 3">
      <input type="submit" name="this_page" value="Cancel">
    </td>
  </tr>
</table>
</form>
EOQ;
		break;
		
	case 3:
	case 4:
}

?>