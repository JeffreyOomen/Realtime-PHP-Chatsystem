<?php

	require_once(__DIR__."..\\..\domain\\chat_user.php");

	class UserDAO {

		public function getUser($userId) {
			$connection = new Connection();
			$mysqliConnection = $connection->openConnection();

			$query = "SELECT user_id, user_name, user_mail, time_mod
					  FROM chat_user WHERE user_id = '$userId';";
			$queryResult = mysqli_query($mysqliConnection, $query) or trigger_error("Query: $mysqliConnection\n<br />MySQL Error: " . mysqli_error($mysqliConnection));

			$row = mysqli_fetch_array($queryResult, MYSQLI_ASSOC);
			$userObj = $this->createUserObj($row);
			
			$connection->freeResult($queryResult);
			$connection->closeConnection();
			return $userObj;
		}

		public function createUserObj($userArray) {
			$userObj = new User($userArray['user_id'], $userArray['user_name'], $userArray['user_mail'], $userArray['time_mod']);
			return $userObj;
		}

		public function updateUserTime($userId) {
			$connection = new Connection();
			$mysqliConnection = $connection->openConnection();
			$now = time();

			$query = "UPDATE chat_user SET time_mod = '$now' WHERE user_id = '$userId'";
			$queryResult = mysqli_query($mysqliConnection, $query) or trigger_error("Query: $mysqliConnection\n<br />MySQL Error: " . mysqli_error($mysqliConnection));

			//$connection->freeResult($queryResult);
			$connection->closeConnection();
		}
	}

?>