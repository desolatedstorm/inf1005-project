<!doctype html>
<html lang="en">
<link rel="icon" type="image/x-icon" href="../images/home.ico">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <meta name="description" content="The Pharaoh's Curse - Escape Room Experience" />

    <title>The Pharaoh's Curse - Escape Room</title>
    <?php
    include "inc/head.inc.php";
    ?>
    <link rel="stylesheet" href="css/rooms.css">

</head>

<body>
    <?php
    include "inc/nav.inc.php";
    ?>

    <main class="page-content">
        <div class="container">
            <a href="index.php" class="back-link">← Back to Rooms</a>


            <img src="/images/P_Curse.jpg" alt="The Pharaoh's Curse" class="room-hero" />


            <div class="thumbnail-gallery">
                <img src="/images/P_Curse.jpg" alt="Thumbnail 1" class="active" onclick="changeHeroImage(this.src)" />

            </div>

            <div class="room-content">

                <div class="room-details">
                    <h1 class="room-title">The Pharaoh's Curse</h1>

                    <div class="room-badges">
                        <span class="badge bg-danger">Mildly Scary</span>
                        <span class="badge bg-warning text-dark">Difficulty 3/5</span>
                        <span class="badge bg-light text-dark">adventure</span>
                        <span class="badge bg-light text-dark">mystery</span>
                    </div>

                    <h3>About This Room</h3>
                    <p>Uncover ancient secrets in the tomb of a forgotten pharaoh. Solve hieroglyphic puzzles and avoid
                        deadly traps.</p>

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

                    <a href="booking.php?room=pharaoh" class="book-btn">Book Now</a>

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