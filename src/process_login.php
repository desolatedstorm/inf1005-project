<?php	
	require_once "login_functions.php";
	require_once "inc/secure_session_start.php";
	
	$_SESSION['user_id'] = $user_id;

	header("Location: manage_account.php");
	exit;

	$errorMsg = "";
	$success = true;
			
	// Validate email
	if (empty($_POST["email"])) {
		$errorMsg .= "Email is required.<br>";
		$success = false;
	}
	else {
		$email = sanitize_input($_POST["email"]);

		if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
			$errorMsg .= "Invalid Email format.<br>";  // Added missing $
			$success = false;
		}
	}

	// Validate password
	if (empty($_POST["password"])) {
		$errorMsg .= "Password is required.<br>";
		$success = false;
	}
	else {
		$password = $_POST["password"];
	}

	if ($success) {
		$user = authenticateUser($email, $password);
		
		if ($user === false) {
			$errorMsg .= "Incorrect email or password.<br>";
			$success = false;
		}
		else {
			require_once __DIR__ . "/includes/session.php";
			
			// Login successful: start session
			$_SESSION["user_id"] = $user["userID"];
			$_SESSION['email'] = $user['email'];
			$_SESSION["username"] = $user["username"];

			// Redirect to homepage or account page
			header("Location: index.php");
			exit;
		}
	}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <title>Login Results</title>
    <?php include "inc/head.inc.php"; ?>
</head>
	
<body>
	<?php include "inc/nav.inc.php"; ?>

	<div class="container justify-content-center mb-3">
		<h2>Oops!</h2>
		<h4>The following errors were detected:</h4>
		<p><?= $errorMsg ?></p>
		<a href="login.php" class="btn btn-warning">Return to Login</a>
	</div>

	<?php include "inc/footer.inc.php"; ?>
</body>
	
</html>
