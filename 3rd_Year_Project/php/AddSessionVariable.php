<?php
	session_start();

	$question_index = $_POST["qnumber"];
	$_SESSION[$question_index] = $_POST[$question_index];

	echo $_POST[$question_index];

?>