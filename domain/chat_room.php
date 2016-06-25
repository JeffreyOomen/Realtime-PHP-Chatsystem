<?php

	class Room {
		private $roomId;
		private $roomName;
		private $roomUserCount;
		private $roomFile;
		private $userArray;

		public function __construct($roomId, $roomName, $roomUserCount, $roomFile) {
			$this->roomId = $roomId;
			$this->roomName = $roomName;
			$this->roomUserCount = $roomUserCount;
			$this->roomFile = $roomFile;
			$this->userArray = array();
		}

		public function getRoomId() {
			return $this->roomId;
		}

		public function getRoomName() {
			return $this->roomName;
		}

		public function getRoomUserCount() {
			return $this->roomUserCount;
		}

		public function getRoomFile() {
			return $this->roomFile;
		}

		public function setRoomId($roomId) {
			$this->roomId = $roomId;
		}

		public function setRoomName($roomName) {
			$this->roomName = $roomName;
		}

		public function setroomUserCount($roomUserCount) {
			$this->roomUserCount = $roomUserCount;
		}

		public function setRoomFile($roomFile) {
			$this->roomFile = $roomFile;
		}

		public function addToArray($user) {
			array_push($this->userArray, $user);
		}
	}

?>