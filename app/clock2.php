<?php
$date = new DateTime("now", new DateTimeZone('Africa/Windhoek') );
echo $date->format('H:i:s');
?>
