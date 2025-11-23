<?php
	require_once __DIR__ . "/inc/secure_session_start.php";

	// Redirect if not logged in
	if (!isset($_SESSION['user_id'])) {
		header("Location: login.php");
		exit();
	}
?>

<!doctype html>
<html lang="en">
<link rel="icon" type="image/x-icon" href="../images/home.ico">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <meta name="description" content="Account management" />

    <title>Your account details:</title>
    <?php include "inc/head.inc.php"; ?>
</head>

<body>
    <?php include "inc/nav.inc.php"; ?>
	
	<main>
    </main>

    <?php include "inc/footer.inc.php"; ?>
</body>

</html>
