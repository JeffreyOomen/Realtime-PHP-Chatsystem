var state = 0;
var currentUserCount = 0;

function Chat () {
    this.updateChat = updateChat;
    this.getUserList = getUserList;
}

$(function() {
    		 
    $("#send-area").keydown(function(event) {  
        console.log("keydown function!");
    
        //https://api.jquery.com/event.which/
        var key = event.which;  // Gets the code (number) which belongs to a character for every keydown
   
         // all keys including return 
         if (key >= 33) {  
         
             var maxLength = $(this).attr("maxlength"); // get max length of field (100)
             var length = this.value.length; // get current length 
             
             if (length >= maxLength) {  
                 event.preventDefault();  
             }  
         }  
																																																			});
			 
    $('#send-area').keyup(function(e) {	
    					 
        if (e.keyCode == 13) { // 13 is the key code when pressing Enter

            var text = $(this).val();
            var maxLength = $(this).attr("maxlength");  
            var length = text.length; 
            console.log("keyup function enter and nickname is: " + nickname);
            if (length <= maxLength + 1) {  
                sendChat(text, nickname);	// the name was set in rooms/index.php
                $(this).val(""); // empty the text field
            } else {
                $(this).val(text.substring(0, maxLength)); // if too much characters, only get 0 - 100
            }	
        
        }
        
    });
            
});

//send the message
function sendChat(message, nickname) {       
    $.ajax({    
       type: "POST",
       url: "../../businesslogic/chatmanager.php",
       data: {  
            'function': 'sendMessage',
            'message': message,
            'nickname': nickname,
            'file': file
            },
       dataType: "json",
       success: function(data) {
            //updateChat();
       },
    });
}

//Updates the chat
function updateChat() {

    console.log("update chat called with state: " + state);

    $.ajax({    
       type: "POST",
       url: "../../businesslogic/chatmanager.php",
       data: {  
            'function': 'updateMessages',
            'chatFile': file,
            'lastLineCount' : state
            },
       dataType: "json",
       cache: false,
       success: function(data) {

            if (data.chatMessages != null) {
                
                for (var i = 0; i < data.chatMessages.length; i++) {
                    var str = data.chatMessages[i];
                    var userName = str.split('<span>')[1].split("</span>")[0];
                    var message = str.split('</span>')[1];

                    if (userName == nickname) {
                        $('#message-box').append($("<div class='message bubble-right'><label class='message-user'>" + userName + "</label><label class='message-timestamp'>2 Hours ago</label><p>"+ message +"</p></div>"));
                    } else {
                        $('#message-box').append($("<div class='message bubble-left'><label class='message-user'>" + userName + "</label><label class='message-timestamp'>2 Hours ago</label><p>"+ message +"</p></div>")); 
                    }
                }
                document.getElementById('message-box').scrollTop = document.getElementById('message-box').scrollHeight;
            } 
            state = data.lastLineCount;
            setTimeout('updateChat()', 1); 
       },
    });
}

function getUserList(roomId, userId) {

    console.log("get user list called with ids: " + roomId + " " + userId);

    $.ajax({    
        type: "POST",
        url: "../../businesslogic/chatmanager.php",
        data: {  
            'function': 'userlist',
            'roomid': roomId,
            'userid': userId,
            'currentUserCount' : currentUserCount
        },
        dataType: "json",
        success: function(data) {

            if (data.userObjects != null) {
                var list = "";
                for (var i = 0; i < data.userObjects.length; i++) {
                    var userName = data.userObjects[i].userName;
                    var userMail = data.userObjects[i].userMail;
                    list += "<li><a href='#'><span class='user-img'></span>";
                    list += "<span class='user-title'>" + userName + "</span>";
                    list += "<p class='user-desc'>" + userMail + "</p></a></li>";
                }

                $('#message-user-list').html(list);


            }
            currentUserCount = data.currentUserCount;
            setTimeout(getUserList(roomId, userId), 5); 
        }, error: function (request, status, error) {
            //alert(request.responseText);
        }
    });
}