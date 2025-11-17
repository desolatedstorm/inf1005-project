<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <meta name="description" content="Haunted Mansion - Escape Room Experience" />

    <title>Haunted Mansion - Escape Room</title>
    <?php
    include "inc/head.inc.php";
    ?>
    <link rel="icon" type="image/x-icon" href="/images/home.ico">
    <link rel="stylesheet" href="css/rooms.css">
</head>

<body>
    <?php
    include "inc/nav.inc.php";
    ?>

    <main class="page-content">
        <div class="container">
            <a href="index.php" class="back-link">← Back to Rooms</a>


            <img src="/images/Haunt.jpg" alt="Haunted Mansion" class="room-hero" />


            <div class="thumbnail-gallery">
                <img src="/images/Haunt.jpg" alt="Thumbnail 1" class="active" onclick="changeHeroImage(this.src)" />

            </div>

            <div class="room-content">

                <div class="room-details">
                    <h1 class="room-title">Haunted Mansion</h1>

                    <div class="room-badges">
                        <span class="badge bg-danger">Very Scary</span>
                        <span class="badge bg-warning text-dark">Difficulty 5/5</span>
                        <span class="badge bg-light text-dark">horror</span>
                        <span class="badge bg-light text-dark">mystery</span>
                    </div>

                    <h3>About This Room</h3>
                    <p>Explore the eerie halls of the Haunted Mansion. Solve supernatural puzzles and uncover dark secrets.</p>

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
                    <div class="price">$45</div>
                    <div style="color: #666; font-size: 0.9rem; margin-bottom: 1rem;">/person</div>

                    <ul class="price-details">
                        <li>80 minutes</li>
                        <li>2-8 players</li>
                        <li>Difficulty: 5/5</li>
                    </ul>

                    <a href="booking.php?room=haunted_mansion" class="book-btn">Book Now</a>
                    <p class="cancellation-note">Free cancellation up to 24 hours before</p>
                </div>
            </div>
        </div>
    </main>

    <?php
    include "inc/footer.inc.php";
    ?>
</body>

</html>