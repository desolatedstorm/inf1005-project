<?php
ob_start();
// 1. Start the session (if not already started)
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

include "inc/functions.php";

// 2. SECURITY CHECK
// If user is NOT logged in OR user is NOT an admin
if (!isset($_SESSION['user_id']) || !isset($_SESSION['is_admin']) || $_SESSION['is_admin'] != 1) {
    // Redirect them to login page
    header("Location: login.php");
    exit(); // Stop the script immediately
}


// post request
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['roomID'])) {

    $conn = getDbConnection();
    // security check forces roomID to be an integer
    $roomID = (int)$_POST['roomID'];

    // get image path before deleting the room (so we can delete the image too)
    $sqlGetImage = "SELECT imagePath FROM Rooms WHERE roomID = ?";
    $stmtGet = $conn->prepare($sqlGetImage);
    $stmtGet->bind_param("i", $roomID);
    $stmtGet->execute();
    $result = $stmtGet->get_result();

    $imageToDelete = null;
    if ($row = $result->fetch_assoc()) {
        $imageToDelete = $row['imagePath'];
    }
    $stmtGet->close();


    // actual deletion (delete on cascade)
    $sqlDelete = "DELETE FROM Rooms WHERE roomID = ?";
    $stmtDelete = $conn->prepare($sqlDelete);
    $stmtDelete->bind_param("i", $roomID);

    if ($stmtDelete->execute()) {

        // deletes the image from earlier
        if ($imageToDelete && $imageToDelete !== 'images/placeholder.png' && file_exists($imageToDelete)) {
            unlink($imageToDelete);
        }

        // redirection
        header("Location: delete_room.php?msg=deleted");
    } else {
        echo "Error deleting record: " . $conn->error;
    }

    $stmtDelete->close();
    $conn->close();
} else {
    // redirection
    header("Location: delete_room.php");
}
ob_end_flush();
