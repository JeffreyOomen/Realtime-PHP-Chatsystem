<?php
	
	require_once("connection.php");
	require_once(__DIR__."..\\..\domain\\chat_room.php");

	class RoomDAO {

		public function getRooms() {
			$connection = new Connection();
			$mysqliConnection = $connection->openConnection();

			$query = "SELECT room_id, room_name, room_users_count, room_file
					  FROM chat_room;";
			$queryResult = mysqli_query($mysqliConnection, $query) or trigger_error("Query: $mysqliConnection\n<br />MySQL Error: " . mysqli_error($mysqliConnection));

			$roomObjects = array();
			while($row = mysqli_fetch_array($queryResult, MYSQLI_ASSOC)) {
				$roomObjects[] = $this->createRoomObj($row);
			}

			$connection->closeConnection();
			return $roomObjects;
		}


		public function getRoom($roomId) {
			$connection = new Connection();
			$mysqliConnection = $connection->openConnection();

			$query = "SELECT room_id, room_name, room_users_count, room_file
					  FROM chat_room WHERE room_id = '$roomId';";
			$queryResult = mysqli_query($mysqliConnection, $query) or trigger_error("Query: $mysqliConnection\n<br />MySQL Error: " . mysqli_error($mysqliConnection));

			$result = $this->createRoomObj(mysqli_fetch_array($queryResult, MYSQLI_ASSOC));

			$connection->closeConnection();
			return $result;
		}

		public function createRoomObj($roomArray) {
			$roomObj = new Room($roomArray['room_id'], $roomArray['room_name'], $roomArray['room_users_count'], $roomArray['room_file']);
			return $roomObj;
		}

	}
?>