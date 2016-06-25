<?php

session_start();
require_once('../datastorage/logindao.php');
require_once('../sanitizer.php');

class LoginManager {

	public function login($userMail, $password) {

		if (Sanitizer::hasValue($userMail) && Sanitizer::hasValue($password)) {
			$loginDAO = new LoginDAO(); // Make a database connection

			if ($loginDAO->login($userMail, $password)) {
				header('Location: chat_rooms.php'); // Login was success, so redirect
			}
		}
	}

}

?>