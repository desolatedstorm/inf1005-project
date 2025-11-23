<?php
require_once 'vendor/autoload.php';
require_once "login_functions.php";
require_once "inc/secure_session_start.php";

include "inc/nav.inc.php";

$username = "";
$email = "";
$password = "";
$errorMsg = "";
$success = true;

// ---------------------------
// Username
// ---------------------------
if (empty($_POST["username"])) {
    $errorMsg .= "Username is required.<br>";
    $success = false;
} else {
    $username = sanitize_input($_POST['username']);
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
}

// ---------------------------
// Password
// ---------------------------
if (empty($_POST["password"])) {
    $errorMsg .= "Password is required.<br>";
    $success = false;
} else {
    $password = $_POST["password"];
    if (strlen($password) < 8) {
        $errorMsg .= "Password must be at least 8 characters.<br>";
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

        // IMPORTANT: your saveUserToDB MUST RETURN the inserted user ID
        $new_user_id = $result['user_id'];

        // Auto-login
        $_SESSION['user_id'] = $new_user_id;

        // Send email
        sendConfirmationEmail($email, $username);

        // Redirect to account page
        header("Location: manage_account.php");
        exit;

    } else {
        $errorMsg = $result['message'];
        $success = false;
    }
}

// ---------------------------
// If failure
// ---------------------------
if (!$success) {
    echo "<div class='container justify-content-center mb-3'>";
    echo "<h2>Oops!</h2>";
    echo "<h4>The following input errors were detected:</h4>";
    echo "<p>$errorMsg</p><br>";
    echo "<a href='register.php' class='btn btn-danger'>Return to Sign Up</a>";
    echo "</div>";
}

include "inc/footer.inc.php";
?>
