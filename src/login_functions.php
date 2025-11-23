<?php
// Helper function that checks input for malicious or unwanted content.
function sanitize_input($data)
{
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}

// Function to create DB connection using credentials in env var
function getDBEnvVar()
{
    // Create DB connection
    $db_host = getenv('DB_HOST') ?: "db";
    $db_user = getenv('DB_USER');
    $db_pass = getenv('DB_PASS');
    $db_name = getenv('DB_NAME');
    $stripekey = getenv('STRIPESECRETKEY');
    return array($db_host, $db_user, $db_pass, $db_name, $stripekey);
}

// Helper function to save a new user to the database
function saveUserToDB(string $username, string $email, string $passwordHash): array
{
    $errorMsg = '';
    $success = true;

    list($db_host, $db_user, $db_pass, $db_name) = getDBEnvVar();

    if (!$db_user || !$db_pass || !$db_name) {
        return ['success' => false, 'message' => "DB Environment Variables not set."];
    }

    mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

    try {
        $conn = new mysqli($db_host, $db_user, $db_pass, $db_name);

        if ($conn->connect_error) {
            return ['success' => false, 'message' => "Connection Failed: " . $conn->connect_error];
        }

        // Use prepared statement to insert user data
        $stmt = $conn->prepare("INSERT INTO Users (username, email, passwordhash) VALUES (?, ?, ?)");

        $stmt->bind_param("sss", $username, $email, $passwordHash);

        if (!$stmt->execute()) {
            $stmt->close();
            $conn->close();
            return ['success' => false, 'message' => "Execute failed: (" . $stmt->errno . ") " . $stmt->error];
        }

        $stmt->close();
        $conn->close();

        return ['success' => true, 'message' => 'User registered successfully.'];
    } catch (Exception $e) {
        return ['success' => false, 'message' => "Exception: " . $e->getMessage()];
    }
}

// Helper function to authenticate user
function authenticateUser($email, $password) {
    list($db_host, $db_user, $db_pass, $db_name) = getDBEnvVar();

    if (!$db_user || !$db_pass || !$db_name) {
        return false;
    }

    //mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

    try {
        $conn = new mysqli($db_host, $db_user, $db_pass, $db_name);

        $stmt = $conn->prepare("SELECT userID, username, email, passwordhash FROM Users WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();
		
        if ($result->num_rows !== 1) {
			$user['message'] = "db";
            return false; // user not found
        }

        $user = $result->fetch_assoc();

        $stmt->close();
        $conn->close();
		
        if (password_verify($password, $user['passwordhash'])) {
			//unset($user['passwordhash']);
		
        //if ($password === $user['passwordhash']) {
			return $user; // success, return user data
        }
		else {
            return false; // password mismatch
        }
    }
	catch (Exception $e) {
        // can log $e->getMessage() here
        return false; //$e->getMessage();
    }
}

// Helper function to get the user's email
function getUserEmail(int $user_id) {
    list($db_host, $db_user, $db_pass, $db_name) = getDBEnvVar();

    mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

    try {
        $conn = new mysqli($db_host, $db_user, $db_pass, $db_name);

        $stmt = $conn->prepare("SELECT email FROM Users WHERE userID = ?");
        $stmt->bind_param("i", $user_id);
        $stmt->execute();
        $result = $stmt->get_result();

        $email = null;
        if ($row = $result->fetch_assoc()) {
            $email = $row["email"];
        }

        $stmt->close();
        $conn->close();

        return $email;
    }
	catch (Exception $e) {
        return null;
    }
}

// Confirmation email for registering
require_once __DIR__ . '/vendor/autoload.php';
if (!file_exists(__DIR__ . '/src/vendor/autoload.php')) {
    die('Error: autoload.php not found at ' . __DIR__ . '/src/vendor/autoload.php');
}


use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\SMTP;

function sendConfirmationEmail($email, $username) {

    $mail = new PHPMailer(true);

    try {
        // -----------------------------
        // OUTLOOK SMTP CONFIG
        // -----------------------------
        $mail->isSMTP();
        $mail->Host       = 'smtp.office365.com'; // im using my outlook account
        $mail->SMTPAuth   = true;
        $mail->Username   = 'email';   // your sender email
        $mail->Password   = 'password';           // email or app password
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port       = 587;


        // -----------------------------
        // EMAIL HEADERS
        // -----------------------------
        $mail->setFrom('SENDER_EMAIL_HERE', 'Escape Quest');  
        $mail->addAddress($email, $username);

        // -----------------------------
        // MESSAGE CONTENT
        // -----------------------------
        $mail->isHTML(true);
        $mail->Subject = "Welcome to Escape Quest!";
        $mail->Body    = "
            Hi <strong>$username</strong>,<br><br>
            Thank you for registering at <strong>Escape Quest</strong>!<br>
            Your account has been created successfully.<br><br>
            You can now log in and start booking our escape rooms.<br><br>
            <em>See you soon!</em><br>
        ";

        $mail->AltBody = "Hi $username, \n\nThank you for registering at Escape Quest! Your account has been created.";

        // -----------------------------
        // SEND
        // -----------------------------
        $mail->send();
        return true;

    } catch (Exception $e) {
        // Log errors somewhere safe later
        error_log("Email Error: " . $mail->ErrorInfo);
        return false;
    }
}

function testPHPMailerLoad() {
    try {
        $mail = new PHPMailer();
        return "PHPMailer loaded successfully!";
    } catch (Exception $e) {
        return "PHPMailer load error: " . $e->getMessage();
    }
}

?>
