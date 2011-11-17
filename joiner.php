<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8"></meta>

</head>
<body>
<?php 

	header("Expires: Mon, 26 Jul 1997 05:00:00 GMT" ); 
	header("Last-Modified: " . gmdate( "D, d M Y H:i:s" ) . "GMT" ); 
	header("Cache-Control: no-cache, must-revalidate" ); 
	header("Pragma: no-cache" );
	//header("Content-Type: text/xml; charset=utf-8");
	require_once('includes/functions.php');
	require_once("includes/keys.php");

	//echo phpinfo();
	//test

	//**************************************************
	
	$userNumber = $_REQUEST['From'];
	$message = $_REQUEST['Body'];
	
	if($userNumber != null && $message != null ):
	
		$decode = array();
		
		$decode = explode(" ", $message);
		
		$command = trim(strtoupper( $decode[0] ));
		
		
		
		if($command === "JOIN"){
		
			$group = trim(strtoupper( $decode[1] ));
			
	        include('join.php');
	
		
		} else if ($command === "LEAVE") {
		
		
		    $group = trim(strtoupper( $decode[1] ));
			
	        include('leave.php');
		
		
		}else if ($command === "HELP") {
		
			//$helpMessage = '"join GROUPNAME" join group, "GROUPNAME message" send message, "leave GROUPNAME" leave group, "silence GROUPNAME", "listen" hear all groups';
			
			$helpMessage = '"join GROUPNAME" join group, "GROUPNAME message" send message, "leave GROUPNAME" leave group, "silence GROUPNAME" toggle group silence';
			
			sendSMS($userNumber, $helpMessage);
			
		} else if($command === "SILENCE"){
		    
		    error_log("SILENCE COMMAND");
		    
		    $group = trim(strtoupper( $decode[1] ));

			include('silence.php');
		
		} else {
		
			
			error_log("From before db");
			$un = $DB_ID;
			$pw = $DB_KEY;
	
			$db = "texts";
			$col = "joins";	
			
			$collection = mongoCollection( $un, $pw, $db, $col );
			
			$results = $collection->find( array( "group" => $command ) );
	
			if($results != null){
			
				$sendMessage = false;	
				$results = iterator_to_array($results);
						
						foreach($results as $join){
						
							$members = $join['members'];
							
							foreach($members as $member){
							
								if($member["number"] == $userNumber):
								
									$sendMessage = true;
									
									$messageSent = 'YourMessage has been sent';
									
						
								endif;
							}
							
							if($sendMessage == true):
							
							foreach($members as $member){
							
									$errorString = "Were a member".$member["number"];
									error_log($errorString);
											
									if($member["number"] != $userNumber):
																
										$groupLength = strlen($join["group"]);
										
										//$groupLength =+ 1;
										$memberNumber = $member["number"];
									
										$sendmessage = substr($message, $groupLength);
										//$subscribed = "You are already a member";
										
										$outgoingMessage = $join["group"].$sendmessage;
										
										if($member["silence"] == false){
									
										sendSMS($memberNumber, $outgoingMessage);
									
										}
									
									endif;
									
							}
							
							endif;
								
								
							}
			
			
			}
		
		
		}
	
	else:
	
		
			echo 'not for humans';
	endif;



?>

<p> Some Text</p>
</body>
</html>
