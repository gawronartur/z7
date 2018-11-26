<?php

	session_start();
	
	session_unset();

	setcookie("username", "", time() - 3600);
	header('Location: index.php');
	
?>