<?php
// set session to access booking.php
include "api/api_generate_token.php";

include "inc/functions.php";
include "inc/review_functions.php";
$conn = getDbConnection();
$room = null;

//check for 'NAME' in the url
if (isset($_GET['name'])) {

    //get the NAME from the url
    $room_name = sanitize_input($_GET['name']);

    //safe query for the room
    $stmt = $conn->prepare("SELECT * FROM Rooms WHERE roomName = ?");
    $stmt->bind_param("s", $room_name);
    $stmt->execute();

    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $room = $result->fetch_assoc();

        // store in session for booking page
        $_SESSION['room_id'] = $room['roomID'];
        $_SESSION['room_name'] = $room_name;
        $_SESSION['desc'] = $room['roomDescription'];
        $_SESSION['min'] = $room['roomMin'];
        $_SESSION['max'] = $room['roomMax'];
        $_SESSION['price'] = $room['roomPriceOffPeak'];

        // Get average rating for this room
        $rating_stmt = $conn->prepare("SELECT AVG(rating) as avg_rating, COUNT(*) as review_count FROM Reviews WHERE Rooms_roomID = ?");
        $rating_stmt->bind_param("i", $room_id);
        $rating_stmt->execute();
        $rating_result = $rating_stmt->get_result();
        $rating_data = $rating_result->fetch_assoc();
        $avg_rating = round($rating_data['avg_rating'] ?? 0, 1);
        $review_count = $rating_data['review_count'] ?? 0;
        $rating_stmt->close();
    }
} else {
    echo "No room specified.";
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <title><?php echo htmlspecialchars($room['roomName']) ?></title>
    <?php include "inc/head.inc.php" ?>
    <link rel="stylesheet" href="css/rooms.css">
    <link rel="preload" href="css/popup.css" as="style">
    <link rel="stylesheet" href="css/popup.css">
    <script defer src="js/popup.js"></script>
</head>

<body>
    <?php include "inc/nav.inc.php" ?>

    <main class="page-content">
        <div class="container">
            <a href="index.php" class="back-link">← Back to Rooms</a>

            <?php if ($room): ?>
                <img src="<?php echo htmlspecialchars($room['imagePath'] ?? '/images/placeholder.png'); ?>"
                    alt=<?php echo htmlspecialchars($room['roomName']) ?> class="room-hero" />

                <div class="thumbnail-gallery">
                    <img src="<?php echo htmlspecialchars($room['imagePath'] ?? '/images/placeholder.png'); ?>" alt="Thumbnail 1" class="active" onclick="changeHeroImage(this.src)" />
                </div>

                <div class="room-content">

                    <div class="room-details">
                        <h1 class="room-title"><?php echo htmlspecialchars($room['roomName']) ?></h1>

                        <div class="room-badges">
                            <span class="badge <?php echo getBadgeColor($room['roomFearLevel']); ?>"><?php echo htmlspecialchars($room['roomFearLevel']); ?></span>
                            <span class="badge <?php echo getDifficultyColor($room['roomDifficulty']); ?>"><?php echo htmlspecialchars($room['roomDifficulty']); ?></span>
                            <span class="badge bg-light text-dark"><?php echo htmlspecialchars($room['roomGenre']) ?></span>
                        </div>

                        <h3>About This Room</h3>
                        <p><?php echo htmlspecialchars($room['roomDescription']) ?></p>
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
                        <div class="price"> $<?php echo $room['roomPriceOffPeak'] ?></div>
                        <div style="color: #666; font-size: 0.9rem; margin-bottom: 1rem;">/person</div>

                        <ul class="price-details">
                            <li><?php echo $room['roomDuration'] ?> minutes </li>
                            <li><?php echo $room['roomMin'] . '-' . $room['roomMax']; ?> players</li>
                            <li>Rating: ★<?php echo $avg_rating; ?> (<?php echo $review_count; ?> reviews)</li>
                        </ul>

                        <!-- leave this as it is for now there is a js function that opens this -->
                        <!-- also copy this to the other pages -->
                        <button type="button" id="openPopup" name="openPopup" class="book-btn">Book Now</button>

                        <?php if (isset($_SESSION['logged_in']) && $_SESSION['logged_in'] === true): ?>
                            <a href="reviews_page.php?room_id=<?php echo $room_id; ?>" class="rating-btn" style="text-decoration: none; display: block; text-align: center;">Rate Our Services</a>
                        <?php endif; ?>

                        <p class="cancellation-note">Free cancellation up to 24 hours before</p>
                    </div>
                </div>


                <div class="pricing-card">
                    <div class="price-label">From</div>
                    <div class="price"> $<?php echo $room['roomPriceOffPeak']?></div>
                    <div style="color: #666; font-size: 0.9rem; margin-bottom: 1rem;">/person</div>

                    <ul class="price-details">
                        <li><?php echo $room['roomDuration']?> minutes </li>
                        <li><?php echo $room['roomMin'] . '-' . $room['roomMax']; ?> players</li>
                        <li>Rating: ★<?php echo $avg_rating; ?> (<?php echo $review_count; ?> reviews)</li>
                    </ul>

                    <!-- leave this as it is for now there is a js function that opens this -->
                    <!-- also copy this to the other pages -->
                    <button type="button" id="openPopup" name="openPopup" class="book-btn">Book Now</button>

                    <?php if (isset($_SESSION['logged_in']) && $_SESSION['logged_in'] === true): ?>
                    <a href="reviews_page.php?room_id=<?php echo $room_id; ?>" class="rating-btn" style="text-decoration: none; display: block; text-align: center;">Rate Our Services</a>
                    <?php endif; ?>

                    <p class="cancellation-note">Free cancellation up to 24 hours before</p>
                </div>
            </div>

            <!-- Reviews Section -->
            <div class="reviews-section" style="margin-top: 3rem; background: #f8f9fa; padding: 2rem; border-radius: 8px; color: #333;">
                <h2>What Our Customers Say</h2>
            
                <?php
                $reviews = getRoomReviews($room_id);
                
                if (count($reviews) > 0):
                ?>
                    <div class="reviews-summary" style="margin-bottom: 2rem; padding: 1rem; background: #f8f9fa; border-radius: 8px;">
                        <div style="font-size: 2rem; color: #ffc107;">
                            <?php echo displayStarRating($avg_rating); ?>
                        </div>
                        <div style="font-size: 1.5rem; font-weight: bold; margin-top: 0.5rem; color: #2e2e2eff;">
                            <?php echo $avg_rating; ?> out of 5
                        </div>
                        <div style="color: #666; margin-top: 0.25rem;">
                            Based on <?php echo $review_count; ?> <?php echo $review_count == 1 ? 'review' : 'reviews'; ?>
                        </div>
                    </div>

                    <div class="reviews-list">
                        <?php foreach ($reviews as $review): ?>
                            <div class="review-item" style="border-bottom: 1px solid #ddd; padding: 1.5rem 0; color: #333;">
                                <div style="display: flex; justify-content: space-between; align-items: start; margin-bottom: 0.5rem;">
                                    <div>
                                        <div style="font-weight: bold; font-size: 1.1rem; color: #2e2e2e;">
                                            <?php echo htmlspecialchars($review['username']); ?>
                                        </div>
                                        <div style="color: #ffc107; font-size: 1.2rem; margin-top: 0.25rem;">
                                            <?php echo displayStarRating($review['rating']); ?>
                                        </div>
                                    </div>
                                    <div style="color: #666; font-size: 0.9rem;">
                                        <?php 
                                        $date = new DateTime($review['created_at']);
                                        echo $date->format('M j, Y'); 
                                        ?>
                                    </div>
                                </div>
                                
                                <?php if (!empty($review['comment'])): ?>
                                    <div style="margin-top: 1rem; line-height: 1.6; color: #555;">
                                        <?php echo nl2br(htmlspecialchars($review['comment'])); ?>
                                    </div>
                                <?php endif; ?>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php else: ?>
                    <div style="padding: 2rem; text-align: center; background: #f8f9fa; border-radius: 8px;">
                        <p style="color: #666; font-size: 1.1rem;">No reviews yet. Be the first to review this room!</p>
                        <?php if (isset($_SESSION['logged_in']) && $_SESSION['logged_in'] === true): ?>
                            <a href="reviews_page.php?room_id=<?php echo $room_id; ?>" class="btn btn-primary" style="margin-top: 1rem;">Write a Review</a>
                        <?php endif; ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>
        <!-- booking pop up -->
        <section id="modal" class="modal">
            <div class="modal-content">
                <span class="close">&times;</span>
                <iframe id="popupFrame" src=""><iframe>
            </div>
        </section>

    <?php else: ?>
        <!-- ERROR: room Not Found -->
        <div class="text-center" style="padding: 100px 0; background: rgba(255,255,255,0.05); border-radius: 15px; margin-top: 2rem;">
            <h1 class="text-warning mb-3">Room Not Found</h1>
            <p class="lead text-light mb-4">
                Sorry, we couldn't find a room with that name.<br>
                It may have been removed or the link is incorrect.
            </p>

            <a href="index.php" class="book-btn" style="text-decoration: none; display: inline-block; max-width: 200px;">
                Browse All Rooms
            </a>
        </div>
    <?php endif; ?>

    </main>

    <?php
    include "inc/footer.inc.php";
    ?>
</body>

</html>