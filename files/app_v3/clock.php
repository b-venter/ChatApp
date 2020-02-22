<?php
$date = new DateTime("now", new DateTimeZone('Africa/Windhoek') );
echo $date->format('Y-m-d H:i:s');
?>
