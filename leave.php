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
			
			foreach($members as $member){
			
				if($member["number"] == $userNumber):
				
				//store objects as php associative arrays
		
				$joinGroup = array(
		
						"group" => $group
		
				);

		
				$user = array(
		
						'$pull' => array(
						
						"members" =>  array(
						
							"number" =>	$userNumber
				
						 )
						 )
		
				);
					
					
				try{
			
				//connect w. our mongo key		
				$collection -> update($joinGroup, $user);
				
				$unsubscribed = "You have left the group ".$group;
				
				sendSMS($userNumber, $unsubscribed);
				//$newUser = false;
				//break;
				
				} catch(Exception $e) {
				
					echo 'Caught exception:', $e->getMessage(), "\n";
				}
		
					
										

					
				endif;
			
			}
		}
		
			
		
	}

?>