<?
$page_title = "Character Activity";

$past = date ( 'Y-m-d', mktime (0, 0, 0, date('m'), date('d') - 7, date('Y')));

$result_set = "";

if (!empty($_POST['start_date']))
{
	$start_date = $_POST['start_date'];
	$start_date = str_replace("/", "-", $start_date);

	$parts = explode("-", $start_date);
	if ( sizeof($parts) != 3 )
	{
		$alert = "<font color=\"red\">Please input a valid Year, Month, and Day.</font><br>";
	}
	if ($parts[0] < 2002 || $parts[1] <1 || $parts[1] >12 || $parts[2] <1 || $parts[2] >31)
	{
		if ( $parts[0] < 2002 )
		{
			$alert .= "<font color=\"red\">Years before 2002 are invalid.</font><br>";
		}
		if ( $parts[1] < 1 || $parts[0] > 12 )
		{
			$alert .= "<font color=\"red\">Please input a valid month number</font><br>";
		}
		if ( $parts[2] < 1 || $parts[2] > 31 )
		{
			$alert .= "<font color=\"red\">Please input a valid day number</font><br>";
		}
	}

	if ($alert == "")
	{
		$order_by_string = "";
		if ($_POST['order'] == 'city')
		{
			$order_by_string = "wod_characters.city, wod_characters.character_name";
		}
		if ($_POST['order'] == 'name')
		{
			$order_by_string = "login.name, wod_characters.city, wod_characters.character_name";
		}
		if ($_POST['order'] == 'last_ip_address')
		{
			$order_by_string = "login.last_ip, login.name, city, character_name";
		}

		$character_query = "select wod_characters.*, login.Name, login.Last_IP from wod_characters, login where wod_characters.last_login >= '$start_date' and is_sanctioned = 'y' and is_npc = 'n' and is_deleted='n' and wod_characters.primary_login_id = login.id order by $order_by_string;";
		$character_result = mysql_query($character_query) or die(mysql_error());

		$result_set .= mysql_num_rows($character_result) . " records found.<br>";
		if (mysql_num_rows($character_result))
		{
			if ($_POST['order'] == 'city')
			{
				$result_set = <<<EOQ
<table border="0" cellspacing="2" cellpadding="2" class="normal_text" width="100%">
	<tr bgcolor="#000000">
		<th>City</th>
		<th>Character Name</th>
		<th>Last Login</th>
		<th>Login Name</th>
		<th>Character Type</th>
		<th>Action</th>
	</tr>
EOQ;
				$city = "";
				$row = 0;

				while ($character_detail = mysql_fetch_array($character_result, MYSQL_ASSOC))
				{
					$row_color = (($row++)%2) ? "#443f33" : "";
					$temp_clan = "";
					
					if ($character_detail['City'] != $city)
					{
						$city = $character_detail['City'];
						$temp_city = $city;
					}
					$result_set .= <<<EOQ
	<tr bgcolor="$row_color">
		<td>
			$temp_city
		</td>
		<td>
		$character_detail[Character_Name]
		</td>
		<td>
		$character_detail[Last_Login]
		</td>
		<td>
		$character_detail[Name]
		</td>
		<td>
		$character_detail[Character_Type]
		</td>
		<td>
		  <a href="view_sheet.php?action=st_view_xp&view_character_id=$character_detail[Character_ID]">Look up $character_detail[Character_Name]</a>
		</td>
	</tr>
EOQ;
				}
				$result_set .= "</table>\n";
			}
			
			// sort by login name
			if ($_POST['order'] == 'name')
			{
				$result_set = <<<EOQ
<table border="0" cellspacing="2" cellpadding="2" class="normal_text" width="100%">
	<tr bgcolor="#000000">
		<th>Login Name</th>
		<th>City</th>
		<th>Character Name</th>
		<th>Last Login</th>
		<th>Character Type</th>
		<th>Action</th>
	</tr>
EOQ;

				$city = "";
				$name = "";
				$row = 0;

				while ($character_detail = mysql_fetch_array($character_result, MYSQL_ASSOC))
				{
					$row_color = (($row++)%2) ? "#443f33" : "";
					$temp_city = "";
					$temp_name = "";

					if ($character_detail['Name'] != $name)
					{
						$name = $character_detail['Name'];
						$city = "";
						$temp_name = $name;
					}

					if ($character_detail['City'] != $city)
					{
						$city = $character_detail['City'];
						$temp_city = $city;
					}
					
					$result_set .= <<<EOQ
	<tr bgcolor="$row_color">
		<td>
			$temp_name
		</td>
		<td>
			$temp_city
		</td>
		<td>
			$character_detail[Character_Name]
		</td>
		<td>
			$character_detail[Last_Login]
		</td>
		<td>
			$character_detail[Character_Type]
		</td>
		<td>
		  <a href="view_sheet.php?action=st_view_xp&view_character_id=$character_detail[Character_ID]">Look up $character_detail[Character_Name]</a>
		</td>
	</tr>
EOQ;
				}
				$result_set .= "</table>\n";
			}
			if ($_POST['order'] == 'last_ip_address')
			{
				$result_set .= <<<EOQ
<table border="0" cellspacing="2" cellpadding="2" class="normal_text" width="100%">
	<tr bgcolor="#000000">
	  <th>Last IP</th>
		<th>Login Name</th>
		<th>City</th>
		<th>Character Name</th>
		<th>Last Login</th>
		<th>Character Type</th>
		<th>Action</th>
	</tr>
EOQ;

				$city = "";
				$name = "";
				$last_ip = "";
				$row = 0;

				while ($character_detail = mysql_fetch_array($character_result, MYSQL_ASSOC))
				{
					$row_color = (($row++)%2) ? "#443f33" : "";
					$temp_city = "";
					$temp_name = "";
					$temp_last_ip = "";

					if ($character_detail['Last_IP'] != $last_ip)
					{
						$last_ip = $character_detail['Last_IP'];
						$city = "";
						$name = "";
						$temp_last_ip .= $last_ip;
					}
					
					if ($character_detail['Name'] != $name)
					{
						$name = $character_detail['Name'];
						$city = "";
						$temp_name .= $name;
					}

					if ($character_detail['City'] != $clan)
					{
						$city = $character_detail['City'];
						$temp_city .= $city;
					}
					
					$result_set .= <<<EOQ
	<tr bgcolor="$row_color">
	  <td>
	    $temp_last_ip
	  </td>
		<td>
			$temp_name
		</td>
		<td>
			$temp_city
		</td>
		<td>
			$character_detail[Character_Name]
		</td>
		<td>
			$character_detail[Last_Login]
		</td>
		<td>
			$character_detail[Character_Type]
		</td>
		<td>
		  <a href="view_sheet.php?action=st_view_xp&view_character_id=$character_detail[Character_ID]">Look up $character_detail[Character_Name]</a>
		</td>
	</tr>
EOQ;
				}
				$result_set .= "</table>\n";
			}
		}
	}
}


$date_box = <<<EOQ
<div align="center">
Pick start date in the format of YYYY-MM-DD, in numeric form.<br>
Default date is one week in the past as a start date.<br>
<form method="post" action="$_SERVER[PHP_SELF]?action=character_activity">
Start Date: <input type="text" name="start_date" id="start_date" value="$past"><br>
Group by: 
City: <input type="radio" name="order" value="city" checked>
Login Name: <input type="radio" name="order" value="name"> 
IP Address: <input type="radio" name="order" value="last_ip_address"><br>
<input type="submit">
</form>
<div>
EOQ;

$page_content = <<<EOQ
$alert
<div align="center">
$date_box
</div>
$result_set
</div>
</div>
EOQ;
?>