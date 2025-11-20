<?php

include "inc/functions.php";
$conn = getDbConnection();

//variable to hold list of rooms and number of rooms
$rooms = [];
$roomCount = 0;

//to-do: implement a variable imagePath and also add data into the DB
$sql = "SELECT roomID, roomName, roomFearLevel, roomDifficulty, roomExperienceType, roomGenre FROM Rooms";
$result = $conn->query($sql);

//check for results
if ($result && $result->num_rows > 0) {
    $rooms = $result->fetch_all(MYSQLI_ASSOC);
    $roomCount = $result->num_rows;
}

//close connection
$conn->close();

?>