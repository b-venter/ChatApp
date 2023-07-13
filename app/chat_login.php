<html>
<style>

</style>
<div class="login" align="center" style="max-height: 600px; overflow: auto; border: solid; max-width: 400px; margin: auto;">
<a href='./index.html'>Home</a>
<?php
require_once('chat_init.php');
include 'chat_functions.php';

$app = new chatApp();
$settings = new chatSettings();


echo "<h2>Select your language and department below:</h2>";
echo "<br>";

$language_array = $app->getPresenceAll();
foreach ($language_array as $language => $state) {
    echo "<a href=./chat.php?user=".$language."_Sound>$language - Sound</a><br><br>";
    echo "<a href=./chat.php?user=".$language."_Video>$language - Video</a><br><br>";
}

?>
</div>
</html>
