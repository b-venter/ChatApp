<?php 
require_once('chat_init.php');
include 'chat_functions.php';

$app = new chatApp();
$settings = new chatSettings();

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

<!---CLEAR MESSAGES-->
<?php
$clr_message = "";
if (isset($_POST['history'])){
    $clear = $app->clearMessageHistory();
    if ($clear == true){
        $clr_message = "All messages cleared.";
    } else {
        $clr_message = "Error clearing messages.";
    }
}

?>
<form id="clear_form" method='post'>
<input type='hidden' name='history' value='history'>
<input type='submit' value='Clear chat history'>
</form>
<div id="history_clear"><?php echo $clr_message; ?></div>
<br><br>


<!---SET BANNERS-->
<?php 
$message_banner = $settings->getSetting('messageBoxBanner');
$banner_error = "";
if (isset($_POST['banner_chat'])){
    $chattext = $_POST['banner_chat'];
    $chatban = $settings->setSetting("messageBoxBanner", $chattext);
    if ($chatban == true) {
        $banner_error = "Successfully set banner";
    } else {
        $banner_error = "Error when setting banner";
    }
} 

$countdown_banner = $settings->getSetting('timerBoxBanner');
$timer_error = "";
if (isset($_POST['banner_countdown'])){
    $timertext = $_POST['banner_countdown'];
    $timerban = $settings->setSetting("timerBoxBanner", $timertext);
    if ($timerban == true) {
        $timer_error = "Successfully set banner";
    } else {
        $timer_error = "Error when setting banner";
    }
}

?>
<form id="banner_form" method='post'>
Chat Banner: <input type='text' name='banner_chat' value='<?php echo $message_banner ?>'><br>
Countdown Banner: <input type='text' name='banner_countdown' value='<?php echo $countdown_banner ?>'><br>
<input type='submit' value='Set Banners'>
</form>
<div id="banner_set"><?php echo "$banner_error | $timer_error";?></div>
<br><br>


<!---SET LANGUAGES-->
<?php
$language_array = $app->getPresenceAll();
print "<form id='lang_form' method='post'>";
foreach ($language_array as $language => $state) {
    print "<input type='text' name='input_$language' value='$language'><br>";
}
print "<input type='submit' value='Submit'>";
print "</form>";
?>
<br><br>

<!---SET COUNTDOWN IN SECONDS-->
<?php
$countdown_timer = $settings->getSetting('timerValue');
$timer2_error = "";
if (isset($_POST['timer_countdown'])){
    $timernumber = $_POST['timer_countdown'];
    $timer_x = $settings->setSetting("timerValue", $timernumber);
    if ($timer_x == true) {
        $timer2_error = "Successfully set timer";
    } else {
        $timer2_error = "Error when setting timer";
    }
}
?>
<form id="timer_form" method='post'>
Countdown Timer: <input type='text' name='timer_countdown' value='<?php echo $countdown_timer ?>'> seconds <br>
<input type='submit' value='Set Timer (s)'>
</form>
<div id="timer_set"><?php echo "$timer2_error";?></div>
<br><br>


</body>
</html>

