<?php

	require_once(__DIR__."..\\..\datastorage\\chatdao.php");
	require_once(__DIR__."..\\..\sanitizer.php");

	if ($_SERVER['REQUEST_METHOD'] === 'POST') {
		$function = htmlentities(strip_tags($_POST['function']), ENT_QUOTES);
	  	
		 switch ($function) {
	    
	    	case ('sendMessage'):
	    	 	$chatMessage = htmlentities(strip_tags($_POST['message']), ENT_QUOTES);
			  	$nickName = htmlentities(strip_tags($_POST['nickname']), ENT_QUOTES);
			  	$chatFile = htmlentities(strip_tags($_POST['file']), ENT_QUOTES);
	        	$chatManager = new ChatManager();
				$chatManager->sendChatMessage($chatFile, $nickName, $chatMessage);
	        	break;
	        case ('updateMessages'):
	        	// Corresponds to the file name which holds the chat data .
			  	$chatFile = htmlentities(strip_tags($_POST['chatFile']), ENT_QUOTES);
			  	// Corresponds to the amount of lines of the file when it was
				// read for the last time.
				$lastLineCount = htmlentities(strip_tags($_POST['lastLineCount']), ENT_QUOTES);
			  	$chatManager = new ChatManager();
			  	$chatManager->updateChatMessages($chatFile, $lastLineCount);
			  	break;
			case ('userlist'):
				$roomId = htmlentities(strip_tags($_POST['roomid']), ENT_QUOTES);
				$userId = htmlentities(strip_tags($_POST['userid']), ENT_QUOTES);
				$currentUserCount = htmlentities(strip_tags($_POST['currentUserCount']), ENT_QUOTES);
				$chatManager = new ChatManager();
				echo json_encode($chatManager->getRoomUsers($roomId, $userId, $currentUserCount));
				break;
	    }
	}

	
	class ChatManager {

		public function getRoomUsers($roomId, $userId, $currentUserCount) {
			$chatDAO = new ChatDAO();
			$userList = $chatDAO->getRoomUsers($roomId, $userId, $currentUserCount);
			return $userList;
		}

		public function sendChatMessage($chatFile, $nickName, $chatMessage) {
            fwrite(fopen($chatFile, 'a'), "<span>". $nickName . "</span>" . $chatMessage = str_replace("\n", " ", $chatMessage) . "\n"); 
		}


		/**
		 * This method will check if there have been new 
		 * messages written to the given room file which
		 * contains the chat messages for one room. If there
		 * are new messages, those will be also returned.
		 */
		public function updateChatMessages($chatFile, $lastLineCount) {

			$finish = time() + 10; // when this time has been reached, break out of the loop
    		$currentLineCount = $this->getlines($this->getfile($chatFile)); // get current number of lines from file

    		// The updateChat() method from chat.js will reach this while
		    // loop which causes it to hang here. When the amountOfLines
		    // is smaller than the state, this means that there are no new
		    // messages send.
		    $count = 0;
		    while ($currentLineCount <= $lastLineCount) {
		    	
		        $now = time(); // current time since 1970 in seconds
				$count++;
		        // Just to have some time in between, let the script sleep
		        // for 0.01 seconds to check after this if the amountOfLines
		        // has maybe changed.
		        usleep(10000);
		        
		        // Finally after 0.01 seconds check if now is less
		        // than the finish time which it mostly will be.
		        // Notice that $finish don't change anymore, but the
		        // $now variable will every time the while loop loops.
		        // This means $now will get bigger and bigger until it
		        // reaches the $finish some time.
		        if ($now <= $finish) {
		            $currentLineCount = $this->getlines($this->getfile($chatFile)); // Check if amountOfLines has been changed in between.
		        } else {
		            break; //break ends execution of the current for, foreach, while, do-while or switch structure.
		            // So the script will break out of this while loop and continue the code when $finish has been reached.
		        }  
		    }

		    // So there are two ways to break out of this while loop.
		    // Either $finish has been reached, or the $amountOfLines
		    // variable has become bigger than the $state variable
		    // which means there are new messages.		 
		    
		    // In case that the $finish reach was the cause of the break out
		    // of the while loop would mean there were no new messages.
		    // In thay case the if-statement will be run because the $state
		    // is still the same as $amountOfLines.
		    if ($lastLineCount == $currentLineCount) { 
		        $log['lastLineCount'] = $currentLineCount; // State remains the same
		    } else { //means $amountOfLines has become bigger) and so there are new messages
		        $text = array();
		        $log['lastLineCount'] = $this->getlines($this->getfile($chatFile)); // refresh state with the new number of lines
		        
		        // In this for each loop only the new messages are added to the text[] array.
		        // This can be achieved by getting the line numbers (message numbers) which are bigger or equals to
		        // the state. 
		        foreach ($this->getfile($chatFile) as $line_num => $line) {
		            if ($line_num >= $lastLineCount) { // Means the messages were not added to the state before (new messages)
		                // This function returns a string or an array with the replaced values.
		                $text[] = str_replace("\n", "", $line); // Replace the \n newlines
		            }
		            $log['chatMessages'] = $text; 
		        }
		    }

		    echo json_encode($log);	// This will be picked up automatically by ajax
		}

		/**
		 * This method will return the total amount
		 * of users in a particular room.
		 */
		public function getRoomUserCount($roomId) {
			$chatDAO = new ChatDAO();
			$roomUserCount = $chatDAO->getRoomUserCount($roomId);
			return $roomUserCount;
		}

	 	/**
	     * This method will return the file in array format.
	     * Each element of the array corresponds to a line in the file
	     * with the newline character still attached. Returns false if
	     * file does not exists or when contents couldnt be turned to array format.
	     */
		public function getfile($file) {
			if (file_exists($file)) { // Check if the file exists
				$fileArray = file($file);
			}	
			return $fileArray; 
		}
		    
	    /**
	     * This method will return the number of lines in
	     * the given parameter which are the file contents
	     * in array format. Each item in this array corresponds
	     * to a line. So simple count the amount of elements in the array.
	     */
		public function getlines($fileArray){
		   	return count($fileArray);	
		}

	}

?>