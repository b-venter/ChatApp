<?php 
require_once('chat_init.php');

$colors = array('#007AFF','#FF7000','#FF7000','#15E25F','#CFC700','#CFC700','#CF1100','#CF00BE','#F00');
$color_pick = array_rand($colors);
$login_user = $_GET["user"];
$presence_array = array();


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
</style>
</head>
<body onload="display_c()">

<a href=chat_login.php>Logout</a><br><p></p>

Please click the button of your language to change between ready and not ready:<br>
<?php
$link = mysqli_connect($link_server, $link_user, $link_passwd, $link_db);
if (mysqli_connect_errno()) {
    printf("Connect failed: %s\n", mysqli_connect_error());
    exit();
}

$query_all = "SELECT DISTINCT * FROM `presence`";
$get_query = mysqli_query($link, $query_all);
while ($first_array = mysqli_fetch_array($get_query)){
  $all_array[] = $first_array[0];
}


print "<div class='table'>";

if (in_array('#pres_image_af2', $all_array)){
  print "<button id='pres_image_af'>Afrikaans <img id='pres_image_af2' src='ready.png'></button>  ";
} else {
  print "<button id='pres_image_af'>Afrikaans <img id='pres_image_af2' src='not_ready.png'></button>  ";
}
if (in_array('#pres_image_en2', $all_array)){
  print "<button id='pres_image_en'>English <img id='pres_image_en2' src='ready.png'></button>  ";
} else {
  print "<button id='pres_image_en'>English <img id='pres_image_en2' src='not_ready.png'></button>  ";
}
if (in_array('#pres_image_wg2', $all_array)){
  print "<button id='pres_image_wg'>Kwangali <img id='pres_image_wg2' src='ready.png'></button>  ";
} else {
  print "<button id='pres_image_wg'>Kwangali <img id='pres_image_wg2' src='not_ready.png'></button>  ";
}
if (in_array('#pres_image_ky2', $all_array)){
  print "<button id='pres_image_ky'>Kwanyama <img id='pres_image_ky2' src='ready.png'></button>  ";
} else {
  print "<button id='pres_image_ky'>Kwanyama <img id='pres_image_ky2' src='not_ready.png'></button>  ";
}
if (in_array('#pres_image_pt2', $all_array)){
  print "<button id='pres_image_pt'>Portuguese <img id='pres_image_pt2' src='ready.png'></button>  ";
} else {
  print "<button id='pres_image_pt'>Portuguese <img id='pres_image_pt2' src='not_ready.png'></button>  ";
}
print "</div>";
mysqli_free_result($get_query);
mysqli_close($link);
?>
<div id="current_time" style="color: red; font: 25px arial;"></div>
<div class="chat-wrapper">
<div id="message-box">
	<!--Pull message history-->
<?php
$link2 = new mysqli($link_server, $link_user, $link_passwd, $link_db);
$mess_hist = $link2->prepare("SELECT `sender`, `message`, DATE_FORMAT(`time`, '%Y-%m-%d %H:%i') AS `formatted_date` FROM `messages`");
$mess_hist->execute();
$mess_hist->bind_result($sender, $texta, $datea);
$mess_hist->store_result();
while ($mess_hist->fetch()){
	print "<div>
		<span style='color: red'>$datea</span>
		<span style='color: magenta'>$sender</span>
		<span style='color:pink'> $texta</span>
		</div>";
}
$mess_hist->free_result();
$mess_hist->close();
$link2->close();
?>
</div>
<div class="user-panel">
<input type="text" name="name" id="name" value="<?php echo $login_user ?>" maxlength="15" />
<input type="text" name="message" id="message" placeholder="Type your message here..." maxlength="100" />
<button id="send-message">Send</button>
</div>
</div>
<div class="chat-wrapper">
<div id="timer-box"><span id="timerval"></span> </div>
<button id="start-timer">Start</button><button id="reset-timer">Reset</button><button id="stop-timer">Cancel</button>
</div>

<script src="./jquery-3.3.1.min.js"></script>
<script language="javascript" type="text/javascript">  
	//create a new WebSocket object.
	var msgBox = $('#message-box');
        var timerBox = $('#timer-box');
	var wsUri = "<?php echo $websocket_uri1; ?>";
        var wsUri2 = "<?php echo $websocket_uri2; ?>";
	websocket = new WebSocket(wsUri); 
        websocket2 = new WebSocket(wsUri2);
	
	websocket.onopen = function(ev) { // connection is open 
		msgBox.append('<div class="system_msg" style="color:#bbbbbb">Welcome to RC2018 AV Chat box!</div>'); //notify user
	}
        websocket2.onopen = function(ev) { // connection is open 
                timerBox.append('<div class="system_msg" style="color:#bbbbbb">Welcome to RC2018 AV Coundown Timer!</div>'); //notify user
        }
        
        //Count down from server, presence management
        websocket2.onmessage = function(ev) {
          var rx_a = JSON.parse(ev.data);
          var rx = rx_a.message;
          if (rx == "IDLE") {
             document.getElementById("timerval").innerHTML = "IDLE";
          } else if (rx == "PRESENCE"){
                     var b = rx_a.name;
                     var c = '#' + b + '2';
                     togglePresence(c);
          }else{ //Countdown timer received
             document.getElementById("timerval").innerHTML = rx;
        }
    }

	// Message received from server
	websocket.onmessage = function(ev) {
		var response 		= JSON.parse(ev.data); //PHP sends Json data
		
		var res_type 		= response.type; //message type
		var user_message 	= response.message; //message text
		var user_name 		= response.name; //user name
		var user_color 		= response.color; //color
		var messg_date		= new Date();

		switch(res_type){
			case 'usermsg':
				msgBox.append('<div><span class="date" style="color: black;">' + messg_date + '</span><span class="user_name" style="color:' + user_color + '">' + user_name + '</span> : <span class="user_message">' + user_message + '</span></div>');
				break;
			case 'system':
				msgBox.append('<div style="color:#bbbbbb">' + messg_date + ' ' + user_message + '</div>');
				break;
		}
		msgBox[0].scrollTop = msgBox[0].scrollHeight; //scroll message 
	};
	
	websocket.onerror	= function(ev){ msgBox.append('<div class="system_error">Error Occurred - ' + ev.data + '</div>'); }; 
	websocket.onclose 	= function(ev){ msgBox.append('<div class="system_msg">Connection Closed</div>'); }; 
	//Message send button
	$('#send-message').click(function(){
		send_message();
	});
	//Start and reset button
	var x_timer;
        $('#start-timer').click(function(){
		start_timer();
        });
        $('#reset-timer').click(function(){
                reset_timer();
	});
   	//Stop timer
        $('#stop-timer').click(function(){
                clearInterval(x_timer);
        });

        //Presence buttons
        $('#pres_image_af').click(function(){
                pres_change(this.id);
        });
        $('#pres_image_en').click(function(){
                pres_change(this.id);
        });
        $('#pres_image_wg').click(function(){
                pres_change(this.id);
        });
        $('#pres_image_ky').click(function(){
                pres_change(this.id);
        });
        $('#pres_image_pt').click(function(){
                pres_change(this.id);
        });



	
	//User hits enter key 
	$( "#message" ).on( "keydown", function( event ) {
	  if(event.which==13){
		  send_message();
	  }
	});


        //Toggle presence
        function togglePresence(c){
          var icon = $(c).attr('src');
          if (icon == "not_ready.png"){
             $(c).attr('src', 'ready.png');
             //Pass ready value via AJAX to presence.php to add to array
             $.ajax({
                type: 'post',
                url: 'presence.php',
                data: {
                  'add': c
                 }
             });
           } else {
             $(c).attr('src', 'not_ready.png');
             //Pass ready value via AJAX to presence.php to add to array
             $.ajax({
                type: 'post',
                url: 'presence.php',
                data: {
                  'del': c
                 }
             });
          }
        }

        //Presence changer
        function pres_change(id) {
              // alert(id);
          var k = '#' + id + '2';
              // alert(k);
          var image = $(k).attr('src');
               //alert(image);
           var pres_toggle = {
                        message: "PRESENCE",
                        name: id,
                        color : 'black'
            };
         websocket2.send(JSON.stringify(pres_toggle));
        }



		
	//Start timer
	function start_timer(){ //test if already running
	  var t1 = 10 * 1000;
          // Set the date we're counting down to
          var countDownDate = new Date().getTime() + t1;

          // Update the count down every 1 second
          var x_timer = setInterval(function() {

          // Get todays date and time
          var now = new Date().getTime();

          // Find the distance between now and the count down date
          var distance = countDownDate - now;

          // Time calculations for days, hours, minutes and seconds
          //var days = Math.floor(distance / (1000 * 60 * 60 * 24));
          //var hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
          //var minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
          var seconds = Math.floor((distance % (1000 * 60)) / 1000);

          // If the count down is over, write some text
          if (seconds <= 0) {
             clearInterval(x_timer);
             seconds = "PLAY";
	  }
         
	  var timer_input = {
                        message: seconds,
                        name: 'Server',
			color : 'black'
         };
	 websocket2.send(JSON.stringify(timer_input));
	  }, 1000);
	}


        //Reset timer
        function reset_timer(){
         var timer_reset = {
                        message: 'IDLE',
                        name: 'Server',
                        color : 'black'
         };
         websocket2.send(JSON.stringify(timer_reset));
        }
	//Send message
	function send_message(){
		var date_input = $('#date'); //date stamp
		var message_input = $('#message'); //user message text
		var name_input = $('#name'); //user name
		
		if(message_input.val() == ""){ //empty name?
			alert("Enter your Name please!");
			return;
		}
		if(message_input.val() == ""){ //emtpy message?
			alert("Enter Some message Please!");
			return;
		}
		//prepare json data
		var msg = {
			datex: date_input.val(),
			message: message_input.val(),
			name: name_input.val(),
			color : '<?php echo $colors[$color_pick]; ?>'
		};
		//convert and send data to server
		websocket.send(JSON.stringify(msg));	
		message_input.val(''); //reset message input      
    } 

</script>
<script>

</script>
</body>
</html>

