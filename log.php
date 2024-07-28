<?php
$urlbase="https://yourwebsite.com";
$filename="log/visits.log";
$fp = fopen($filename, "a");
fwrite($fp, date('d-m-Y H:i:s')." ".$_GET['ip']." ".$urlbase.$_GET['data']."\n");
fclose($fp);

