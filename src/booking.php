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
    </head>
    <body>
        <main class="container container-fluid">
            <h1 class="display-1">
                Book Your Experience
            </h1>
            <hr>
            <h2>The Pharaoh&apos;s Curse</h2>
            <p>Uncover ancient secrets in the tomb of a forgotten pharaoh. Solve hieroglyphic puzzles and avoid deadly traps&dot;</p>
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
