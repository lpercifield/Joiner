<?php 

// include the PHP TwilioRest library
require "includes/Services/Twilio.php";


require "keys.php";

//Twilio functions

function sendSMS($number, $message){

		require "keys.php";
		// set our AccountSid and AuthToken
		$AccountSid = $TWIL_SID;
		$AuthToken = $TWIL_KEY;
		
		// instantiate a new Twilio Rest Client
	    $client = new Services_Twilio($AccountSid, $AuthToken);
	 
	    //error_log("From functions before sms");
		//send back sms
		$sms = $client->account->sms_messages->create(
			$TWIL_NUMBER,
			$number,
			$message
		);


}


//mongo functions
//written by c.piuggi

//make auth connection to Collection
//returns collection object

function mongoCollection($u, $p, $d, $c ){

	$key = 'mongodb://'.$u.':'.$p.'@localhost/'.$d;
	
	try{
	
		//connect w. our mongo key		
		$mongo = new Mongo($key);
		//$mongo = connect();
		
		} catch(Exception $e) {
		
			echo 'Caught exception:', $e->getMessage(), "\n";
	}
		
		
	//set our database
	$db = $mongo->$d;

	//set our collection
	$collection = $db->$c;
	
	return $collection;

}


?>