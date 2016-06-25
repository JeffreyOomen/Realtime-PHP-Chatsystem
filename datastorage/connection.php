<?php

	class Connection {

		const DB_USER = 'root';
		const DB_PASSWORD = '';
		const DB_HOST = 'localhost';
		const DB_NAME = 'phpchatsystem';
		var $connection;

		/**
		 * This method will open a connection with the database.
		 */
		public function openConnection() {
			$this->connection = mysqli_connect(self::DB_HOST, self::DB_USER, self::DB_PASSWORD, self::DB_NAME);
			return $this->connection;
		}

		/**
		 * This method will close a connection with the database.
		 */
		public function closeConnection() {
			mysqli_close($this->connection);
		}

		/**
		 * This method will free a result.
		 */
		public function freeResult($result) {
			mysqli_free_result($result);
		}

		/**
		 * This method will run some security checks
		 * to make sure the value of the given parameter
		 * can do no harm to this application.
		 */
		public static function securityCheck($measureSecurity) {
			return mysqli_real_escape_string($connection, $measureSecurity);
		}


	}

?>