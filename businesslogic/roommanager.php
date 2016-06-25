<?php

require_once(__DIR__."..\\..\datastorage\\roomdao.php");
require_once(__DIR__."..\\..\sanitizer.php");

class RoomManager {

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