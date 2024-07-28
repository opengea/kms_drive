<?php
$urlbase="https://kmscloud.com";
$filename="log/visits.log";
$fp = fopen($filename, "a");
//if ($_GET['ip']!="88.30.28.138") {
fwrite($fp, date('d-m-Y H:i:s')." ".$_GET['ip']." ".$urlbase.$_GET['data']."\n");
//}
fclose($fp);

