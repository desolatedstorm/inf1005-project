<?php
// Start or resume the session
session_start();

// Force specific values into the session to simulate an Admin login
$_SESSION['logged_in'] = true;
$_SESSION['user_id'] = 4;           // ID 4 is 'admin_user' in your seed data
$_SESSION['username'] = 'admin_user';
$_SESSION['email'] = 'admin@myhorror.com';
$_SESSION['is_admin'] = 1;          // 1 = TRUE (This enables access to create/delete pages)

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Test Admin Mode</title>
    <style>
        body { font-family: sans-serif; background: #222; color: #fff; text-align: center; padding: 50px; }
        a { color: #f59f00; text-decoration: none; font-size: 1.2rem; display: block; margin: 10px; }
        a:hover { text-decoration: underline; }
        .success { color: #4cd137; font-size: 2rem; margin-bottom: 20px; }
        .warning { background: #c23616; color: white; padding: 10px; margin-top: 30px; display: inline-block; border-radius: 5px;}
    </style>
</head>
<body>

    <h1 class="success">✅ Admin Session Active</h1>
    <p>You are now logged in as: <strong><?php echo $_SESSION['username']; ?></strong> (ID: <?php echo $_SESSION['user_id']; ?>)</p>
    <p>Admin Status: <strong><?php echo $_SESSION['is_admin']; ?></strong></p>

    <hr style="border-color: #444; width: 50%; margin: 30px auto;">

    <h3>Quick Links:</h3>
    <a href="index.php">🏠 Go to Home Page</a>
    <a href="create_room.php">➕ Test Create Room</a>
    <a href="delete_room.php">⚙️ Test Manage/Delete Rooms</a>

    <div class="warning">
        ⚠️ <strong>IMPORTANT:</strong> Delete this file before submitting or deploying your project!
        <br>It allows anyone to bypass your password security.
    </div>

</body>
</html>