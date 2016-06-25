<?php
	
	require_once("connection.php");

	class LoginDAO {

		/**
		 * This method will execute the login query which
		 * will check whether the username and password are matching
		 * the data in the database.
		 */
		public function login($userMail, $password) {
			$connection = new Connection();
			$mysqliConnection = $connection->openConnection();

			$query = "SELECT user_id, user_name, user_mail, time_mod
						  FROM chat_user WHERE user_mail = '$userMail' AND user_pass= '$password';";
			$queryResult = mysqli_query($mysqliConnection, $query) or trigger_error("Query: $mysqliConnection\n<br />MySQL Error: " . mysqli_error($mysqliConnection));

			$match = false;
			if ($match = $this->isMatch($queryResult)) {
				$_SESSION = mysqli_fetch_array($queryResult, MYSQLI_ASSOC);
			}

			$connection->closeConnection(); 
			return $match;
		}

		/**
		 * This method will check if the login query
		 * was successful. It only is successful if 
		 * just one result was returned.
		 */
		private function isMatch($queryResult) {
			if (mysqli_num_rows($queryResult) == 1) { // Match made
				return true;
			} else { // No match was made.
				return false;
			}
		}
	}
?>