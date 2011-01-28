<?php
$output = shell_exec("less /srv/www/ntyoutube.com/logs/access.log | grep watch | wc -l");
echo $output;
?>