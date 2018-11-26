<?php
session_start();
if (isset($_POST["logout"])) 
session_unset();

$user = $_COOKIE["username"];
$file = basename($_GET['file']);
$file2 = "/home/admini/ftp/z7/$user123/".$file;
if(!file_exists($file2)){ // file does not exist
    die('file not found');
} else {
    header("Cache-Control: public");
    header("Content-Description: File Transfer");
    header("Content-Disposition: attachment; filename=$file");
    header("Content-Type: application/text");
    header("Content-Transfer-Encoding: binary");
    // read the file from disk
    readfile($file2);
}
?>