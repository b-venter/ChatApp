<?php 
require_once('chat_init.php');
include 'chat_functions.php';

$colors = array('#007AFF','#FF7000','#FF7000','#15E25F','#CFC700','#CFC700','#CF1100','#CF00BE','#F00');
$color_pick = array_rand($colors);
$login_user = $_GET["user"];

$app = new chatApp();


$settings = new chatSettings();
$message_banner = $settings->getSetting("messageBoxBanner");
$countdown_banner = $settings->getSetting("timerBoxBanner");
$countdown = $settings->getSetting("timerValue");;

?>

<!DOCTYPE html>
<html>
<script>

function display_ct(){
	//var d = new Date();
	    //var x1 = d.getDay() + '/' + d.getMonth() +1 + '/' + d.getFullYear() + ' ' + d.toLocaleTimeString();
	var url = 'clock.php';
	var xmlhttp2 = new XMLHttpRequest();
	xmlhttp2.onreadystatechange=function() {
		if (xmlhttp2.readyState==4 && xmlhttp2.status==200) {
			var response = xmlhttp2.responseText;
			document.getElementById('current_time').innerHTML = response;
		}
	}
	xmlhttp2.open("GET",url,true);
        xmlhttp2.send();
	repeat = display_c();
}

function display_c(){
	showclock = setTimeout('display_ct()', 1000)
}

</script>
<head>
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Regional Convention Countdown Timer</title>
<style type="text/css">
.chat-wrapper {
	font: bold 11px/normal 'lucida grande', tahoma, verdana, arial, sans-serif;
    background: #00a6bb;
    padding: 20px;
    margin: 20px auto;
    box-shadow: 2px 2px 2px 0px #00000017;
	max-width:700px;
	min-width:500px;
}
#timer-box {
    width: 97%;
    display: inline-block;
    height: 100px;
    background: #fff;
    box-shadow: inset 0px 0px 2px #00000017;
    overflow: auto;
    padding: 10px;
}
#message-box {
    width: 97%;
    display: inline-block;
    height: 300px;
    background: #fff;
    box-shadow: inset 0px 0px 2px #00000017;
    overflow: auto;
    padding: 10px;
}
.user-panel{
    margin-top: 10px;
}
input[type=text]{
    border: none;
    padding: 5px 5px;
    box-shadow: 2px 2px 2px #0000001c;
}
input[type=text]#name{
    width:20%;
}
input[type=text]#message{
    width:60%;
}
button#send-message {
    border: none;
    padding: 5px 15px;
    background: #11e0fb;
    box-shadow: 2px 2px 2px #0000001c;
}
#table{
 display: inline;
 float: left;
}
#timerval{
  font-size: 50px;
  color: red;
}
#timer-cancel{
  font-size: 50px;
  color: red;
  display: none;
}
</style>
</head>
<body onload="display_c()">

<a href=chat_login.php>Logout</a><br><p></p>

<!--Presence-->
Please click the button of your language to change between ready and not ready:<br>
<?php
$presence_array = $app->getPresenceAll();
print "<div class='table'>";
foreach ($presence_array as $language => $state) {
    if ($state == 1) {
        print "<button id='pres_$language' onclick='pres_change(this.id)'>$language <img id='pres_image_$language' src='ready.png'></button>  ";
    }
    elseif ($state == 0) {
        print "<button id='pres_$language' onclick='pres_change(this.id)'>$language <img id='pres_image_$language' src='not_ready.png'></button>  ";
    }
}
print "</div>";
?>


<div id="current_time" style="color: red; font: 25px arial;"></div>


<!--Create message box-->
<div class="chat-wrapper">
    <div id="message-box">
    
    <!--Pull message history-->
    <?php
    $message_hist = $app->getMessageHistory();
    foreach ($message_hist as list($a, $b, $c)) {
        print "<div>
        <span style='color: red'>$a</span>
        <span style='color: gray'>$b</span>
        <span style='color: #C0C0C0'> $c</span>
        </div>";
    }
    ?>
    </div>


    <!--Username and message creation-->
    <div class="user-panel">
        <input type="text" name="name" id="name" value="<?php echo $login_user ?>" maxlength="15" />
        <input type="text" name="message" id="message" placeholder="Type your message here..." maxlength="100" />
        <button id="send-message">Send</button>
    </div>
</div>

<!--Countdown timer box-->
<div class="chat-wrapper">
    <div id="timer-box"><span id="timerval"></span><span id="timer-cancel"></span> </div>
    <button id="start-timer">Start</button><button id="reset-timer">Reset</button><button id="stop-timer">Cancel</button>
</div>

<script src="./jquery-3.7.0.min.js"></script>
<script language="javascript" type="text/javascript">  
	//create a new WebSocket object.
	var msgBox = $('#message-box');
    var timerBox = $('#timer-box');
    var ctdownBox = $('#timerval');
    var cancelBox = $('#timer-cancel');
	var wsUri = "<?php echo $websocket_uri1; ?>";
        var wsUri2 = "<?php echo $websocket_uri2; ?>";
	websocket = new WebSocket(wsUri); 
        websocket2 = new WebSocket(wsUri2);
	
	websocket.onopen = function(ev) { // connection is open 
		msgBox.append('<div class="system_msg" style="color:#bbbbbb"><?php echo $message_banner ?></div>'); //notify user
	}
    
    websocket2.onopen = function(ev) { // connection is open 
        timerBox.append('<div class="system_msg" style="color:#bbbbbb"><?php echo $countdown_banner ?></div>'); //notify user
    }
        
        //Count down from server and/or presence management
        websocket2.onmessage = function(ev) {
          var rx_a = JSON.parse(ev.data);
          var rx_type = rx_a.type;
        if (rx_type == "timer") {
             document.getElementById("timerval").innerHTML = rx_a.message;
             if (rx_a.message == 'IDLE'){
                ctdownBox.css("display", "block");
				cancelBox.css("display", "none");
             }
             
             if ($.isNumeric(rx_a.message)){
                $('#start-timer').attr('disabled','disabled');
            } else {
                $('#start-timer').removeAttr('disabled');
            }

        } else if (rx_type == "presence"){
                     var b = rx_a.name;
                     var c = 'pres_image_' + b;
                     togglePresence(c);
          }else{
             //Future usse
        }
    }

	// Instant Message received from server
	websocket.onmessage = function(ev) {
		var response 		= JSON.parse(ev.data); //PHP sends Json data
		
		var res_type 		= response.type; //message type
		var user_message 	= response.message; //message text
		var user_name 		= response.name; //user name
		var user_color 		= response.color; //color
		var d_date		= new Date();
		var days		= ["Sunday", "Monday", "Tuesday", "Wednesday", "Thursday", "Friday", "Saturday"];
		var months		= ["January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December"];
		var messg_date		= days[d_date.getDay()] + " " + months[d_date.getMonth()] + " " + d_date.getDate() + " " + d_date.getFullYear() + " " + d_date.getHours() + ":" + d_date.getMinutes() + ":" + d_date.getSeconds() + " ";

		switch(res_type){
			case 'usermsg':
				if (user_message !== null){
				msgBox.append('<div><span class="date" style="color: black;">' + messg_date + '</span><span class="user_name" style="color:' + user_color + '">' + user_name + '</span> : <span class="user_message">' + user_message + '</span></div>');
				break;
				}
			case 'system':
				if (user_message !== null){
				msgBox.append('<div style="color:#bbbbbb">' + messg_date + ' ' + user_message + '</div>');
				break;
				}
            case 'control':
				if (user_message !== null){
				ctdownBox.css("display", "none");
				cancelBox.css("display", "block");
				document.getElementById("timer-cancel").innerHTML = user_message;
				break;
				}
		}
		msgBox[0].scrollTop = msgBox[0].scrollHeight; //scroll message 
	};
	
	websocket.onerror	= function(ev){ msgBox.append('<div class="system_error">Error Occurred - ' + ev.data + '</div>'); }; 
	websocket.onclose 	= function(ev){ msgBox.append('<div class="system_msg">Connection Closed</div>'); };
	websocket2.onerror	= function(ev){ timerBox.append('<div class="system_error">Error Occurred - ' + ev.data + '</div>'); }; 
	websocket2.onclose 	= function(ev){ timerBox.append('<div class="system_msg">Connection Closed</div>'); };
	
	
	//Message send button
	$('#send-message').click(function(){
		send_message();
	});
	
	//Start and reset button
        $('#start-timer').click(function(){
		start_timer();
        });
        $('#reset-timer').click(function(){
                reset_timer();
	});
   	//Stop timer
        $('#stop-timer').click(function(){
                stop_timer();
        });


	
	//User hits enter key 
	$( "#message" ).on( "keydown", function( event ) {
	  if(event.which==13){
		  send_message();
	  }
	});


        //Toggle presence (received from server)
        function togglePresence(c){
          var d = '#' + c;
          var icon = $(d).attr('src');
          if (icon == "not_ready.png"){
             $(d).attr('src', 'ready.png');
           } else {
             $(d).attr('src', 'not_ready.png');
          }
        }

        //Presence changer (send to server)
        function pres_change(id) {
              // alert(id);
          //var k = '#' + id + '2';
              // alert(k);
          //var image = $(k).attr('src');
               //alert(image);
            var proper_id = id.replace("pres_", "");
           var pres_toggle = {
                        type: "presence",
                        message: "presence update",
                        name: proper_id,
                        color : 'black'
            };
         websocket2.send(JSON.stringify(pres_toggle));
        }



		
	//Start timer
	function start_timer(){
	  var timer_input = {
                        type: "timer",
                        message: <?php echo $countdown; ?>,
                        name: 'Server',
                        color : 'black'
         };
	 websocket2.send(JSON.stringify(timer_input));
	}


    //Reset timer
    function reset_timer(){
        var timer_reset = {
                        type: "timer",
                        message: 'IDLE',
                        name: 'Server',
                        color : 'black'
         };
         websocket2.send(JSON.stringify(timer_reset));
        }
        
    //Stop timer
    function stop_timer(){
        var timer_stop = {
                        type: "control",
                        message: 'CANCELLED',
                        name: 'Server',
                        color : 'black'
         };
         websocket.send(JSON.stringify(timer_stop));
    }
        
	//Send message
	function send_message(){
		var date_input = $('#date'); //date stamp
		var message_input = $('#message'); //user message text
		var name_input = $('#name'); //user name
		
		if(name_input.val() == ""){ //empty name?
			alert("Enter your Name please!");
			return;
		}
		if(message_input.val() == ""){ //emtpy message?
			alert("Enter Some message Please!");
			return;
		}
		//prepare json data
		var msg = {
            type: 'usermsg',
			datex: date_input.val(),
			message: message_input.val(),
			name: name_input.val(),
			color : '<?php echo $colors[$color_pick]; ?>'
		};
		//convert and send data to server
		websocket.send(JSON.stringify(msg));
		//console.log(JSON.stringify(msg));	
		message_input.val(''); //reset message input      
    } 

</script>
<script>

</script>
</body>
</html>

