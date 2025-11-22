<?php 
session_start();
header('Content-Type: application/json');

require_once __DIR__ . "/../vendor/autoload.php";
require_once __DIR__ . "/../inc/login_functions.php";

list($db_host, $db_user, $db_pass, $db_name, $stripekey) = getDBEnvVar();

$success = true;
$messages = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    
    // ===========================
    // 1. VALIDATE INPUT DATA
    // ===========================
    $user_id = $_SESSION['user-id'] ?? 1;
    if (!$user_id || $user_id <= 0) {
        $success = false;
        $messages .= 'Invalid user ID. ';
    }

    $room_id = $_SESSION['room_id'] ?? null;
    if (!$room_id || $room_id <= 0) {
        $success = false;
        $messages .= "Invalid room ID. ";
    }

    $stripe_payment_id = $_SESSION['stripe_payment_intent_id'] ?? null;
    if (!$stripe_payment_id) {
        $success = false;
        $messages .= "Missing payment intent id. ";
    }

    $date = isset($_POST['date']) ? $_POST['date'] : null;
    if (!$date || !preg_match('/^\d{4}-\d{2}-\d{2}$/', $date)) {
        $success = false;
        $messages .= "Invalid date format. Expected yyyy-mm-dd. ";
    }

    $time = isset($_POST['time']) ? $_POST['time'] : null;
    if (!$time || !preg_match('/^\d{2}:\d{2}:\d{2}$/', $time)) {
        $success = false;
        $messages .= 'Invalid time format. Expected HH:mm:ss. ';
    }

    $pax = isset($_POST['pax']) ? intval($_POST['pax']) : null;
    if (!$pax || $pax <= 0) {
        $success = false;
        $messages .= 'Invalid PAX. ';
    }

    $user_name = isset($_POST['user_name']) ? $_POST['user_name'] : null;

    $room_name = isset($_POST['room_name']) ? $_POST['room_name'] : null;

    // Generate Booking Reference
    $ref = generateBookingRef();
    
    if (!$ref) { // validate booking ref
        $success = false;
        $messages .= "Invalid booking reference. ";
    }
        
    if (!$success) {
        echo json_encode(array(
            'success' => false,
            'message' => $messages
        ));
        exit();
    }

    // ===========================
    // 2. INSERT BOOKING INTO DATABASE
    // (Only AFTER payment is confirmed by Stripe)
    // ===========================
    
    try {
        $conn = new mysqli($db_host, $db_user, $db_pass, $db_name);
        
        if ($conn->connect_error) {
            throw new Exception("Connection failed: " . $conn->connect_error);
        }

        // query room, user name, price from DB
        $roomName = $conn->prepare("
            SELECT roomName FROM Rooms where RoomID = ?
        ");
        $roomName->bind_param("i", $room_id);
        $roomName->execute();
        $room_result = $roomName->get_result()->fetch_assoc();
        $roomName->close();
        // check
        if (!$room_result) {
            $success = false;
            $messages .= "No such room name. ";
        }

        $user = $conn->prepare("
            SELECT username, email FROM Users where userID = ?
        ");
        $user->bind_param("i", $user_id);
        $user->execute();
        $user_result = $user->get_result()->fetch_assoc();
        $user->close();
        //check
        if (!$user_result) {
            $success = false;
            $messages .= "No user. ";
        }

        // Room price
        $price = $conn->prepare("SELECT roomPriceOffPeak from Rooms WHERE roomID = ?");
        $price->bind_param("i", $room_id);
        $price->execute();
        $price_result = $price->get_result()->fetch_array();
        $price->close();
        if(!$price_result) {
            $success = false;
            $messages .= "Failed to get room price. ";
        }
        $subtotal = $price_result['roomPriceOffPeak'] * $pax;

        // Check if slot is still available
        $check_stmt = $conn->prepare("
            SELECT COUNT(*) as count FROM Bookings 
            WHERE bookingDate = ? 
            AND bookingTimeslot = ? 
            AND Rooms_roomID = ? 
            AND bookingStatus IN ('Confirmed', 'Completed')
        ");
        $check_stmt->bind_param("ssi", $date, $time, $room_id);
        $check_stmt->execute();
        $check_result = $check_stmt->get_result();
        $row = $check_result->fetch_assoc();
        
        if ($row['count'] > 0) {
            $success = false;
            $messages .= "This timeslot is no longer available. ";
        }

        // Insert booking with "Confirmed" status
        $booking_status = "Confirmed";
        $created_at = date('Y-m-d H:i:s');
        
        $stmt = $conn->prepare("
            INSERT INTO Bookings 
            (bookingRef, bookingDate, bookingTimeslot, numPlayers, totalPrice, bookingStatus, 
            created_at, stripe_payment_id, Rooms_roomID, Users_userID) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
        ");

        $stmt->bind_param(
            "sssidsssii", 
            $ref,
            $date, 
            $time, 
            $pax,
            $subtotal, 
            $booking_status,
            $created_at,
            $stripe_payment_id, 
            $room_id, 
            $user_id
        );
        
        if (!$stmt->execute()) {
            $success = false;
            $messages .= "Failed to insert booking: " . $stmt->error;
        }

        if (!$success) {
            echo json_encode(array(
            'success' => $success,
            'message' => $messages
            ));
            exit();
        }

        $booking_id = $conn->insert_id;

        // cancellation token (64 random varchar)
        $cancel_token = bin2hex(random_bytes(32));

        // Store it in your Bookings table (you'll need to add this column)
        $stmt = $conn->prepare("UPDATE Bookings SET cancel_token = ? WHERE bookingID = ?");
        $stmt->bind_param("si", $cancel_token, $booking_id);
        if (!$stmt->execute()) {
            throw new Exception("Failed to update cancellation token: " . $stmt->errno);
        }

        $stmt->close();
        $conn->close();

        unset($_SESSION['stripe_payment_intent_id']);

        // ===========================
        // 3. SEND SUCCESS RESPONSE
        // ===========================
        
        // Set Sessions
        //$_SESSION['bookingRef'] = $ref;
        $_SESSION['date'] = $date;
        $_SESSION['time'] = $time;
        $_SESSION['username'] = $user_result['username'];
        $_SESSION['email'] = $user_result['email'];
        $_SESSION['room_name'] = $room_result['roomName'];
        $_SESSION['pax'] = $pax;
        $_SESSION['total'] = $subtotal;
        $_SESSION['bookingSuccess'] = true;

        echo json_encode(array(
            'success' => true,
            'message' => 'Booking confirmed successfully',
            'booking_ref' => $ref
        ));

        // TODO: Send confirmation email
        /*
        $user_email = getUserEmail($user_id); // Implement this function
        $subject = "Booking Confirmation";
        $message = "Your booking has been confirmed!\n\n";
        $message .= "Booking ID: $booking_id\n";
        $message .= "Date: $date\n";
        $message .= "Time: $time\n";
        $message .= "Players: $pax\n";
        $message .= "Total: $$subtotal\n";
        $cancel_link = "https://yourdomain.com/cancel_booking.php?token=" . $cancel_token;

        // In your email body
        $email_body .= "Need to cancel? Click here: " . $cancel_link;
        
        mail($user_email, $subject, $message);
        */

    } catch (Exception $e) {
        echo json_encode(array(
            'success' => false,
            'message' => 'Database error: ' . $e->getMessage()
        ));
    }

} else {
    echo json_encode(array(
        'success' => false,
        'message' => 'Only POST requests are allowed'
    ));
}

function generateBookingRef() {
    return strtoupper(substr(base_convert(bin2hex(random_bytes(4)), 16, 36), 0, 8));
}
?>