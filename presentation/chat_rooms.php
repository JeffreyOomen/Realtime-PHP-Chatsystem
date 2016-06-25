<?php
	session_start();

	require_once("../businesslogic/roommanager.php");
    require_once("../businesslogic/chatmanager.php");

	if (!isset($_SESSION['user_id'])) {
	    header("Location: index.php");
	}

	$roomManager = new RoomManager();
	$roomArray = $roomManager->getRooms();

    $chatManager = new ChatManager();
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>Chat Rooms</title>
    <link rel="stylesheet" type="text/css" href="css/chat_rooms.css"/>
</head>

<body>
    <div id="page-wrap"> 

    	<div id="header">
        	<h1><a href="#">Chat System</a></h1>
        	<div id="loggedin"><span>Logged in as:</span> <?php echo $_SESSION['user_name']; ?></div>
        </div>
        
    	<div id="section">
            <div id="rooms">
            	<h3>Rooms</h3>
                <ul>
                	<?php 
	                	foreach($roomArray as $key => $value):
	                		$roomId = $value->getRoomId();
							$roomName = $value->getRoomName();
                            $numOfUsers = $chatManager->getRoomUserCount($roomId);
                    ?>
                    <li>
                        <a href="room/?room_id=<?php echo $roomId ?>"><?php echo $roomName . "<span>Users chatting: <strong>" . $numOfUsers . "</strong></span>" ?></a>
                    </li>
                    <?php endforeach;   ?>
                </ul>
            </div>
        </div>
        
    </div>

</body>
</html>