<?php
session_start();

// Set a flag to allow booking.php to load
$_SESSION['allow_booking'] = true;
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <?php 
        include "inc/head.inc.php"
        ?>
        <script defer src="js/main.js"></script>
        <link rel="stylesheet" href="css/popup.css">
    </head>
    <body>
        
        <h1>This is to test page popup in page</h1>
        <button type="button" id="openPopup" name="openPopup">Click me</button>
        
        <div id="modal" class="modal">
            <div class="modal-content">
                <span class="close">&times;</span>
                <iframe id="popupFrame" src=""><iframe>
            </div>
        </div>
    </body>
</html>