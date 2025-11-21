<?php
session_start();
session_destroy();
?>
<!DOCTYPE html>
<html>
<head>
    <title>Session Cleared</title>
</head>
<body>
    <h1>Test Session Cleared</h1>
    <p>You have been "logged out".</p>
    <a href="test_review.php">Back to test page</a> | 
    <a href="index.php">Home</a>
</body>
</html>
