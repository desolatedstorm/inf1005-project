<?php
// Start the session
session_start();

// Force specific values into the session
$_SESSION['logged_in'] = true;
$_SESSION['user_id'] = 999;       // Fake ID
$_SESSION['username'] = 'Test Admin';
$_SESSION['email'] = 'admin@test.com';
$_SESSION['is_admin'] = 1;        // 1 = Admin

echo "<h1>✅ You are now logged in as ADMIN.</h1>";
echo "<p>Session variables set.</p>";
echo "<ul>";
echo "<li><a href='index.php'>Go to Home</a></li>";
echo "<li><a href='create_room.php'>Test Create Room (Should Work)</a></li>";
echo "<li><a href='delete_room.php'>Test Delete Room (Should Work)</a></li>";
echo "</ul>";
?>