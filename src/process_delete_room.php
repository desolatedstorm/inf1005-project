<?php
ob_start();
include "inc/functions.php";

// Placeholder for Admin Check
// session_start();
// if (!isset($_SESSION['user_id']) || $_SESSION['is_admin'] !== 1) {
//     die("Unauthorized access.");
// }

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
