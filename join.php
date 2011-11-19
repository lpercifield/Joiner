<?php 

	require_once("includes/functions.php"); 
	require_once("includes/keys.php");

	$un = $DB_ID;
	$pw = $DB_KEY;
	
	$db = "texts";
	$col = "joins";
	
	$collection = mongoCollection( $un, $pw, $db, $col );
	
	$newUser = true;
	$results = $collection->find( array( "group"=> $group ) );
	
	if($results != null){
	
		$results = iterator_to_array($results);
		
		foreach($results as $join){
		
			$members = $join['members'];
			
			foreach($members as $member){
			
				if($member["number"] == $userNumber):
					
					$subscribed = "You are already a member";
					error_log("you are already a member");
					sendSMS($userNumber, $subscribed);
					$newUser = false;
					//break;
					
				endif;
			
			}
		}
		
			
		
	}
	
	//---------------------------------------------//
	//Insert an object into our database
	
	if($newUser == true):
		
		$safe_insert = true;
		
		
		//store objects as php associative arrays
		
		$joinGroup = array(
		
				"group" => $group
		
		);

		
		
		$user = array(
		
				'$push' => array(
						
						"members" => array(
						
								"number" =>	$userNumber,
								"silence" => false
							
							)
				
				 )
		
		);
		
		//if group exists add, if group doesn't exist make.
		$upsert = array(
		
			 "upsert" => $safe_insert
		
		);
		
		try{
	
		//connect w. our mongo key		
		$collection -> update($joinGroup, $user, $upsert );
		
		} catch(Exception $e) {
		
			echo 'Caught exception:', $e->getMessage(), "\n";
		}
		
		
		
		$joinMessage = 'You joined '.$group;
		
		sendSMS($userNumber, $joinMessage);
	  
	  endif;



?>






