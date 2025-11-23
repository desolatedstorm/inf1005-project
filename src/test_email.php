<?php
	require "login_functions.php";

	sendConfirmationEmail("tristankohrh@gmail.com", "Tristan");
	echo testPHPMailerLoad();
?>