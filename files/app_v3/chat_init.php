<?php 
//MySQL Connection
$link_server = "localhost";
$link_user = "avenger";
$link_passwd = "password";
$link_db = "chatapp";

//Server IP
//
//******-----------------------------**********//
//**Example of specifying IP address manually:
$local_ip = '172.16.12.79';
//Auto detect:
//$local_ip = gethostbyname(gethostname());

//Websocket server 1 - service settings
$websocket_host1 = 'localhost';
$websocket_port1 = 9000;

//Websocket server 1 - URI settings
$websocket_srv1 = $local_ip;
$websocket_uri1 = "ws://$websocket_srv1:$websocket_port1/server.php";

//Websocket server 2 - service settings
$websocket_host2 = 'localhost';
$websocket_port2 = 9001;

//Websocket server 2 - URI settings
$websocket_srv2 = $local_ip;
$websocket_uri2 = "ws://$websocket_srv2:$websocket_port2/server2.php";
?>
