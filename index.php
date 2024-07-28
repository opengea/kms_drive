<? 
session_start();
include "setup.php";
include "functions.php";
include "control.php";?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
	<link rel="icon" type="image/png" href="/drive/img/drive.png">
  <title>Drive</title>
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
  <style>
    .loading-icon {
        display: none;
    }
<?
$fp = fopen("style.css", "r");
$contents = fread($fp, filesize("style.css"));
echo $contents;
fclose($fp);
?>
  </style>
</head>
<body>

<header>
  <div class="logo"><img src="/drive/img/drive.png"> <div style='font-weight:normal;font-size:25px;margin-top:-45px;margin-left:55px'>Drive</div></div>
<?  if (!$_SESSION['logged'])  { ?>
  <button class="signin-btn" onclick="window.location.href='/drive/login.php<?=$add_params?>'" target="_blank"><?=$l[$lang]['_SIGN_IN']?></button>

<? } else { ?>
  <button class="signin-btn" onclick="window.location.href='/drive/logout.php'" target="_blank"><?=$l[$lang]['_LOGOUT']?></button>


<? } ?>
</header>

<?
function escape($s) {
return str_replace(" ","\ ",$s);
}

function download($file_path,$file_name) {

   if (file_exists($file_path)) {
    header('Content-Description: File Transfer');
    header('Content-Type: application/octet-stream');
    header('Content-Disposition: attachment; filename="' . $file_name . '"');
    header('Expires: 0');
    header('Cache-Control: must-revalidate');
    header('Pragma: public');
    header('Content-Length: ' . filesize($file_path));
    readfile($file_path);
    exit;
}

}
function createNavigator($currentPath) {
 global $separador, $dropdown, $sharedIcon, $add_params;
 $separador="<div class='sep'>".$separador."</div>";
 $dropdown="<div class='sep'>".$dropdown."</div>";
 $sharedIcon="<div class='icon' title='Shared'>".$sharedIcon."</div>";
 echo '<div class="navigator">';
 $split=split('/',$currentPath);
 $s="";
 $max=0; $i=1;foreach ($split as $d) { $max++; }
 
 $mypath="/drive/";//$base_path;
 foreach ($split as $d) {
  $mypath.=$d."/";
  $s.= '<div><a class="navbut" href="' . $mypath .  $add_params.'">' . $d . '</a></div>';
  if ($i<$max) $s.=$separador; else $s.=$dropdown." ".$sharedIcon;
  $i++;
 }
   $s=substr($s,0,strlen($s)-2);
    echo $s;
	echo "<div style='clear:left'></div><br><br>";
    echo '</div>';
}

function showFolders($currentPath) {
 global $add_params,$folderIcon,$blocked,$blocked_opengea;
 $s="";
 //$currentPath=substr($currentPath,1);
if (substr($currentPath,-1)==="/") $currentPath = substr($currentPath,0,strlen($currentPath)-1);
 
$folders = glob($currentPath . '/*', GLOB_ONLYDIR);
 foreach ($folders as $folder) {
    $folderName = basename($folder);
	$base_path="/drive/";
    if ($blocked&&$folderName=='privat') { } 
    else if ($folderName=='Opengea'&&$blocked_opengea) { 
    } else {

  $s.= '<div class="folder" data-link="'.$base_path . $folder . $add_params.'/"><a href="'.$base_path.$folder .$add_params. '"><div class="icon left">' . $folderIcon. ' </div><div class="foldername left">'.$folderName . '</div></a></div>';

    }
 }
   $s=substr($s,0,strlen($s)-2);
  if ($s) echo '<div class="folders"><b>Folders</b><br>'.$s.'</div></div></div>';

}

?>
<div class="navigator toolbar">
<? 

$directory = 'content';
$currentPath = isset($path) ? urldecode($path) : $directory;
if (strpos($currentPath,"privat")) $downloads=false; else $downloads=true;
   // createNavigator($currentPath); 
    createNavigator($currentPath); 
?>
</div>

<table border=0 style="width:100%"><tr>
<div class="left" id="content-description"><h2><?=$l[$lang]['_CONTENTS_OF']?> <?=$currentPath?> </h2></div>
<div class="right" id='down' style='display:none'><div id='download_zip' data-folder-path='<?=$currentPath?>'><?=$l[$lang]['_DOWNLOAD_ALL']?></div>
<div id="loading_icon" class="loading-icon"><i class="fas fa-spinner fa-spin"></i> <?=$l[$lang]['_PREPARING_ARCHIVE']?></div></div>
<div style="clear:left"><br></div>
<?
    $directory=$currentPath;
    showFolders($directory);
    $s="";
    $files = scandir($currentPath);
    $files = array_diff($files, array('.', '..'));
    sort($files);
    $list_images = "";
    $index=0;
    foreach ($files as $file) {
	if (substr($file,0,1)==".") {
		if ($file==".public") $show_files=true; else $show_files=true;
		if ($_SESSION['logged']) $show_files=true;
	 } else { //ignoring hidden files 
	 $filePath = $currentPath . '/' . $file;
	 if (is_file($filePath)) {
	 $baseurl=$base_path."/".$currentPath;
	 $url=$baseurl."/".$file;
//$url=$base_path."/".$file;
         $list_images.="'".$file."',";
	 $filename=substr($file,0,strpos($file,"."));
	 $ext=strtolower(substr($file,strpos($file,".")+1));
	 if ($ext=="jpeg"||$ext=="jpg"||$ext=="gif"||$ext=="png") $ext="pic";
	 else if ($ext=="xls") $ext="xls";
	 else if ($ext=="pdf") $ext="pdf";
         else $ext="doc";
	//video // dolder // audio // draw // zip // presentacions // forms // sites
	$pic=$url;
	if ($ext=="pdf") {
		if (!file_exists("..".$baseurl."/.thumbs/".$filename.".png")) {
		mkdir("..".$baseurl."/.thumbs/");
		exec("convert -thumbnail x800 ../".$baseurl."/".$file."[0] ../".$baseurl."/.thumbs/".$filename.".png");
		}
		$pic=$baseurl."/.thumbs/".$filename.".png";	
	} else if ($ext=="doc"||$ext=="docx"||$ext=="xls") {
		if (!file_exists("..".$baseurl."/.thumbs/".$filename.".png")) {
		mkdir("..".$baseurl."/.thumbs/");
		exec("libreoffice --headless --convert-to png --outdir ..".$baseurl."/.thumbs/ ../".$baseurl."/".escape($file));
		}
		$pic=$baseurl."/.thumbs/".$filename.".png";
	}

	$s.='<div class="file-container">';
	$s.='<div class="h"><div class="l"><img src="/drive/img/'.$ext.'.png"></div><div class="t">'.$filename.'</div><div class="b" path="'.$filePath.'" file="'.$file.'">'.$menu.'</div></div>';
	if ($ext=="pic") $s.='<div class="clear image-cointainer image-item" onclick="openLightbox(\''.$url.'\','.$index.')">
    <img src="'.$pic.'" alt="'.$file.'">
    <div class="image-overlay"></div>
</div>'; else $s.='<div class="clear image-cointainer image-item" onclick="document.location=\''.$url.'\'">
    <img src="'.$pic.'" alt="'.$file.'">
    <div class="image-overlay"></div>
</div>';
	$s.='</div>';
        $index++;
	}
   }
  }
if ($visible) {

$list_images=substr($list_images,0,strlen($list_images)-1);

if ($s) echo '<div class="clear:left"><b>'.$l[$lang]['_FILES'].'</b></div><br><br>
<div class="gallery image-grid">
'.$s.'</div>
</div>';

if ($s&&$downloads) echo '<script>
$("#down").show();
</script>';
?>

<script>
var images = [<?=$list_images?>];
var currentIndex=1;
var hasScrolled = false;

document.addEventListener('DOMContentLoaded', function() {
    var folders = document.querySelectorAll('.folder');
    folders.forEach(function(folder) {
        folder.addEventListener('click', handleEventClick);
        folder.addEventListener('touchend', handleEventTouchend);
    });
    // Scroll event handler
    const scrollHandler = () => {
        hasScrolled = true;
    };

    // Add scroll event listener to the window
    window.addEventListener("scroll", scrollHandler);

});

function handleEventClick(event) {

    event.preventDefault();
    var link = event.currentTarget.getAttribute('data-link');
    window.location.href = link;
    hasScrolled = false;
}

function handleEventTouchend(event) {

    event.preventDefault();
    var link = event.currentTarget.getAttribute('data-link');
    if (!hasScrolled) window.location.href = link;
    hasScrolled = false;
}


</script>
<? } ?>
</div>

<!-- Lightbox Container -->
<div class="lightbox" id="lightbox" onclick="closeLightbox()">
  <div class="lightbox-nav">
   <div class="image-overlay"></div>
    <span class="lightbox-prev" onclick="prevImage()">&#10094;</span>
    <img src="" alt="Preview">
    <span class="lightbox-next" onclick="nextImage()">&#10095;</span>
  </div>
 <div class='index' style='position:absolute;bottom:0px;color:#fff'>x</div>
</div>

<div id="floatingMenu" class="hidden">
    <ul>
      <? if ($downloads) { ?>  <li id="downLink"><div class='ico'><?=$downloadIcon?></div><div class='text'>Download</div></li> <? } ?>
    </ul>
</div>

<script>

 const menuOpt = document.getElementById('menuOpt');
   const menus = document.querySelectorAll('.b');
    menus.forEach(function (div) {
        div.addEventListener('click', function () {
		const floatingMenu = document.getElementById('floatingMenu');
	var file= this.attributes['file'].value;
	var path= this.attributes['path'].value;
 		floatingMenu.style.top = event.clientY +55 + 'px';
	var addx = 105;
        if (event.clientX+300>window.innerWidth) addx = -100;
        floatingMenu.style.left = event.clientX + addx + 'px';
		 const downLink = document.getElementById('downLink');
		 var myClickHandler = function() {
			const floatingMenu = document.getElementById('floatingMenu');
			 console.log('hide');
//			floatingMenu.style.display = 'none';
			download(path,file);
			floatingMenu.classList.toggle('visible');
//			floatingMenu.style.height = '0px';
		};
		 downLink.removeEventListener('click', myClickHandler);
		 downLink.addEventListener('click', myClickHandler);
		floatingMenu.classList.toggle('hidden');
		//floatingMenu.style.display = 'block';
//		floatingMenu.style.height = '200px';

        });
    });

//document.body.addEventListener('click', function() { floatingMenu.style.display = 'none'; console.log('hide'); });
function removeAllEventListeners(element) {
    var clone = element.cloneNode(true);
    element.parentNode.replaceChild(clone, element);
console.log('removed');
}
 function download(path,file) {
  //   var xhr = new XMLHttpRequest();
     var url = "//kmscloud.com/drive/getfile.php?path="+path+"&file="+file;
   console.log(url);
//	xhr.open("GET", url, true);
	window.open(url, '_self');
	const floatingMenu = document.getElementById('floatingMenu');
	floatingMenu.classList.toggle('hidden');
//	xhr.onreadystatechange = function () {
 /*   if (xhr.readyState == 4 && xhr.status == 200) {
        // Handle the response here
        console.log('response:'+xhr.responseText);
    }
   } */
}

 function log(mydata) {

var xhr = new XMLHttpRequest();
var url = "/drive/log.php";
var data = 'data=' + encodeURIComponent(mydata)+ '&ip=<?=$_SERVER['REMOTE_ADDR']?>'; 
xhr.open("GET", url + '?' + data, true);
xhr.onreadystatechange = function () {
    if (xhr.readyState == 4 && xhr.status == 200) {
        console.log('saved ' + mydata);
    } else if (xhr.readyState == 4) {
        console.log(xhr.status);
    }
};

xhr.send();


}

  function openLightbox(imageSrc,index) {
    currentIndex=index;
    log(imageSrc);
    document.getElementById('lightbox').style.display = 'flex';
    document.querySelector('.lightbox img').src = imageSrc;
    document.querySelector('.index').innerText=currentIndex;
  }

  function closeLightbox() {
    document.getElementById('lightbox').style.display = 'none';
  }

function openNext() {
	openLightbox('<?=$base_path?>/<?=$currentPath?>/'+images[currentIndex],currentIndex);
 }

function updateLightboxImage() {
//    const lightboxImage = document.querySelector('.lightbox img');
  //  lightboxImage.src = '<?=$currentPath?>/'+images[currentIndex];
 // console.log(lightboxImage.src);
    setTimeout(openNext,100);
  }

  function prevImage() {
    currentIndex = (currentIndex - 1 + images.length) % images.length;
    updateLightboxImage();
  }

  function nextImage() {
    currentIndex = (currentIndex + 1) % images.length;
    updateLightboxImage();
  }


$(document).ready(function(){
    $('#download_zip').click(function(){

	$('#download_zip').hide();
	$('#loading_icon').show();	
        var button = document.getElementById('download_zip');

    		var folderPath = button.getAttribute('data-folder-path');
    		var url = '<?=$base_path?>/zip-folder.php?folderPath=' + encodeURIComponent(folderPath);
    		window.open(url, '_self');
		setTimeout(function() {
                    $('#loading_icon').hide();
		    $('#download_zip').show();
                }, 5000); 


});

});

window.addEventListener('scroll', function() {
  var floatingMenu = document.getElementById('floatingMenu');
  
  // If the user has scrolled down more than 100 pixels, hide the floating menu
  if (window.scrollY > 100) {
    floatingMenu.classList.add('hidden');
  }
});

</script>
<br><br>
<? if ($path!="content") { ?>
<div class="grey folder" data-link="//kmscloud.com/drive/content<?=$add_params?>"><a href="//kmscloud.com/drive/content/habitatges/<?=$add_params?>"><div class="icon left"><?=$back?> </div><div class="foldername left"><?=$l[$lang]['_BACK']?></div></a></div>
<? } ?>
</body>
</html>

