<?
// Folder to zip
$data = array(
        'UNIQUE_ID' => $_SERVER['UNIQUE_ID'],
        'REMOTE_ADDR' => $_SERVER['REMOTE_ADDR'],
        'HTTP_USER_AGENT' => $_SERVER['HTTP_USER_AGENT'],
        'HTTP_ACCEPT_LANGUAGE' => $_SERVER['HTTP_ACCEPT_LANGUAGE'],
        'HTTP_REFERER' => $_SERVER['HTTP_REFERER'],
        'REQUEST_URI' => $_SERVER['REQUEST_URI'],
        'DATE' => date('d-m-Y H:i:s')

);
$fp = fopen("log/visits.log", "a");
if ($_SERVER['REMOTE_ADDR']!="188.30.28.138") fwrite($fp, print_r($data,true));
fclose($fp);

$_POST['folderPath']=$_GET['folderPath'];

$folderPath = isset($_POST['folderPath']) ? $_POST['folderPath'] : '';

if (!empty($folderPath)) {
    // Path to the folder to be zipped
    $folderPath = rtrim($folderPath, '/') . '/'; // Ensure folder path ends with a slash

 // Create a temporary directory for the zip file
    $tempDir = sys_get_temp_dir() . '/';
	$tempDir = "tmp/";
// Output zip file name
$zipFileName = tempnam($tempDir, 'archive_') . '.zip';
$zipFileName = $tempDir.'archive.zip';
// Create a zip archive
$zip = new ZipArchive();
if ($zip->open($zipFileName, ZipArchive::CREATE | ZipArchive::OVERWRITE) !== true) {
    exit("Cannot create a zip file.");
}

// Add files from the folder to the zip archive
$files = new RecursiveIteratorIterator(
    new RecursiveDirectoryIterator($folderPath),
    RecursiveIteratorIterator::LEAVES_ONLY
);

foreach ($files as $name => $file) {
    // Skip directories (we only want files)
    if (!$file->isDir()) {
        $filePath = $file->getRealPath();
        // Relative path to the folder
        $relativePath = substr($filePath, strlen($folderPath) + 1);
        // Add file to zip
        $zip->addFile($filePath, $relativePath);
    }
}

// Close the zip
$zip->close();

// Download the zip file
header('Content-Type: application/zip');
header('Content-disposition: attachment; filename=' . $zipFileName);
header('Content-Length: ' . filesize($zipFileName));
readfile($zipFileName);

// Delete the zip file after download
unlink($zipFileName);

} else {
    // Error: Folder path is empty or not provided
    http_response_code(400);
    echo "Folder path is empty or not provided. folderPath=".$folderPath." tempDir=".$tempDir." zipFileName=".$zipFileName;
}

