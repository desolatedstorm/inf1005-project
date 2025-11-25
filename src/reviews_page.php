<?php
session_start();

$_SESSION['logged_in'] = true; // TODO: remove aft tes
// Check if user is logged in
if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    header("Location: login.php");
    exit();
}

// Get room ID from URL
if (!isset($_GET['room_id'])) {
    header("Location: index.php");
    exit();
}

$room_id = (int)$_GET['room_id'];
?>
<!doctype html>
<html lang="en">
<link rel="icon" type="image/x-icon" href="../images/home.ico">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <meta name="description" content="Leave a Review" />

    <title>Let Us Know Your Thoughts!</title>
    <?php include "inc/head.inc.php"; ?>
    <link href="css/style.css" rel="stylesheet" />
    <style>
        .star-rating {
            direction: rtl;
            display: inline-flex;
            font-size: 2rem;
            gap: 0.25rem;
        }
        .star-rating input[type="radio"] {
            display: none;
        }
        .star-rating label {
            color: #ddd;
            cursor: pointer;
            transition: color 0.2s;
        }
        .star-rating label:hover,
        .star-rating label:hover ~ label,
        .star-rating input[type="radio"]:checked ~ label {
            color: #ffc107;
        }
    </style>
</head>

<body>
    <?php include "inc/nav.inc.php"; ?>
    
    <main class="container mt-5 mb-5">
        <h1>Leave a Review</h1>
        <p class="text-white">Your email address will not be published. Required fields are marked *</p>

        <form action="process_review.php" method="POST" class="mt-4">
            <input type="hidden" name="room_id" value="<?php echo $room_id; ?>">
            
            <div class="mb-4">
                <label class="form-label">Rating *</label>
                <div class="star-rating">
                    <input type="radio" id="star5" name="rating" value="5" required />
                    <label for="star5" title="5 stars">★</label>
                    
                    <input type="radio" id="star4" name="rating" value="4" />
                    <label for="star4" title="4 stars">★</label>
                    
                    <input type="radio" id="star3" name="rating" value="3" />
                    <label for="star3" title="3 stars">★</label>
                    
                    <input type="radio" id="star2" name="rating" value="2" />
                    <label for="star2" title="2 stars">★</label>
                    
                    <input type="radio" id="star1" name="rating" value="1" />
                    <label for="star1" title="1 star">★</label>
                </div>
            </div>

            <div class="mb-3">
                <label for="comment" class="form-label">Comment (Optional)</label>
                <textarea name="comment" id="comment" class="form-control" rows="5" 
                    placeholder="Tell us about your experience..."></textarea>
            </div>

            <button type="submit" class="btn btn-primary">Submit Review</button>
            <a href="room.php?id=<?php echo $room_id; ?>" class="btn btn-secondary">Cancel</a>
        </form>
    </main>

    <?php include "inc/footer.inc.php"; ?>
</body>
</html>