<?php
session_start();
$cookie_value = $_POST['user'];
setcookie("username", $cookie_value, time() + (86400 * 30), "/");
?>

<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="pl" lang="pl">
<HEAD>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Gawron - Zadanie 7</title>
</HEAD>
<BODY>
<?php

$user=$_POST['user'];
$pass=$_POST['pass'];

$dbhost="sql.admini.nazwa.pl";
$dbuser="admini_Zadanie7";
$dbpassword="Statystyka1!";
$dbname="admini_Zadanie7";

$link = mysqli_connect($dbhost, $dbuser, $dbpassword, $dbname);

if(!$link) {echo"Błąd: ". mysqli_connect_errno()." ".mysqli_connect_error();}
mysqli_query($link, "SET NAMES 'utf8'");

$result = mysqli_query($link, "SELECT * FROM `users` WHERE nazwisko='$user' AND haslo='$pass'"); 
$rekord = mysqli_fetch_array($result);

if(!$rekord['haslo'])
{
	$result12 = mysqli_query($link, "SELECT * FROM `users` WHERE nazwisko='$user'"); 
		$wynik1 = mysqli_fetch_array($result12);
		$idu = $wynik1['idu'];
		$ip = $_SERVER['REMOTE_ADDR'];
		$licznik1 = $wynik1['licznik1'] + 1;
		$result22 = mysqli_query($link, "INSERT INTO `logi`(`idu`, `ip`) VALUES ('$idu', '$ip')");
		$result23 = mysqli_query($link, "UPDATE `users` SET `licznik1`= '$licznik1' WHERE idu LIKE '$idu'");
		mysqli_close($link);
	echo "Błędne hasło!";
}
else
{
	if($rekord['haslo']==$pass)
	{
		
	$ip = $_SERVER['REMOTE_ADDR'];
	
	$result1 = mysqli_query($link, "SELECT idu FROM `users` WHERE nazwisko='$user' AND haslo='$pass'"); 
	$wynik = mysqli_fetch_array($result1);
	$idu = $wynik['idu'];
	
	$licznik = 0;
	$result2 = mysqli_query($link, "SELECT licznik1 FROM `users` WHERE idu LIKE '$idu'");
	$wynik2 = mysqli_fetch_array($result2);
	$licz = $wynik2['licznik1'];
	
	$result4 = mysqli_query($link, "SELECT dataczas FROM `logi` WHERE idu LIKE '$idu'");
	$wynik4 = mysqli_fetch_array($result4);
	$dataczas = $wynik4['dataczas'];
	
	$result3 = mysqli_query($link, "UPDATE `users` SET `licznik1`=0 WHERE idu LIKE '$idu'");
	
	if($licz==0){
	echo "Logowanie przebiegło pomyślnie. User: <span style=\"color: green\">{$rekord['nazwisko']}</span>. Hasło: {$rekord['haslo']}<br/>";
	echo "Ostatnie błędne logowanie: ". $dataczas .'<br/>';
	echo "Ilość błędnych prób: " .$licz.'<br/>';
	
	}
	else {
		echo "Logowanie przebiegło pomyślnie. User: <span style=\"color: red\"> {$rekord['nazwisko']}</span>. Hasło: {$rekord['haslo']}<br/>";
		echo "Ostatnie błędne logowanie: ". $dataczas .'<br/>';
		echo "Ilość błędnych prób: " .$licz.'<br/>';
	}
	
	echo '<a href="logout.php">Wyloguj się</a>';
	echo '<br/>'."Twoje ID: ".$idu.'<br/>';
	echo "Twój IP: ".$ip.'<br/>';
	echo "Wartość ciastka: " . $_COOKIE["username"].'<br/>';
	echo '<a href="wyslij.html">Wyślij plik</a>';
	$user123 = $_COOKIE["username"];
	
	
	//Wyświetl pliki i foldery
 
// Create recursive dir iterator which skips dot folders
$dir = new RecursiveDirectoryIterator("/home/admini/ftp/z7/$user123",
    FilesystemIterator::SKIP_DOTS);
 
// Flatten the recursive iterator, folders come before their files
$it  = new RecursiveIteratorIterator($dir,
    RecursiveIteratorIterator::SELF_FIRST);
 
// Maximum depth is 1 level deeper than the base folder
$it->setMaxDepth(1);
 
// Basic loop displaying different messages based on file or folder
foreach ($it as $fileinfo) {
    if ($fileinfo->isDir()) {
        printf("<br/><form method='POST'>Folder - %s\n<br/>", $fileinfo->getFilename());
    } elseif ($fileinfo->isFile()) {
            if($it->getSubPath() === ''){
                    printf("<br/><form method='POST'>%s 
					<a href='download.php?file=%s'>Pobierz</a>
					<input type='hidden' name='delete_file' value='%s'>
					<input type='submit' value='Usuń'></form>\n", 
					$fileinfo->getFilename(),$fileinfo->getFilename(), $fileinfo->getFilename());
            }else{
                    printf("<br/><form method='POST'>%s -> %s 
					<a href='download.php?file=%s/%s'>Pobierz</a>
					<input type='hidden' name='delete_file' value='%s/%s'>
					<input type='submit' value='Usuń'></form>\n", 
					$it->getSubPath(), $fileinfo->getFilename(),$it->getSubPath(), 
					$fileinfo->getFilename(), $it->getSubPath(), $fileinfo->getFilename());
            }
        }
}

	#Usuń plik $file
	if (isset($_POST['delete_file'])){
        $file=$_POST['delete_file'];
        $file="../pliki/$user/$file";
        if (unlink($file)) {   echo 'Usunięto pomyślnie '.$_POST['delete_file']; }
        else { echo 'Błąd usuwania pliku - ' . $_POST['delete_file'] . ' - ścieżka - ' . $file; }
}

	
	}
	else
	{
		$result12 = mysqli_query($link, "SELECT idu FROM `users` WHERE nazwisko='$user' AND haslo='$pass'"); 
		$wynik1 = mysqli_fetch_array($result12);
		$idu = $wynik1['idu'];
		
		$licznik = $licznik + 1;
		$result22 = mysqli_query($link, "INSERT INTO `logi`(`idu`, `ip`, `licznik`) VALUES ('$idu', '$ip', '$licznik')");
		
		mysqli_close($link);
		echo "Błąd w haśle !";
	}
}
?>

</BODY>
</HTML>