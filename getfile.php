<?

$path=$_GET['path'];
$file=$_GET['file'];

   if (file_exists($path)) {
    header('Content-Description: File Transfer');
    header('Content-Type: application/octet-stream');
    header('Content-Disposition: attachment; filename="' . $file . '"');
    header('Expires: 0');
    header('Cache-Control: must-revalidate');
    header('Pragma: public');
    header('Content-Length: ' . filesize($path));
    readfile($path);

//
$fp = fopen("log/visits.log", "a");
//if ($_SERVER['REMOTE_ADDR']!="88.30.28.138"&&!$blocked) 
fwrite($fp, date('d-m-Y H:i:s')." ".$_SERVER['REMOTE_ADDR']." Downloading ".$path."\n");
fclose($fp);

    exit;
}
?>
