 <?php
	require_once __DIR__ . "/inc/secure_session_start.php";

	session_unset();
	session_destroy();

	header("Location: login.php");
	exit();
?>
