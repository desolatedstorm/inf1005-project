<?php
session_start();

// Manually set session variables to simulate logged-in user
$_SESSION['logged_in'] = true;
$_SESSION['user_id'] = 1; // Change this to an existing user ID from your Users table
$_SESSION['user_fname'] = 'Test';
$_SESSION['user_lname'] = 'User';
$_SESSION['user_email'] = 'test@example.com';

echo "<h1>Session Set Successfully!</h1>";
echo "<p>You are now 'logged in' as a test user.</p>";
echo "<p>Session Data:</p>";
echo "<pre>";
print_r($_SESSION);
echo "</pre>";

echo "<h3>Now you can:</h3>";
echo "<ul>";
echo "<li><a href='room.php?id=1'>Go to Room 1 and test rating</a></li>";
echo "<li><a href='reviews_page.php?room_id=1'>Go directly to review page for Room 1</a></li>";
echo "<li><a href='index.php'>Go to home page</a></li>";
echo "</ul>";

echo "<hr>";
echo "<h3>Or submit a test review directly:</h3>";
?>

<!DOCTYPE html>
<html>
<head>
    <title>Test Review Submission</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; }
        .star-rating { font-size: 2rem; }
        .star-rating input { margin: 0 5px; }
    </style>
</head>
<body>
    <form action="process_review.php" method="POST">
        <h2>Quick Test Review Form</h2>
        
        <div>
            <label>Room ID:</label>
            <input type="number" name="room_id" value="1" required>
        </div>
        
        <div class="star-rating">
            <label>Rating:</label><br>
            <input type="radio" name="rating" value="1" id="r1"> <label for="r1">1★</label>
            <input type="radio" name="rating" value="2" id="r2"> <label for="r2">2★</label>
            <input type="radio" name="rating" value="3" id="r3" checked> <label for="r3">3★</label>
            <input type="radio" name="rating" value="4" id="r4"> <label for="r4">4★</label>
            <input type="radio" name="rating" value="5" id="r5"> <label for="r5">5★</label>
        </div>
        
        <div>
            <label>Comment:</label><br>
            <textarea name="comment" rows="4" cols="50">This is a test review!</textarea>
        </div>
        
        <button type="submit">Submit Test Review</button>
    </form>
    
    <hr>
    
    <h3>Check Database:</h3>
    <p>After submitting, you can verify the review was saved by checking your database:</p>
    <code>SELECT * FROM Reviews ORDER BY created_at DESC LIMIT 5;</code>
    
    <h3>Clear Session:</h3>
    <a href="test_logout.php">Logout (clear test session)</a>
</body>
</html>
