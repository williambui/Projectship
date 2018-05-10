<?php
	session_start();
	unset($_SESSION['currUser']);
	header("Location: loginPage.php");
?>