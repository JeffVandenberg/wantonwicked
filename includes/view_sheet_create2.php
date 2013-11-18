<?
$page_id = getPageID();

$INFORMATION_PAGE = 0;
$BIOGRAPHY_PAGE = 1;
$STATS_PAGE = 2;
$CHARACTER_TYPE_PAGE = 3;
$MERITS_PAGE = 4;
$HISTORY_PAGE = 5;
$REVIEW_PAGE = 6;

// check if updating

// display the page;
$next_id = $page_id + 1;

switch($page_id)
{
  case $INFORMATION_PAGE:
    $page_content = <<<EOQ
This is in development. Please do not proceed if you have not  been asked to go here.<br>
<br>
<a href="$_SERVER[PHP_SELF]?action=create2&page_id=$next_id">Make Character</a><br>
EOQ;
    break;
    
  case $BIOGRAPHY_PAGE:
    $page_content = <<<EOQ
Early Biographical information on the character to get an idea of the character.<br>
<br>
<a href="$_SERVER[PHP_SELF]?action=create2&page_id=$next_id">Goto next step<a>
EOQ;
    $page_content .= getBiographyInfo();
    break;
    
  case $STATS_PAGE:
    $page_content = <<<EOQ
This is where you will assign priorities and spend dots for attributes and skills.<br>
<br>
<a href="$_SERVER[PHP_SELF]?action=create2&page_id=$next_id">Goto next step<a>
EOQ;
    break;
    
  case $CHARACTER_TYPE_PAGE:
    $page_content = <<<EOQ
This is where you will pick your character type and select any relevant bonus for being a supernatural.<br>
<br>
<a href="$_SERVER[PHP_SELF]?action=create2&page_id=$next_id">Goto next step<a>
EOQ;
    break;
    
  case $MERITS_PAGE:
    $page_content = <<<EOQ
This is the page where you will select your merits and powers (if any).<br>
<br>
<a href="$_SERVER[PHP_SELF]?action=create2&page_id=$next_id">Goto next step<a>
EOQ;
    break;
    
  case $HISTORY_PAGE:
    $page_content = <<<EOQ
Finally you will fill in your character's history, goals, notes, and other relevant information to round them out.<br>
<br>
<a href="$_SERVER[PHP_SELF]?action=create2&page_id=$next_id">Goto next step<a>
EOQ;
    break;
    
  case $REVIEW_PAGE:
    $page_content = <<<EOQ
Finally you will review your character and make sure that they are what you want. You can go back to any step to make changes. However, once you accept the character's stats, they  are locked as minimums for future xp tracking during the next phase of character creation.<br>
<br>
<a href="$_SERVER[PHP_SELF]?action=create2&page_id=$next_id">Goto next step<a>
EOQ;
    break;
    
  default:
    $page_content = <<<EOQ
This is in development. Please do not proceed if you have not  been asked to go here.<br>
<br>
<a href="$_SERVER[PHP_SELF]?action=create2&page_id=1">Make Character</a><br>
EOQ;
    break;
}

// BEGIN USEFUL FUNCTIONS

// get page ID
// MAKE GLOBAL
function getPageID()
{
  $page_id = (isset($_POST['page_id'])) ? $_POST['page_id'] + 0: 0;
  $page_id = (isset($_GET['page_id'])) ? $_GET['page_id'] + 0 : $page_id;
  return $page_id;
}

// CUT TO AN INCLUDE
function getBiographyInfo()
{
  global $userdata;
  // get character information
  $character_sheet = new TempWoDCharacter();
  $character_sheet->setLoginID($userdata['user_id']);
  
  if($character_sheet->load())
  {
    // build template
    $template = new Template("/templates/");
  	$template->assign_vars(array(
    	"CHARACTER_NAME" => $character_sheet->getCharacterName(),
    	"USER_ID" => $character_sheet->getLoginID(),
    	"SEX" => $character_sheet->getSex(),
    	"CITY" => $character_sheet->getCity(),
    	"VIRTUE" => $character_sheet->getVirtue(),
    	"VICE" => $character_sheet->getVice(),
    	"AGE" => $character_sheet->getAge(),
    	"CONCEPT" => $character_sheet->getConcept(),
    	"DESCRIPTION" => $character_sheet->getDescription()
    	)
    );
    
    $do_save = false;
    $character_sheet->close($do_save);
  
    // initialize template
    $template->set_filenames(array(
    		'body' => 'templates/character_sheet_biography_layout.tpl')
    );
    
    return $template->parseNoEval('body');
  }
  else
  {
    
  }

}

// CUT TO AN INCLUDE
class TempWodCharacter
{
  var $character_name = "";
  var $login_id = 0;
  var $sex = "";
  var $age = 0;
  var $city = "";
  var $virtue = "";
  var $vice = "";
  var $concept = "";
  var $description = "";
  
  var $db_connection = "";
  
  function TempWodCharacter()
  {
    $this->db_connection = mysql_connect("localhost", "wanton00_jeffv", "dragon");
    mysql_select_db("wanton00_phpbb1", $this->db_connection) or die(mysql_error($this->db_connection));
  }
   
  function getLoginID ( )
  {
    return $this->login_id;
  }
  
  function setLoginID ( $login_id = 0 )
  {
    $this->login_id = $login_id;
  }
  
  function getCharacterName ( $edittable = false )
  {
    return $this->character_name;
  }
  
  function setCharacterName ($character_name = "")
  {
    $this->character_name = $character_name;
  }
  
  function getSex (  )
  {
    return $this->sex;
  }
  
  function setSex ( $sex = "") 
  {
    $this->sex = $sex;
  }
  
  function getAge ( )
  {
    return $this->age;
  }
  
  function setAge ( $age = 0 )
  {
    $this->age = $age;
  }
  
  function getCity ( ) 
  {
    return $this->city;
  }
  
  function setCity ( $city = "") 
  {
    $this->city = $city;
  }
  
  function getVirtue ( )
  {
    return $this->virtue;
  }
  
  function setVirtue ( $virtue = "" )
  {
    $this->virtue = $virtue;
  }
  
  function getVice ( ) 
  {
    return $this->vice;
  }
  
  function setVice ( $vice = "")
  {
    $this->vice = $vice;
  }
  
  function getConcept ( ) 
  {
    return $this->concept;
  }
  
  function setConcept ( $concept = "" )
  {
    $this->concept = $concept;
  }
  
  function getDescription ( )
  {
    return $this->description;
  }
  
  function setDescription ( $description = "" )
  {
    $this->description = $description;
  }
  
  function load()
  {
    $found_character = false;
    
    // try to pull it from the database;
    $character_query = "select * from wod_temp_characters where login_id = " . $this->login_id . ";";
    $character_result = mysql_query($character_query, $this->db_connection) or die(mysql_error($this->db_connection));
    
    // test if a login id has been set
    if($this->login_id > 0)
    {
      
      // test if there was a character or not
      if(mysql_num_rows($character_result))
      {
        // process the character fields
        echo "filling in the class properties<br>";
        $character_detail = mysql_fetch_array($character_result, MYSQL_ASSOC);
        
        $this->setCharacterName($character_detail['Character_Name']);
        $this->setSex($character_detail['Sex']);
        $this->setAge($character_detail['Age']);
        $this->setCity($character_detail['City']);
        $this->setVirtue($character_detail['Virtue']);
        $this->setVice($character_detail['Vice']);
        $this->setConcept($character_detail['Concept']);
        $this->setDescription($character_detail['Description']);
        
        $found_character = true;
      }
      else
      {
        // insert a blank record
        echo "Inserting a blank record<br>";
        $new_character_query = "insert into wod_temp_characters (login_id) values (" . $this->login_id .");";
        //$new_character_result = mysql_query($new_character_query, $this->db_connection) or die(mysql_error($this->db_connection));
        $found_character = true;
      }
    }
    return $found_character;
  }
  
  function save()
  {
    // build up the update query and process
  }
  
  function close($do_save)
  {
    if($do_save)
    {
      save();
    }
    mysql_close($this->db_connection);
  }
}
?>