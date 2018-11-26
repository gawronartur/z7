<?php
session_start();
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
$pass1=$_POST['pass1'];

if(strlen($user)<4){
	echo $user.'<br/>';
	echo strlen($user);
	exit("Login min 4 znakowy");
}

if($pass!=$pass1){
	exit("Proszę podać takie same hasła");
}

$dbhost="sql.admini.nazwa.pl";
$dbuser="admini_Zadanie7";
$dbpassword="Statystyka1!";
$dbname="admini_Zadanie7";

$link = mysqli_connect($dbhost, $dbuser, $dbpassword, $dbname);

if(!$link) { echo"Błąd: ". mysqli_connect_errno()." ".mysqli_connect_error(); }
mysqli_query($link, "SET NAMES 'utf8'");

$result5 = mysqli_query($link, "SELECT * FROM `users` WHERE nazwisko='$user'");
$rekord5 = mysqli_fetch_array($result5);

if(sizeof($rekord5)!==0){
	exit("Login zajęty.");
}

$result = mysqli_query($link, "INSERT INTO `users` (`nazwisko`, `haslo`) VALUES('$user', '$pass')");

$result = mysqli_query($link, "SELECT * FROM `users` WHERE nazwisko='$user' AND haslo='$pass'"); 
$rekord = mysqli_fetch_array($result);

 if($rekord['haslo']==$pass)
 {
 echo "Rejestracja przebiegła pomyślnie. User: {$rekord['nazwisko']}. Hasło: {$rekord['haslo']}";
 
 #tworzenie folderu dla uzytkownika
 
 $ftp_username = "admini_ftp";
 $ftp_userpass = "Zamek123";
 $ftp_server = "ftp.gawronartur.pl";
 $ftp_conn = ftp_connect($ftp_server) or die("Nie mozna połączyć z FTP $ftp_server");
 ftp_login($ftp_conn, $ftp_username, $ftp_userpass);
 $user = $rekord['nazwisko'];
 mkdir ($_SERVER['DOCUMENT_ROOT'].'z7/'.$user, 0777);

 ftp_close($ftp_conn);
 
 echo "<br/><b>Folder o nazwie: <i>$user</i> został stworzony!</b>";
 }
 else
 {
 mysqli_close($link);
 echo "Błąd w haśle !";
 }
?>
</BODY>
</HTML>