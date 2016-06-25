<?php

	session_start();
	require_once("../../businesslogic/roommanager.php");
	require_once("../../sanitizer.php");
	require_once("../../businesslogic/chatmanager.php");

	$roomId = $_GET['room_id'];
	echo "" . $roomId;
	if (Sanitizer::hasValue($roomId) && isset($_SESSION['user_id'])): 

		$roomManager = new RoomManager();
		$room = $roomManager->getRoom($roomId);

		$roomName = $room->getRoomName();
		$roomFile = $room->getRoomFile();

		/*$chatManager = new ChatManager();
		$userList = $chatManager->getRoomUsers($roomId, $_SESSION['user_id']);*/

?>

<!DOCTYPE html>
<html>
	<head>
		<meta charset="UTF-8">
		<title>Chat System</title>
		<link rel="stylesheet" href="../css/chat.css">
		<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.4/jquery.min.js" type="text/javascript"></script>
    	<script type="text/javascript">
    		var nickname = '<?php echo $_SESSION['user_name'];?>';
    		var file = '<?php echo $roomFile; ?>';
    	</script>
    	<script type="text/javascript" src="../js/send.js"></script>
    	<script type="text/javascript">
    		var chat = new Chat();
    		chat.updateChat();
    		chat.getUserList(<?php echo "'" . $roomId ."','" . $_SESSION['user_id'] . "'"; ?>);
    	</script>
	</head>

	<body>
		<div id="wrapper">
			<h1><?php echo $roomName ?></h1>
			<div class="message-container">
				<div class="message-north">
					<ul class="message-user-list" id="message-user-list">
						<!-- <li>
							<a href="#">
								<span class="user-img"></span>
								<span class="user-title">Bryan Adams</span>
								<p class="user-desc">badams@music.com</p>
							</a>
						</li>
						<li>
							<a href="#">
								<span class="user-img"></span>
								<span class="user-title">Enrique Iglesias</span>
								<p class="user-desc">enriqueiglesias@music.com</p>
							</a>
						</li>
						<li>
							<a class="active" href="#">
								<span class="user-img"></span>
								<span class="user-title">Jack Johnson</span>
								<p class="user-desc">jackjohnson@music.com</p>
							</a>
						</li>
						<li>
							<a href="#">
								<span class="user-img"></span>
								<span class="user-title">Paul McCartney</span>
								<p class="user-desc">paulmccartney@music.com</p>
							</a>
						</li>
						<li>
							<a href="#">
								<span class="user-img"></span>
								<span class="user-title">George Harrison</span>
								<p class="user-desc">georgeharrison@music.com</p>
							</a>
						</li>
						<li>
							<a href="#">
								<span class="user-img"></span>
								<span class="user-title">Jason Mraz</span>
								<p class="user-desc">jasonmraz@music.com</p>
							</a>
						</li>
						<li>
							<a href="#">
								<span class="user-img"></span>
								<span class="user-title">Louis Armstrong</span>
								<p class="user-desc">louisarmstrong@music.com</p>
							</a>
						</li>
						<li>
							<a href="#">
								<span class="user-img"></span>
								<span class="user-title">Clinton Cerejo</span>
								<p class="user-desc">clintoncerejo@music.com</p>
							</a>
						</li>
						<li>
							<a href="#">
								<span class="user-img"></span>
								<span class="user-title">John Lennon</span>
								<p class="user-desc">johnlennon@music.com</p>
							</a>
						</li>
						<li>
							<a href="#">
								<span class="user-img"></span>
								<span class="user-title">Joseph Arthur</span>
								<p class="user-desc">josepharthur@music.com</p>
							</a>
						</li>
						<li>
							<a href="#">
								<span class="user-img"></span>
								<span class="user-title">Bryan Adams</span>
								<p class="user-desc">badams@music.com</p>
							</a>
						</li>
						<li>
							<a href="#">
								<span class="user-img"></span>
								<span class="user-title">Enrique Iglesias</span>
								<p class="user-desc">enriqueiglesias@music.com</p>
							</a>
						</li>
						<li>
							<a href="#">
								<span class="user-img"></span>
								<span class="user-title">Jack Johnson</span>
								<p class="user-desc">jackjohnson@music.com</p>
							</a>
						</li>
						<li>
							<a href="#">
								<span class="user-img"></span>
								<span class="user-title">Paul McCartney</span>
								<p class="user-desc">paulmccartney@music.com</p>
							</a>
						</li>
						<li>
							<a href="#">
								<span class="user-img"></span>
								<span class="user-title">George Harrison</span>
								<p class="user-desc">georgeharrison@music.com</p>
							</a>
						</li> -->
						<!-- <?php 
								                	/*foreach($userList as $key => $user):
								                		$userName = $user->getUserName();
								$userMail = $user->getUserMail();*/
						?>
						<li>
							<a href="#">
								<span class="user-img"></span>
								<span class="user-title"><?php /*echo*/ $userName; ?></span>
								<p class="user-desc"><?php /*echo*/ $userMail; ?></p>
							</a>
						</li>
						<?php /*endforeach*/; ?> -->
					</ul>
					<div class="message-thread" id="message-box">
						<!-- <div class="message bubble-left">
							<label class="message-user">Bryan Adams</label>
							<label class="message-timestamp">2 Hours Ago</label>
							<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Etiam feugiat nunc ut nibh interdum tempus. Donec at lorem eget sapien iaculis porttitor id quis ligula feugiat nunc ut nibh justo eget elit aliquet interdum tempus.</p>
						</div>
						<div class="message bubble-right">
							<label class="message-user">Jack Johnson</label>
							<label class="message-timestamp">2 Hours Ago</label>
							<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Etiam feugiat nunc ut nibh interdum tempus. Donec at lorem eget sapien iaculis porttitor id quis ligula feugiat nunc ut nibh justo eget elit aliquet interdum tempus.</p>
						</div>
						<div class="message bubble-left">
							<label class="message-user">Bryan Adams</label>
							<label class="message-timestamp">2 Hours Ago</label>
							<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit.</p>
						</div>
						<div class="message bubble-right">
							<label class="message-user">Jack Johnson</label>
							<label class="message-timestamp">2 Hours Ago</label>
							<p>:-)</p>
						</div>
						<div class="message bubble-left">
							<label class="message-user">Bryan Adams</label>
							<label class="message-timestamp">2 Hours Ago</label>
							<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit.</p>
						</div>
						<div class="message bubble-right">
							<label class="message-user">Jack Johnson</label>
							<label class="message-timestamp">2 Hours Ago</label>
							<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Etiam feugiat nunc ut nibh interdum tempus. Donec at lorem eget sapien iaculis porttitor id quis ligula feugiat nunc ut nibh justo eget elit aliquet interdum tempus.</p>
						</div>
						<div class="message bubble-right">
							<label class="message-user">Jack Johnson</label>
							<label class="message-timestamp">2 Hours Ago</label>
							<p>;-)</p>
						</div>
						<div class="message bubble-left">
							<label class="message-user">Bryan Adams</label>
							<label class="message-timestamp">2 Hours Ago</label>
							<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Etiam feugiat nunc ut nibh interdum tempus. Donec at lorem eget sapien iaculis porttitor id quis ligula feugiat nunc ut nibh justo eget elit aliquet interdum tempus.</p>
						</div>
						<div class="message bubble-left">
							<label class="message-user">Bryan Adams</label>
							<label class="message-timestamp">2 Hours Ago</label>
							<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit.</p>
						</div> -->
					</div>
				</div>
				<div class="message-south">
					<textarea cols="20" rows="3" id="send-area" maxlength="100"></textarea>
					<button>Send</button>
				</div>
			</div>
		</div>
	</body>
</html>

<?php
    else:
        header('Location: http://css-tricks.com/examples/Chat2/');
    endif; 
?>