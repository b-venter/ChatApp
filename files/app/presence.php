<?php
require_once('chat_init.php');

$link = mysqli_connect($link_server, $link_user, $link_passwd, $link_db);
if (mysqli_connect_errno()) {
    printf("Connect failed: %s\n", mysqli_connect_error());
    exit();
}

$pres_add = $_POST['add'];
$pres_remove = $_POST['del'];

if (isset ($_POST['add'])) {
   $query_add = "INSERT INTO `presence` (`presence`) VALUES ('$pres_add')";
   if (!mysqli_query($link,$query_add)) {
       die('Error: ' . mysqli_error($link));
   unset($_POST['add']);
 }
}

if (isset ($_POST['del'])) {
   $query_del = "DELETE FROM `presence` WHERE `presence` = '$pres_remove'";
   if (!mysqli_query($link,$query_del)) {
       die('Error: ' . mysqli_error($link));
    unset($_POST['del']);
 }
}

mysqli_close($link);
?>
