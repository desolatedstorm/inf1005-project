<?php

include "inc/db.inc.php";
$conn = getDbConnection();

//check for id in the url (e.g php?id=22)
if (isset($_GET['id'])) {
    
    //get the id from the url
    $room_id = (int)$_GET['id'];
    
    //safe query for the room
    $stmt = $conn->prepare("SELECT * FROM Rooms WHERE roomID = ?");
    $stmt->bind_param("i", $room_id);
    $stmt->execute();
    
    $result = $stmt->get_result();
    
    if ($result->num_rows > 0) {
        $room = $result->fetch_assoc();

        // DISPLAY ROOM DETAILS HERE! 
        // echo htmlspecialchars($room['roomName']);
        echo "<h1>" . htmlspecialchars($room['roomName']) . "</h1>";
        echo "<p>" . htmlspecialchars($room['roomDescription']) . "</p>";

    } else {
        echo "Room is not found.";
    }
} else {
    echo "No room specified.";
}
?>