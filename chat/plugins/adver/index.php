<?php

/*
* update click thros
*
*/ 

if(isset($_POST['clickID']) && is_numeric($_POST['clickID']))
{
	// include files
	include("../../includes/functions.php");

	// update display count
	try {
		$dbh = db_connect();
		$params = array(
		'clickID' => makeSafe($_POST['clickID'])
		);
		$query = "UPDATE prochatrooms_adverts 
				  SET clicks = clicks+1 
				  WHERE id = :clickID
				  ";							
		$action = $dbh->prepare($query);
		$action->execute($params);	
		$dbh = null;
	}
	catch(PDOException $e) 
	{
		$error  = "Function: ".__FUNCTION__."\n";
		$error .= "File: ".basename(__FILE__)."\n";	
		$error .= 'PDOException: '.$e->getCode(). '-'. $e->getMessage()."\n\n";

		debugError($error);
	}	

	return;
}

/*
* show banner adverts
*
*/ 

function displayAd()
{
	$html = '';
	
	// include files
	include("../../includes/functions.php");

	// get advert
	try {
		$dbh = db_connect();
		$params = array('');
		$query = "SELECT id, text  
				  FROM prochatrooms_adverts 
				  ORDER BY RAND() 
				  LIMIT 1
				  ";							
		$action = $dbh->prepare($query);
		$action->execute($params);
		
		$html = '';		
					
		foreach ($action as $i) 
		{
			$id = $i['id'];

			$i['text'] = str_replace("<img","<img border='0'",$i['text']);

			$html .= "<body style=\"margin: 0 0 0 0; overflow: hidden;\">";
			$html .= "<div style=\"border: 0px solid #84B2DE;\" onclick=\"parent.adverClick('".$i['id']."')\">".stripslashes($i['text'])."<div>";	
		}
		
		$dbh = null;
	}
	catch(PDOException $e) 
	{
		$error  = "Function: ".__FUNCTION__."\n";
		$error .= "File: ".basename(__FILE__)."\n";	
		$error .= 'PDOException: '.$e->getCode(). '-'. $e->getMessage()."\n\n";

		debugError($error);
	}		

	// update display count
	if(isset($id))
	{
		try {
			$dbh = db_connect();
			$params = array(
			'id' => $id
			);
			$query = "UPDATE prochatrooms_adverts 
					  SET displays = displays+1 
					  WHERE id = :id
					  ";							
			$action = $dbh->prepare($query);
			$action->execute($params);	
			$dbh = null;
		}
		catch(PDOException $e) 
		{
			$error  = "Function: ".__FUNCTION__."\n";
			$error .= "File: ".basename(__FILE__)."\n";	
			$error .= 'PDOException: '.$e->getCode(). '-'. $e->getMessage()."\n\n";

			debugError($error);
		}	
	}
	return $html;
}

echo displayAd();

?>