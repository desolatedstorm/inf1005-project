<?php
require_once __DIR__ . '/vendor/autoload.php';
require_once __DIR__ . "/login_functions.php";
require_once __DIR__ . "/inc/secure_session_start.php";

$username = "";
$email = "";
$password = "";
$errorMsg = "";
$successMsg = "";
$success = true;

// ---------------------------
// Username
// ---------------------------
if (empty($_POST["username"])) {
    $errorMsg .= "Username is required.<br>";
    $success = false;
} else {
    $username = sanitize_input($_POST['username']);
    
    // Validate username format
    $usernameValidation = validateUsername($username);
    if (!$usernameValidation['valid']) {
        $errorMsg .= implode("<br>", $usernameValidation['errors']) . "<br>";
        $success = false;
    }
    
    // Check if username already exists
    if ($success && usernameExists($username)) {
        $errorMsg .= "Username already exists. Please choose another.<br>";
        $success = false;
    }
}

// ---------------------------
// Email
// ---------------------------
if (empty($_POST["email"])) {
    $errorMsg .= "Email is required.<br>";
    $success = false;
} else {
    $email = sanitize_input($_POST["email"]);
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errorMsg .= "Invalid email format.<br>";
        $success = false;
    }
    
    // Check if email already exists
    if ($success && emailExists($email)) {
        $errorMsg .= "Email already registered. Please <a href='login.php'>login</a> or use a different email.<br>";
        $success = false;
    }
}

// ---------------------------
// Password
// ---------------------------
if (empty($_POST["password"])) {
    $errorMsg .= "Password is required.<br>";
    $success = false;
} else {
    $password = $_POST["password"];
    
    // Validate password strength
    $passwordValidation = validatePasswordStrength($password);
    if (!$passwordValidation['valid']) {
        $errorMsg .= implode("<br>", $passwordValidation['errors']) . "<br>";
        $success = false;
    }
}

if (empty($_POST["password_confirm"])) {
    $errorMsg .= "Please confirm your password.<br>";
    $success = false;
} else {
    $password_confirm = $_POST["password_confirm"];
    if ($password !== $password_confirm) {
        $errorMsg .= "Passwords do not match.<br>";
        $success = false;
    }
}

// ---------------------------
// If validation passed
// ---------------------------
if ($success) {

    $password_hash = password_hash($password, PASSWORD_DEFAULT);

    // Save user
    $result = saveUserToDB($username, $email, $password_hash);

    if ($result['success']) {

        // Send email
        //$emailResult = sendConfirmationEmail($email, $username);
        
        $successMsg = "Registration successful! A confirmation email has been sent to $email.";
        $success = true;

    } else {
        $errorMsg = $result['message'];
        $success = false;
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <title>Registration Results</title>
    <?php include "inc/head.inc.php"; ?>
</head>
	
<body>
	<?php include "inc/nav.inc.php"; ?>

	<?php if ($success && !empty($successMsg)): ?>
	<div class="container justify-content-center mb-3">
		<h2>Success!</h2>
		<h4>Your account has been created.</h4>
		<p><?= $successMsg ?></p>
		<a href="login.php" class="btn btn-success">Go to Login</a>
	</div>
	<?php elseif (!$success): ?>
	<div class="container justify-content-center mb-3">
		<h2>Oops!</h2>
		<h4>The following input errors were detected:</h4>
		<p><?= $errorMsg ?></p>
		<a href="register.php" class="btn btn-danger">Return to Sign Up</a>
	</div>
	<?php endif; ?>

	<?php include "inc/footer.inc.php"; ?>
</body>
	
</html>