<?php 
require_once('chat_init.php');

?>

<!DOCTYPE html>
<html>
<head>
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Regional Convention Countdown Timer - Settings</title>
<style type="text/css">
</style>
</head>


<a href=index.html>Home</a><br><p></p>

<?php
if (isset($_POST['history'])){
    $link2 = new mysqli($link_server, $link_user, $link_passwd, $link_db);
    $clr_hist = $link2->prepare("DELETE FROM `messages`");
    if ($clr_hist->execute()){
            echo "Chat history cleared!<br>";
    }
    $clr_hist->close();
    $link2->close();
}
?>

<form method='post'>
<input type='hidden' name='history' value='history'>
<input type='submit' value='Clear chat history'>
</form>

</body>
</html>

