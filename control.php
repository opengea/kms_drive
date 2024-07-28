<?
$downloads=false;
$currentUserIP = $_SERVER['REMOTE_ADDR'];
$blocklist = 'block.lst';
$blocked_ips = file($blocklist, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
$blocked_all=false;//true;//false;//true;//false;

$lang=substr($_SERVER['HTTP_ACCEPT_LANGUAGE'],0,2);

if (strpos($_SERVER['REQUEST_URI'],"log")) { exit;}
if ($_GET['path']) {
$path=$_GET['path'];
$_GET['path']="";
}

if (strpos(" ".$_SERVER['HTTP_USER_AGENT'],"TelegramBot")||strpos(" ".$_SERVER['HTTP_USER_AGENT'],"facebook")) $blocked_all=true;

if (substr(" ".$_SERVER['HTTP_USER_AGENT'],"facebookexternalhit")) {
	$blocked=true;
	$fp = fopen("block.lst", "a");
	fwrite($fp,$_SERVER['REMOTE_ADDR']);
	fclose($fp);
}

if ($blocked_all) $blocked=true;

foreach ($blocked_ips as $blocked_ip) {
    if (strpos($currentUserIP, $blocked_ip) !== false) {
        $blocked=true;
    }
}

if (in_array($currentUserIP, $blocked_ips)||$blocked_all) {
    $blocked=true;
    if (strpos($_SERVER['REQUEST_URI'],"privat")) header('location:'.$base_path.'/');

}


$data = array(
	'UNIQUE_ID' => $_SERVER['UNIQUE_ID'],
	'REMOTE_ADDR' => $_SERVER['REMOTE_ADDR'],
	'HTTP_USER_AGENT' => $_SERVER['HTTP_USER_AGENT'],
        'HTTP_ACCEPT_LANGUAGE' => $_SERVER['HTTP_ACCEPT_LANGUAGE'],
	'HTTP_REFERER' => $_SERVER['HTTP_REFERER'],
	'REQUEST_URI' => $_SERVER['REQUEST_URI'],
	'DATE' => date('d-m-Y H:i:s')	
	
);

if ($_GET['resourcekey']) {
	$_GET['resourcekey']=str_replace("/","",$_GET['resourcekey']);
	$add_params="?resourcekey=".$_GET['resourcekey'];
	//get the code and user id
	$user=validatekey($_GET['resourcekey']);
	$data['USER']=$user;
	//validate only resoucekey links
	if ($user!="")  {
	$blocked_all=false;
	$blocked=false;
	}

	else { $blocked_opengea=true; }

}
if ($_GET['resourcekey']=="")  $blocked_opengea=true;
$visible=true;


$fp = fopen("log/visits.log", "a");
if (!$blocked) fwrite($fp, print_r($data,true));

fclose($fp);

