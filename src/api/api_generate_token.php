<?php 
// Generate token to access booking page
session_start();
if ($_SERVER['REQUEST_METHOD'] !== 'POST'){
    header("Location: ../index.php");
    die("Not allowed");
}
// random 32 byte string
$token = bin2hex(random_bytes(32));
$_SESSION['allow_booking'] = $token;
?>
