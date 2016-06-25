<?php

require_once('../datastorage/roomdao.php');
require_once('../sanitizer.php');

class UserManager {

	public function getRooms() {
		$roomDAO = new RoomDAO();
		return $roomDAO->getRooms();
	}

	public function getRoom($roomId) {
		$roomDAO = new RoomDAO();
		return $roomDAO->getRoom($roomId);
	}

}

?>