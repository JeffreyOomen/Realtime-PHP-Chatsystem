<?php

	class User {
		private $userId;
		public $userName;
		public $userMail;
		private $timeMod;

		public function __construct($userId, $userName, $userMail, $timeMod) {
			$this->userId = $userId;
			$this->userName = $userName;
			$this->userMail = $userMail;
			$this->timeMod = $timeMod;
		}

		public function getUserId() {
			return $this->userId;
		}

		public function getUserName() {
			return $this->userName;
		}

		public function getUserMail() {
			return $this->userMail;
		}

		public function getUserTimeMod() {
			return $this->timeMod;
		}

		public function setUserId($userId) {
			$this->userId = $userId;
		}

		public function setUserName($userName) {
			$this->username = $userName;
		}

		public function setUserMail($userMail) {
			$this->userMail = $userMail;
		}

		public function setUserTimeMod($timeMod) {
			$this->timeMod = $timeMod;
		}
	}

?>