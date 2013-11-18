<?php
/* @var array $userdata */
// page_variables
$page_title = "Wanton Wicked ST Application";
$contentHeader = "ST Application";

$show_form = true;
$applicant_name = "";
$application = "";
$js = "";
$error = "";
$form = "";

include 'cgi-bin/submitPost.php';
/** @noinspection PhpIncludeInspection */
include($phpbb_root_path . 'includes/functions_posting.' . $phpEx);
/** @noinspection PhpIncludeInspection */
include($phpbb_root_path . 'includes/message_parser.' . $phpEx);


// get if they are submitting an application
if(isset($_POST['action']))
{
  $applicant_name = stripslashes(trim(htmlspecialchars($_POST['applicant_name'])));
  $application = stripslashes(trim(htmlspecialchars($_POST['application'])));
  
  $verified = true;
  if($applicant_name == "")
  {
    $verified = false;
    $error .= <<<EOQ
<span class="highlight">Please type in your name.</span><br>
EOQ;
  }
  
  if($application == "")
  {
    $verified = false;
    $error .= <<<EOQ
<span class="highlight">Please fill in your application.</span><br>
EOQ;
  }
  
  if($userdata['user_id'] == 1)
  {
    $verified = false;
    $error .= <<<EOQ
<span class="highlight">You must be signed in to submit an application.</span> <a href="http://www.wantonwicked.net/forum/ucp.php?mode=login&redirect=$_SERVER[PHP_SELF]?action=application">Sign In</a><br>
EOQ;
  }
  
  if($verified)
  {
    submitPost("New Application: $applicant_name", $application, 9);
    
		/*$userdata['user_id'] = 8;
		$userdata['username'] = "JeffV";*/

		
		// restore them back to original values
		/*$userdata['user_id'] = $temp_id;
		$userdata['username'] = $temp_name;*/
		
		$show_form = false;
		
		$form = <<<EOQ
Thank you for the application $applicant_name. Your application has been forwarded for the Head STs to review. They will be in contact with you shortly about your application.<br>
<br>
<br>
<a href="$_SERVER[PHP_SELF]">Return to Chat and Game menu</a>
EOQ;
  }
}


// present page
if($show_form)
{
  $js = <<<EOQ
<script language="JavaScript">
  function submitForm()
  {
  	var fields = "";
  	
  	if(window.document.application_form.applicant_name.value == "")
  	{
  		fields = fields + "Your Name, ";
  	}
  	
  	if(window.document.application_form.application.value == "")
  	{
  		fields = fields + "Your Application, ";
		}
		
  	// test if validated
  	if(fields == "")
  	{
  		window.document.application_form.submit();
  	}
  	else
  	{
  		fields = fields.substring(0, fields.length-2);
  		alert("Please enter the following fields : " + fields + " then click Submit");
  	}
  }
</script>
EOQ;
  $form = <<<EOQ
<form name="application_form" method="POST" action="$_SERVER[PHP_SELF]?action=application">
<table>
    <tr>
        <td width="100">
            <span class="highlight">Your Name:</span>
        </td>
        <td>
        <div align="left">
            <input type="text" name="applicant_name" id="applicant_name" value="$applicant_name" size="20" maxlength="35">
        </div>
        </td>
        <td>
              View the <a href="forum/viewtopic.php?t=24">ST Application</a>
        </td>
    </tr>
    <tr>
        <td colspan="3">
            Application: Copy and Paste here<br>
            <textarea name="application" id="application" rows="30" style="width:100%;">$application</textarea>
        </td>
    </tr>
    <tr>
        <td colspan="3">
            <div align="center">
                <input type="button" name="action" value="Submit Application" onClick="submitForm();">
            </div>
        </td>
    </tr>
</table>
</form>
EOQ;
}

$page_content = <<<EOQ
$js
$error
$form
EOQ;
?>