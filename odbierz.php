<?php
session_start();
?>

<?php
$max_rozmiar = 1000;
if (is_uploaded_file($_FILES['plik']['tmp_name']))
{
	if ($_FILES['plik']['size'] > $max_rozmiar) {
		echo "Przekroczenie rozmiaru $max_rozmiar"; }
	else
	{
	echo 'Odebrano plik: '.$_FILES['plik']['name'].'<br/>';
		if (isset($_FILES['plik']['type'])) {
			echo 'Typ: '.$_FILES['plik']['type'].'<br/>'; }
	move_uploaded_file($_FILES['plik']['tmp_name'],$_SERVER['DOCUMENT_ROOT'].'z7/'.$_COOKIE["username"].'/'.$_FILES['plik']['name']);
	echo $_SERVER['DOCUMENT_ROOT'].'z7/'.$_COOKIE["username"];
	}
}
else {
	echo 'Błąd przy przesyłaniu danych!';}
	
?>