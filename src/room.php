<?php
// set session to access booking.php
session_start();
$_SESSION['allow_booking'] = true;

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
    } else {
        echo "Room is not found.";
    }
} else {
    echo "No room specified.";
}
//helper function to get the right css color for fear factor
function getFearColor($fearLevel)
{
    switch ($fearLevel) {
        case 'Very Scary':
            return 'bg-danger';
        case 'Scary':
            return 'bg-warning text-dark';
        case 'Mildly Scary':
            return 'bg-info text-dark';
        case 'Not Scary':
            return 'bg-secondary';
        default:
            return 'bg-light text-dark';
    }
}

function getDifficultyColor($roomDifficulty)
{
    switch ($roomDifficulty) {
        case 'Very Hard':
            return 'bg-danger';
        case 'Hard':
            return 'bg-warning text-dark';
        case 'Medium':
            return 'bg-info text-dark';
        case 'Easy':
            return 'bg-secondary';
        default:
            return 'bg-light text-dark';
    }
}

?>

<!doctype html>
<html lang="en">
<link rel="icon" type="image/x-icon" href="../images/home.ico">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <meta name="description" content="The Pharaoh's Curse - Escape Room Experience" />

    <title><?php echo htmlspecialchars($room['roomName']) ?></title>
    <?php include "inc/head.inc.php" ?>
    <link rel="stylesheet" href="css/rooms.css">
</head>

<body>
    <?php include "inc/nav.inc.php" ?>

    <main class="page-content">
        <div class="container">
            <a href="index.php" class="back-link">← Back to Rooms</a>

            <img src="<?php echo htmlspecialchars($room['imagePath'] ?? '/images/placeholder.png'); ?>" 
            alt=<?php echo htmlspecialchars($room['roomName']) ?> class="room-hero" />

            <div class="thumbnail-gallery">
                <img src="<?php echo htmlspecialchars($room['imagePath'] ?? '/images/placeholder.png'); ?>" alt="Thumbnail 1" class="active" onclick="changeHeroImage(this.src)" />
            </div>

            <div class="room-content">

                <div class="room-details">
                    <h1 class="room-title"><?php echo htmlspecialchars($room['roomName']) ?></h1>

                    <div class="room-badges">
                        <span class="badge <?php echo getFearColor($room['roomFearLevel']); ?>"><?php echo htmlspecialchars($room['roomFearLevel']); ?></span>
                        <span class="badge <?php echo getDifficultyColor($room['roomDifficulty']); ?>"><?php echo htmlspecialchars($room['roomDifficulty']); ?></span>
                        <span class="badge bg-light text-dark"><?php echo htmlspecialchars($room['roomGenre'])?></span>
                    </div>

                    <h3>About This Room</h3>
                    <p><?php echo htmlspecialchars($room['roomDescription'])?></p>
                    <hr>
                    <h4>What to Expect</h4>
                    <ul>
                        <li>Immersive storyline and detailed set design</li>
                        <li>Challenging puzzles that require teamwork</li>
                        <li>Professional game master guidance</li>
                        <li>Photo opportunities after completion</li>
                    </ul>

                    <h3>Important Information</h3>
                    <ul>
                        <li>Please arrive 10 minutes before your scheduled time</li>
                        <li>Late arrivals may result in reduced game time</li>
                        <li>Not recommended for children under 12 (unless specified)</li>
                        <li>Comfortable clothing and closed-toe shoes recommended</li>
                    </ul>
                </div>


                <div class="pricing-card">
                    <div class="price-label">From</div>
                    <div class="price">$35</div>
                    <div style="color: #666; font-size: 0.9rem; margin-bottom: 1rem;">/person</div>

                    <ul class="price-details">
                        <li>60 minutes</li>
                        <li>2-6 players</li>
                        <li>Difficulty: 3/5</li>
                    </ul>
                    <!-- leave this as it is for now there is a js function that opens this -->
                    <!-- also copy this to the other pages -->
                    <button type="button" id="openPopup" name="openPopup" class="book-btn">Book Now</Button>

                    <p class="cancellation-note">Free cancellation up to 24 hours before</p>
                </div>
            </div>
        </div>
        <!-- booking pop up -->
        <section id="modal" class="modal">
            <div class="modal-content">
                <span class="close">&times;</span>
                <iframe id="popupFrame" src=""><iframe>
            </div>
        </section>
    </main>

    <?php
    include "inc/footer.inc.php";
    ?>
</body>

</html>