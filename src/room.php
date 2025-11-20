<!doctype html>
<html lang="en">
<link rel="icon" type="image/x-icon" href="../images/home.ico">
<?php
session_start();
include "inc/db.inc.php";
$conn = getDbConnection();

// Check for id in the url (e.g room.php?id=1)
if (!isset($_GET['id'])) {
    header("Location: index.php");
    exit();
}

// Get the id from the url
$room_id = (int)$_GET['id'];

// Safe query for the room
$stmt = $conn->prepare("SELECT * FROM Rooms WHERE roomID = ?");
$stmt->bind_param("i", $room_id);
$stmt->execute();

$result = $stmt->get_result();

if ($result->num_rows === 0) {
    header("Location: index.php");
    exit();
}

$room = $result->fetch_assoc();
$conn->close();

//get badge color based on fear level
function getFearBadgeColor($fearLevel) {
    switch ($fearLevel) {
        case 'Very Scary':
            return 'bg-danger';
        case 'Scary':
            return 'bg-warning text-dark';
        case 'Mildly Scary':
            return 'bg-info';
        case 'Not Scary':
            return 'bg-secondary';
        default:
            return 'bg-light text-dark';
    }
}
//get difficulty display
function getDifficultyDisplay($difficulty) {
    $levels = ['Easy' => '2/5', 'Medium' => '3/5', 'Hard' => '4/5'];
    return $levels[$difficulty] ?? '3/5';
}

// Default image mapping (you can update this or add imagePath to database)
function getRoomImage($roomName) {
    $imageMap = [
        //insert whatever here placeholder
    ];
    return $imageMap[$roomName] ?? "/images/placeholder.png";
}
?>
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <meta name="description" content="<?php echo htmlspecialchars($room['roomName']); ?> - Escape Room Experience" />

    <title><?php echo htmlspecialchars($room['roomName']); ?> - Escape Room</title>
    <?php include "inc/head.inc.php"; ?>
    <link rel="stylesheet" href="css/rooms.css">
</head>

<body>
    <?php include "inc/nav.inc.php"; ?>

    <main class="page-content">
        <div class="container">
            <a href="index.php" class="back-link">← Back to Rooms</a>

            <img src="<?php echo getRoomImage($room['roomName']); ?>" alt="<?php echo htmlspecialchars($room['roomName']); ?>" class="room-hero" />

            <div class="thumbnail-gallery">
                <img src="<?php echo getRoomImage($room['roomName']); ?>" alt="Thumbnail 1" class="active" onclick="changeHeroImage(this.src)" />
            </div>

            <div class="room-content">
                <div class="room-details">
                    <h1 class="room-title"><?php echo htmlspecialchars($room['roomName']); ?></h1>

                    <div class="room-badges">
                        <span class="badge <?php echo getFearBadgeColor($room['roomFearLevel']); ?>">
                            <?php echo htmlspecialchars($room['roomFearLevel']); ?>
                        </span>
                        <span class="badge bg-warning text-dark">
                            Difficulty <?php echo getDifficultyDisplay($room['roomDifficulty']); ?>
                        </span>
                        <span class="badge bg-light text-dark"><?php echo strtolower($room['roomGenre']); ?></span>
                        <span class="badge bg-light text-dark"><?php echo strtolower($room['roomExperienceType']); ?></span>
                    </div>

                    <h3>About This Room</h3>
                    <p><?php echo nl2br(htmlspecialchars($room['roomDescription'])); ?></p>

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
                    <div class="price">$<?php echo number_format($room['roomPriceOffpeak'], 0); ?></div>
                    <div style="color: #666; font-size: 0.9rem; margin-bottom: 1rem;">/person</div>

                    <ul class="price-details">
                        <li><?php echo $room['roomDuration']; ?> minutes</li>
                        <li><?php echo $room['roomMin']; ?>-<?php echo $room['roomMax']; ?> players</li>
                        <li>Difficulty: <?php echo getDifficultyDisplay($room['roomDifficulty']); ?></li>
                    </ul>

                    <!-- leave this as it is for now there is a js function that opens this -->
                    <button type="button" id="openPopup" name="openPopup" class="book-btn">Book Now</button>

                    <?php if (isset($_SESSION['logged_in']) && $_SESSION['logged_in'] === true): ?>
                    <button type="button" id="openRatingPopup" name="openRatingPopup" class="rating-btn">Rate Our Services</button>
                    <?php endif; ?>

                    <p class="cancellation-note">Free cancellation up to 24 hours before</p>
                </div>
            </div>
        </div>
    </main>

    <script src="js/main.js"></script>
    <?php include "inc/footer.inc.php"; ?>
</body>

</html>