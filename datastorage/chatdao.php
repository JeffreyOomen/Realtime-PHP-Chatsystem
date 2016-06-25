<?php
	require_once("connection.php");
	
	require_once(__DIR__."..\\userdao.php");

	class ChatDAO {

		/**
		 * This method will get all room users from the database.
		 * These users will be converted to User objects. This method
		 * should be only used for user list initialization. After
		 * that updateUserList should be used for performance reasons.
		 * @param $roomId the id of the room
		 * @param $userId the id of te user
		 * @param $currentUserCount the last known usercount in the room
		 * @return a list with user objects
		 */
		public function getRoomUsers($roomId, $userId, $currentUserCount) {
			$connection = new Connection();
			$mysqliConnection = $connection->openConnection();

			// Add current user if not already exists
			if (!$this->existsRoomUser($userId, $roomId)) {
				$this->addRoomUser($userId, $roomId);
			} 

			$finish = time() + 7;
			$userDao = new UserDAO();

			$hasChanged = false;
			while(true) {
				usleep(100000); // sleep for 0.1 seconds

				// Keep updating the time_mod column in the users table
				$userDao->updateUserTime($userId);
				$expiringTime = time() - 5;
				$this->deleteRoomUsers($expiringTime);

				$now = time();
				if ($now <= $finish) {
					$this->updateRoomUserTime($userId, $roomId);
					if ($hasChanged = $this->hasRoomUsersChanged($currentUserCount, $roomId)) { // if number of users has changed, break out of this while loop
						break;
					}
				} else { // if finish time has been reached, break out of this while loop
					break;	
			    }
		    }

		    $userObjects = array();
		    if ($hasChanged) {
		    	$query = "SELECT user_id FROM chat_users_rooms WHERE room_id ='$roomId';";
				$queryResult = mysqli_query($mysqliConnection, $query) or trigger_error("Query: $mysqliConnection\n<br />MySQL Error: " . mysqli_error($mysqliConnection));

				$userObjects['currentUserCount'] = mysqli_num_rows($queryResult);
				$userObjects['userObjects'] = array();
				while($row = mysqli_fetch_array($queryResult, MYSQLI_ASSOC)) {
					$userObjects['userObjects'][] = $userDao->getUser($row['user_id']);
				}
				

				//$connection->freeResult($queryResult);
				$connection->closeConnection();

		    } else {
		    	$userObjects['currentUserCount'] = $currentUserCount;
		    }

		    return $userObjects;
		}

		/**
		 * This method will add a new active user to the
		 * table which holds this kind of data. A user
		 * can only be added if it was not added before.
		 * @param $roomId the id of the room
		 * @param $userId the id of the user
		 * @return True if insert was success, false otherwise 
		 */
		private function addRoomUser($userId, $roomId) {
			$connection = new Connection();
			$mysqliConnection = $connection->openConnection();

			/*if ($this->existsRoomUser($roomId, $userId)) {
				return true;
			}*/

			$now = time();
			$query = "INSERT INTO `chat_users_rooms` (`id`, `user_id`, `room_id`, `time_mod`) VALUES ( NULL , '$userId', '$roomId', '$now')";
			$queryResult = mysqli_query($mysqliConnection, $query) or trigger_error("Query: $mysqliConnection\n<br />MySQL Error: " . mysqli_error($mysqliConnection));
			$connection->closeConnection();
			return $queryResult;
		}

		/**
		 * This method will check if a certain user already
		 * is registered in the table which holds all active
		 * users per room.
		 * @param $roomId the id of the room
		 * @param $userId the id of the user
		 */
		private function existsRoomUser($userId, $roomId) {

			$connection = new Connection();
			$mysqliConnection = $connection->openConnection();

			$query = "SELECT id, user_id, room_id, time_mod
					  FROM chat_users_rooms WHERE `user_id` = '$userId' AND `room_id` ='$roomId';";
			$queryResult = mysqli_query($mysqliConnection, $query) or trigger_error("Query: $mysqliConnection\n<br />MySQL Error: " . mysqli_error($mysqliConnection));

			$match = false;
			if (mysqli_num_rows($queryResult) == 1) {
				$match = true;
			}

			$connection->closeConnection();
			return $match;
		}

		/**
		 * This method will return the current number of users
		 * which are in a particular room.
		 * @param $roomId the id of the room for which the count is needed
		 * @return the total number of active users in the room
		 */
		public function getRoomUserCount($roomId) {
			$connection = new Connection();
			$mysqliConnection = $connection->openConnection();

			$query = "SELECT id
					  FROM chat_users_rooms WHERE `room_id` ='$roomId';";
			$queryResult = mysqli_query($mysqliConnection, $query) or trigger_error("Query: $mysqliConnection\n<br />MySQL Error: " . mysqli_error($mysqliConnection));

			$roomUserCount = mysqli_num_rows($queryResult);

			$connection->freeResult($queryResult);
			$connection->closeConnection();
			return $roomUserCount;
		}

		/**
		 * This method will update the time_mod column
		 * for the specified user which is in a certain room.
		 * By doing this, it can be concluded which users are 
		 * active and which ones are not (not active ones get deleted).
		 * @param $userId the id of the user for which the time should be updated
		 * @param $roomId the id of the room in which the user is in 
		 */
		private function updateRoomUserTime($userId, $roomId) {
			$connection = new Connection();
			$mysqliConnection = $connection->openConnection();
			$now = time();

			$query = "UPDATE `chat_users_rooms` SET `time_mod` = '$now' WHERE `user_id` = '$userId' AND `room_id` ='$roomId'  LIMIT 1;";
			$queryResult = mysqli_query($mysqliConnection, $query) or trigger_error("Query: $mysqliConnection\n<br />MySQL Error: " . mysqli_error($mysqliConnection));

			$connection->closeConnection();
		} 

		/**
		 * This method will check if the amount of room users
		 * in a particular room has changed. 
		 * @param $currentUserCount the latest known amount of users
		 * @return True if user amount has changed, false otherwise
		 */
		private function hasRoomUsersChanged($currentUserCount, $roomId) {
			$connection = new Connection();
			$mysqliConnection = $connection->openConnection();

			$query = "SELECT user_id
					  FROM chat_users_rooms WHERE room_id ='$roomId';";
			$queryResult = mysqli_query($mysqliConnection, $query) or trigger_error("Query: $mysqliConnection\n<br />MySQL Error: " . mysqli_error($mysqliConnection));

			$newUserCount = mysqli_num_rows($queryResult);
			$hasChanged = false;
			if ($currentUserCount != $newUserCount) { // means there are new users
				$hasChanged = true;
			}
			
			$connection->freeResult($queryResult);
			$connection->closeConnection();
			return $hasChanged;
		}

		/**
		 * This method will remove all users which have
		 * a time_mod which is smaller than the expiring time
		 * parameter. This means that they are not active in
		 * any room anymore, so they should be deleted.
		 * @param $expiringTime the expiring time to which users will be removed
		 */
		private function deleteRoomUsers($expiringTime) {
			$connection = new Connection();
			$mysqliConnection = $connection->openConnection();

			$query = "DELETE FROM `chat_users_rooms` WHERE `time_mod` < '$expiringTime';";
			$queryResult = mysqli_query($mysqliConnection, $query) or trigger_error("Query: $mysqliConnection\n<br />MySQL Error: " . mysqli_error($mysqliConnection));
			
			$connection->closeConnection();
		}
	}

?>