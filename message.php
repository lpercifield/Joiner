<?php

// a function to save texts by date and group in the db.

function logSMS($group, $message) {


	$un = $DB_ID;
	$pw = $DB_KEY;
	
	$db = "texts";
	$col = "messages";
	
	$collection = mongoCollection( $un, $pw, $db, $col );
	
	$safe_insert = true;
	
	$joinGroup = array(
		
		"group_id" => $group,
		
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
	
		"upsert" => $safe_insert;
	
	);
	
	try{
	
		//connect w. our mongo key		
		$collection -> update($joinGroup, $content, $upsert );
		
		} catch(Exception $e) {
		
			echo 'Caught exception:', $e->getMessage(), "\n";
	}
	
}

?>