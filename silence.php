<?php


	require_once('includes/functions.php');
	
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
			
			$counter = 0;
			
			foreach($members as $member){
			
			
				if($member["number"] == $userNumber):
				
				if($member["silence"] == false ){
				
					$bSilent = true;
					
				}else{
				
					$bSilent = false;
				
				}
				
				//store objects as php associative arrays
		
				$silenceGroup = array(
		
						"group" => $group

				);
				
				$errorString = "attempting to connect to index: ".$counter;
				error_log($errorString);
		
				$user = array(
		
						'$set' => array(
						
							"members.".$counter => array(
							
								"number" => $userNumber,
								"silence" => $bSilent
							
							 )	
					
						 )
		
				);
					
					
				try{
			
				//connect w. our mongo key		
				$collection -> update($silenceGroup, $user);
				if($bSilent){
				
				$unsubscribed = "You have silenced the group ".$group.", text 'SILENCE ".$group."' to change";
				} else {
				
				$unsubscribed = "You have unsilenced the group ".$group.", text 'SILENCE ".$group."' to change";

				
				}
				sendSMS($userNumber, $unsubscribed);
				//$newUser = false;
				//break;
				
				} catch(Exception $e) {
				
					//$errorString = 'Caught exception:', $e->getMessage(), "\n";
					//error_log($errorString);
				}
		
					
										

					
				endif;
			
				$counter++;
			
			}
		}
		
			
		
	}


?>