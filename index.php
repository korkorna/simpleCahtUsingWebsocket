<!DOCTYPE html>
<html>
<head>
	<meta charset="UTF-8">
	<title>Simple Chat Using Websocket</title>
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<script src="https://ajax.aspnetcdn.com/ajax/jQuery/jquery-3.1.1.min.js"></script>
	<style type="text/css">

.panel{

	
margin-right: 3px;
}

.button {
    background-color: #4CAF50;
    border: none;
    color: white;
	margin-right: 30%;   
	margin-left: 30%;
    text-decoration: none;
    display: block;
    font-size: 16px;
    cursor: pointer;
	width:30%;
    height:40px;
	margin-top: 5px;
	 
}
input[type=text]{
		width:100%;
		margin-top:5px;
		
	}


.chat_wrapper {
	width: 70%;
	height:472px;
	margin-right: auto;
	margin-left: auto;
	background: #3B5998;
	border: 1px solid #999999;
	padding: 10px;
	font: 14px 'lucida grande',tahoma,verdana,arial,sans-serif;
}
.chat_wrapper .message_box {
	background: #F7F7F7;
	height:350px;
		overflow: auto;
	padding: 10px 10px 20px 10px;
	border: 1px solid #999999;
}
.chat_wrapper  input{
	//padding: 2px 2px 2px 5px;
}
.system_msg{color: #BDBDBD;font-style: italic;}
.user_name{font-weight:bold;}
.user_message{color: #88B6E0;}

@media only screen and (max-width: 720px) {
    /* For mobile phones: */
    .chat_wrapper {
        width: 95%;
	height: 40%;
	}
    

	.button{ width:100%;
	margin-right:auto;   
	margin-left:auto;
	height:40px;}
	
	
	
	
	
				
}

</style>
</head>

<body>
	<?php 
	$colours = array('007AFF','FF7000','FF7000','15E25F','CFC700','CFC700','CF1100','CF00BE','F00');
	$user_colour = array_rand($colours);
	?>
	<div class="chat_wrapper">
		<div class="message_box" id="message_box"></div>

		<div class="panel">
			<input type="text" name="name" id="name" placeholder="Your Name" maxlength="25">
			<input type="text" name="message" id="message" placeholder="Message" maxlength="255">
			<button type="button" id="send-btn">Send</button>
		</div>
	</div>

	<script>
		$(document).ready(function () {
			//create a new Websocket object
			var wsUri = "ws://localhost:9000/server.php";
			websocket = new WebSocket(wsUri);

			websocket.onopen = function (e) {
				$('message_box')
					.appned('<div class="system_msg">Connected!</div>');
			};

			websocket.onmessage = function (e) {
				var msg = JSON.parse(e.data);	// server sends JSON data
				var type = msg.type;
				var message = msg.message;
				var name = msg.name;
				var color = msg.color;

				if(type == 'usermsg') {
		            $('#message_box')
						.append("<div><span class='user_name' style='color:#"+color+"'>"+name+"</span> : <span class='user_message'>"+message+"</span></div>");
		        }
		        if(type == 'system') {
		            $('#message_box')
						.append("<div class='system_msg'>"+message+"</div>");
        		}
        		
				var objDiv = document.getElementById("message_box");
				objDiv.scrollTop = objDiv.scrollHeight;

				$('#message').val(''); //reset text
			};

			websocket.onerror   = function(ev){$('#message_box').append("<div class='system_error'>Error Occurred - "+ev.data+"</div>");}; 
			
			websocket.onclose   = function(ev){$('#message_box').append("<div class='system_msg'>Connection Closed</div>");}; 

			$("#send-btn").click(function () {
				
				var myname = $('#name').val();
				var mymessage = $('#message').val();

				if (myname == "") {
					alert('Enter your name please.');
					return;
				}

				if (mymessage == "") {
					alert('Enter some message please.');
					return;
				}

				document.getElementById("name").style.visibility = "hidden";
		
				var objDiv = document.getElementById("message_box");
				objDiv.scrollTop = objDiv.scrollHeight;

				var data = {
					name: myname,
					message: mymessage,
					color: '<?php echo $colours[$user_colour]; ?>'
				};

				//convert and send data to servver
				websocket.send(JSON.stringify(data));
			});
		});
	</script>
</body>

</html> 