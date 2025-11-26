<?php
session_start();
require_once "inc/functions.php";

// Check if user is logged in
if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    header("Location: login.php");
    exit();
}

$errorMsg = "";
$success = true;

// Check if form was submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    // Validate rating
    if (empty($_POST["rating"])) {
        $errorMsg .= "Rating is required.<br>";
        $success = false;
    } else {
        $rating = (int)$_POST["rating"];
        if ($rating < 1 || $rating > 5) {
            $errorMsg .= "Rating must be between 1 and 5.<br>";
            $success = false;
        }
    }
    
    // Validate room ID
    if (empty($_POST["room_id"])) {
        $errorMsg .= "Room ID is required.<br>";
        $success = false;
    } else {
        $room_id = (int)$_POST["room_id"];
    }
    
    // Comment is optional but sanitize if provided
    $comment = isset($_POST["comment"]) ? trim($_POST["comment"]) : "";
    
    // Get user ID from session (you'll need to set this during login)
    if (!isset($_SESSION['user_id'])) {
        $errorMsg .= "User session not found. Please login again.<br>";
        $success = false;
    } else {
        $user_id = (int)$_SESSION['user_id'];
    }
    
    if ($success) {
        try {
            $conn = getDBconnection();
            
            // Get room name for redirect
            $room_stmt = $conn->prepare("SELECT roomName FROM Rooms WHERE roomID = ?");
            $room_stmt->bind_param("i", $room_id);
            $room_stmt->execute();
            $room_result = $room_stmt->get_result();
            $room_data = $room_result->fetch_assoc();
            $room_name = $room_data['roomName'] ?? '';
            $room_stmt->close();
            
            // Check if user already reviewed this room
            $check_stmt = $conn->prepare("SELECT reviewID FROM Reviews WHERE Users_userID = ? AND Rooms_roomID = ?");
            $check_stmt->bind_param("ii", $user_id, $room_id);
            $check_stmt->execute();
            $result = $check_stmt->get_result();
            
            if ($result->num_rows > 0) {
                // Update existing review
                $stmt = $conn->prepare("UPDATE Reviews SET rating = ?, comment = ? WHERE Users_userID = ? AND Rooms_roomID = ?");
                $stmt->bind_param("isii", $rating, $comment, $user_id, $room_id);
            } else {
                // Insert new review
                $stmt = $conn->prepare("INSERT INTO Reviews (rating, comment, Users_userID, Rooms_roomID) VALUES (?, ?, ?, ?)");
                $stmt->bind_param("isii", $rating, $comment, $user_id, $room_id);
            }
            
            if (!$stmt->execute()) {
                $errorMsg = "Error saving review: " . $stmt->error;
                $success = false;
            }
            
            $stmt->close();
            $check_stmt->close();
            $conn->close();
            
        } catch (Exception $e) {
            $errorMsg = "Exception: " . $e->getMessage();
            $success = false;
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title>Review Submission Result</title>
    <?php include "inc/head.inc.php"; ?>
</head>
<body>
    <?php include "inc/nav.inc.php"; ?>
    
    <div class="container mt-5 mb-5">
        <?php if ($success): ?>
            <div class="alert alert-success">
                <h2>Thank you for your review!</h2>
                <p>Your review has been successfully submitted.</p>
                <a href="room.php?name=<?php echo urlencode($room_name); ?>" class="btn btn-primary">Back to Room</a>
                <a href="index.php" class="btn btn-secondary">Back to Home</a>
            </div>
        <?php else: ?>
            <div class="alert alert-danger">
                <h2>Oops! Something went wrong</h2>
                <p><?php echo $errorMsg; ?></p>
                <a href="javascript:history.back()" class="btn btn-warning">Go Back</a>
            </div>
        <?php endif; ?>
    </div>
    
    <?php include "inc/footer.inc.php"; ?>
</body>
</html>
