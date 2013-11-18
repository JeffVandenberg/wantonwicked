<?
function verifyDate ( $year, $month, $day )
{
	// set up return function
	$return_value['verified'] = false;
	$return_value['message'] = "";
	$return_value['date'] = "";


  $year = $year + 0;
	$month = $month + 0;
	$day = $day + 0;

	// account for 2 digit year
	$year = ( $year < 10 ) ? 2000 + $year : $year;
	$year = ( $year < 100 ) ? 1900 + $year : $year;

	$year_check = true;
	$month_check = true;
	$day_check = true;

	if ( $month < 1 || $month > 12 )
	{
  	$return_value['message'] .= "<div class=\"red_highlight\">Please enter a valid Month.</div>\n";
  	$month_check = false;
	}

	$days_of_month = 0;

	if ( $month_check ) // have to make sure
	{
  	switch($month)
  	{
    	case 1:
    	case 3:
    	case 5:
    	case 7:
    	case 8:
    	case 10:
    	case 12:
    	  $days_of_month = 31;
    	  break;
    	case 4:
    	case 6:
    	case 9:
    	case 11:
    	  $days_of_month = 30;
    	  break;
    	case 2:
    	  $days_of_month = 28;
    	  break;
    	default;
    	  $days_of_month = 0;
    	  break;
    }
  }
	if ( $day < 1 || $day > $days_of_month )
	{
  	$return_value['message'] .= "<div class=\"red_highlight\">Please enter a valid Day.</div>\n";
  	$day_check = false;
	}

  if ( $day_check && $month_check && $year_check )
  {
	  $return_value['verified'] = true;
	  $return_value['date'] = "${year}-${month}-${day}";
  }

  return $return_value;
}
?>