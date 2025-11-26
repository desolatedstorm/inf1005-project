<?php
session_start();

// Check if switching user via URL parameter
if (isset($_GET['switch_user'])) {
    $user_id = (int)$_GET['switch_user'];
    
    // Set different user data based on user ID
    $users = [
        1 => ['fname' => 'Test', 'lname' => 'User', 'email' => 'test@example.com', 'username' => 'victim_1'],
        4 => ['fname' => 'Admin', 'lname' => 'User', 'email' => 'admin@myhorror.com', 'username' => 'admin_user'],
        5 => ['fname' => 'Tester', 'lname' => 'McTest', 'email' => 'test@yourhorror.com', 'username' => 'tester'],
        6 => ['fname' => 'Cascade', 'lname' => 'Test', 'email' => 'cascade@example.com', 'username' => 'test_user_cas'],
    ];
    
    if (isset($users[$user_id])) {
        $_SESSION['user_id'] = $user_id;
        $_SESSION['user_fname'] = $users[$user_id]['fname'];
        $_SESSION['user_lname'] = $users[$user_id]['lname'];
        $_SESSION['user_email'] = $users[$user_id]['email'];
        $_SESSION['username'] = $users[$user_id]['username'];
    }
}

// Set default user if not logged in
if (!isset($_SESSION['logged_in'])) {
    $_SESSION['logged_in'] = true;
    $_SESSION['user_id'] = 1;
    $_SESSION['user_fname'] = 'Test';
    $_SESSION['user_lname'] = 'User';
    $_SESSION['user_email'] = 'test@example.com';
    $_SESSION['username'] = 'victim_1';
}

echo "<h1>Session Set Successfully!</h1>";
echo "<p>You are now 'logged in' as: <strong>" . $_SESSION['username'] . "</strong> (User ID: " . $_SESSION['user_id'] . ")</p>";

echo "<h3>Switch User:</h3>";
echo "<ul>";
echo "<li><a href='?switch_user=1'>Switch to User 1 (victim_1)</a></li>";
echo "<li><a href='?switch_user=4'>Switch to User 4 (admin_user)</a></li>";
echo "<li><a href='?switch_user=5'>Switch to User 5 (tester)</a></li>";
echo "<li><a href='?switch_user=6'>Switch to User 6 (test_user_cas)</a></li>";
echo "</ul>";

echo "<p>Session Data:</p>";
echo "<pre>";
print_r($_SESSION);
echo "</pre>";

echo "<h3>Now you can:</h3>";
echo "<ul>";
echo "<li><a href='room.php?name=The Cursed Cabin'>Go to Room 1 (The Cursed Cabin) and test rating</a></li>";
echo "<li><a href='room.php?name=Asylum'>Go to Room 2 (Asylum) and test rating</a></li>";
echo "<li><a href='reviews_page.php?room_id=1'>Go directly to review page for Room 1</a></li>";
echo "<li><a href='reviews_page.php?room_id=2'>Go directly to review page for Room 2</a></li>";
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
