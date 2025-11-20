<?php
ob_start();
include "inc/functions.php";

//placeholder for admin again..
// session_start();
// if (!isset($_SESSION['user_id']) || $_SESSION['is_admin'] !== 1) {
//     die("Unauthorized access.");
// }

// check if form is submitted or not 
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    $conn = getDbConnection();
    $success = true;
    $errorMsg = "";

    // sanitise inputs
    $name = sanitize_input($_POST['roomName']);
    $desc = sanitize_input($_POST['roomDescription']);
    $location = sanitize_input($_POST['roomLocation']);
    
    // int
    $duration = (int)$_POST['roomDuration'];
    $min = (int)$_POST['roomMin'];
    $max = (int)$_POST['roomMax'];
    
    // float
    $priceOff = (float)$_POST['roomPriceOffpeak'];
    $pricePeak = (float)$_POST['roomPricePeak'];
    
    // drop down menu so can select from these 
    $difficulty = $_POST['roomDifficulty'];
    $fear = $_POST['roomFearLevel'];
    $genre = $_POST['roomGenre'];
    $expType = $_POST['roomExperienceType'];
    
    // image upload
    $target_dir = "images/";
    $imagePath = "images/placeholder.png"; // Default fallback

    if (isset($_FILES["roomImage"]) && $_FILES["roomImage"]["error"] == 0) {
        
        $fileName = basename($_FILES["roomImage"]["name"]);
        // Create unique filename to avoid overwrites (e.g., room_timestamp_filename.jpg)
        $newFileName = "room_" . time() . "_" . $fileName;
        $target_file = $target_dir . $newFileName;
        $uploadOk = 1;
        $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

        // Check if image file is a actual image
        $check = getimagesize($_FILES["roomImage"]["tmp_name"]);
        if ($check === false) {
            $errorMsg = "File is not an image.";
            $uploadOk = 0;
        }

        // Check file size (Limit to 5MB)
        if ($_FILES["roomImage"]["size"] > 5000000) {
            $errorMsg = "Sorry, your file is too large.";
            $uploadOk = 0;
        }

        // Allow certain file formats
        if ($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg" && $imageFileType != "gif") {
            $errorMsg = "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
            $uploadOk = 0;
        }

        // Try to upload
        if ($uploadOk == 1) {
            if (move_uploaded_file($_FILES["roomImage"]["tmp_name"], $target_file)) {
                // Success! Store the path relative to root
                $imagePath = $target_file;
            } else {
                $errorMsg = "Sorry, there was an error uploading your file.";
            }
        } else {
            $success = false;
        }
    }

    // prepare statement to insert into db
    if ($success) {
        $sql = "INSERT INTO Rooms (roomName, roomDescription, roomMax, roomMin, roomDuration, roomDifficulty, roomLocation, roomFearLevel, roomExperienceType, roomGenre, roomPricePeak, roomPriceOffpeak, imagePath) 
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
        
        $stmt = $conn->prepare($sql);
        
        if ($stmt) {
            $stmt->bind_param("ssiiisssssdds", 
                $name, $desc, $max, $min, $duration, 
                $difficulty, $location, $fear, $expType, $genre, 
                $pricePeak, $priceOff, $imagePath
            );

            if ($stmt->execute()) {
                // Redirect to the newly created room or index
                $newID = $stmt->insert_id;
                header("Location: room.php?id=" . $newID);
                exit();
            } else {
                $errorMsg = "Database execute failed: " . $stmt->error;
            }
            $stmt->close();
        } else {
            $errorMsg = "Database prepare failed: " . $conn->error;
        }
    }

    $conn->close();
    
    // If we reached here, there was an error
    echo "<div style='background-color: #333; color: white; padding: 20px; text-align: center;'>";
    echo "<h3>Error Creating Room</h3>";
    echo "<p>$errorMsg</p>";
    echo "<a href='create_room.php' style='color: #f59f00;'>Go Back</a>";
    echo "</div>";

} else {
    // Not a POST request
    header("Location: create_room.php");
    exit();
}
?>