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
		//$tropoKey = $TROPOAPIKEY;
		
		//http://api.tropo.com/1.0/sessions?action=create&token=1234&to=12125551212&msg=I+feel+pretty
		//header("Location: http://api.tropo.com/1.0/sessions?action=create&token=$tropokey&to=$number&msg=$message");
		//$ch = curl_init('http://api.tropo.com/1.0/sessions?action=create&token=$tropokey&to=$number&msg=$message');
 		//curl_exec ($ch);
 		//curl_close ($ch);


}


//mongo functions
//written by c.piuggi

//make auth connection to Collection
//returns collection object

function mongoCollection($u, $p, $d, $c ){

	$key = 'mongodb://'.$u.':'.$p.'@localhost/'.$d;
	
	try{
	
		//connect w. our mongo key	
		error_log("attempting to connect to DB");
		$mongo = new Mongo($key);
		//$mongo = connect();
		
		} catch(Exception $e) {
		
			echo 'Caught exception:', $e->getMessage(), "\n";
	}
		
		
	//set our database
	$db = $mongo->$d;
	//error_log($c);
	//set our collection
	$collection = $db->$c;
	error_log("this is our collection");
	error_log($collection);
	
	return $collection;

}

// afunction to log SMS 

function logSMS($group, $message) {
	
	//error_log("attempting to log");
	require "keys.php";

	$un = $DB_ID;
	$pw = $DB_KEY;
	
	$db = "texts";
	$col = "messages";
	error_log("attempting to log");
	//error_log($col);
	
	$mcollection = mongoCollection( $un, $pw, $db, $col );
	
	$safe_insert = true;
	
	$joinGroup = array(
		
		"group_id" => $group
		
	);
		
		
	$content = array(
		
				'$push' => array(
						
						"messages" => array(
						
								"message" => $message,
								"timestamp" => new MongoDate()
							
							)
				
				 )
		
	);
	
	
	
	$upsert = array(
	
		"upsert" => $safe_insert
	
	);
	
		//error_log("really trying");
	
	try{
	
		//connect w. our mongo key		
		$mcollection -> update($joinGroup, $content, $upsert );
		//error_log("complete");
		
		} catch(Exception $e) {
		
			echo 'Caught exception:', $e->getMessage(), "\n";
	}
	
}




?>