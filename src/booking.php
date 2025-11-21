<?php 
session_start();

$token = $_SESSION['allow_booking'];
$room_id = $_SESSION['room_id'] ?? '';
$room_name = $_SESSION['room_name'] ?? '';
$desc = $_SESSION['desc'] ?? '';
$rtn_dest = "index.php";

if (!$token || !$_SESSION['allow_booking'] || !hash_equals($_SESSION['allow_booking'], $token)) {
    http_response_code(403);
    header("Location: " . $rtn_dest);
    exit();
}

if (!$room_id) {
    //do smth
}
// else if (!$room_name || !$min || !$max || !$desc || !$price) {
//     //query from db
// }

unset($_SESSION['allow_booking']);
?>
<!-- Floating Window Booking -->
<!DOCTYPE html>
<html lang="en">
    <head>
        <title>
            Booking
        </title>
        <?php
            include "inc/head.inc.php"
        ?>
        <link rel="stylesheet" href="css/calendar.css"> 
        <script defer src="js/calendar.js"></script>
        <script src="https://js.stripe.com/v3/"></script> <!-- TODO: add integrity/CSRF -->
    </head>
    <body>
        <main class="container container-fluid">
            <h1 class="display-1">
                Book Your Experience
            </h1>
            <hr>
            <h2><?php echo htmlspecialchars($room_name, ENT_QUOTES | ENT_HTML5, 'UTF-8') ?></h2>
            <p><?php echo htmlspecialchars($desc, ENT_QUOTES | ENT_HTML5, 'UTF-8') ?></p>
            <div class="row row-cols-2">
                <img src="images/calendar.png" class="logo me-2" alt="calendar logo">
                <p>Select a date</p>
            </div>
            <?php
                include "inc/calendar.inc.php"
            ?>
        </main>
    </body>
</html>
