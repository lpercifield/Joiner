<!DOCTYPE html>
<?php
 	
 	if( isset($_GET['Message']) && isset($_GET['To']) ):
 			echo $_GET['Message'];
 		 	$user = $_GET['To'];
 			$message = $_GET['Message'];
 			$valid = true;
 			
 			echo 'junk';
 			
 	else:
 	
 		    $valid = false;
 		    echo 'oops';
 	
 	endif;
 	

 	echo 'more junk';
 	
 	
    // include the PHP TwilioRest library
    //require "includes/Services/Twilio.php";
    require "includes/functions.php";
 
    // twilio REST API version
    //$ApiVersion = "2010-04-01";
 
    // set our AccountSid and AuthToken
    $AccountSid = $TWIL_SID;
    $AuthToken = $TWIL_KEY;
    
    echo 'so much junk';
    
    // instantiate a new Twilio Rest Client
    $client = new Services_Twilio($AccountSid, $AuthToken);
 
    // make an associative array of people we know, indexed by phone number
    if($valid === true):
	
		echo '<br>sending<br>'.$user;
	
	$sms = $client->account->sms_messages->create(
		$TWIL_NUMBER,
		$user,
		$message
	);
		//print $sms->sid;	
		echo "Message sent!";
		
 	else:
 	
 		echo "did not send";
 	
    endif;
?>