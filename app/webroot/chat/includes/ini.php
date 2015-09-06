<?php

/*
* set the error reporting level
* http://uk.php.net/error_reporting
*/

error_reporting(E_ALL & ~E_NOTICE);

/*
* enable display errors
* set to 0 for production servers 
* 0 Off - 1 On
*/


/*
* enable log errors
* 0 Off - 1 On
*/


/*
* if error logging enabled above
* file to save error messages too
*/


/*
* Sets the default timezone used by all date/time functions in a script
* PHP 5 >= 5.1.0
* http://php.net/manual/en/function.date-default-timezone-set.php
*/

if(phpversion() >= '5.1.0')
{
	date_default_timezone_set("America/Chicago");
}