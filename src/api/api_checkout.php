<?php 
header('Content-Type: application/json');

require_once '../vendor/autoload.php';
require_once '../inc/login_functions.php';

list($db_host, $db_user, $db_pass, $db_name, $stripekey) = getDBEnvVar();

$success = true;
$messages = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    
    // ===========================
    // 1. VALIDATE INPUT DATA
    // ===========================
    
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

    $user_id = isset($_POST['user_id']) ? intval($_POST['user_id']) : null;
    if (!$user_id || $user_id <= 0) {
        $success = false;
        $messages .= 'Invalid user ID. ';
    }

    $room_id = isset($_POST['room_id']) ? intval($_POST['room_id']) : null;
    if (!$room_id || $room_id <= 0) {
        $success = false;
        $messages .= "Invalid room ID. ";
    }

    $pax = isset($_POST['pax']) ? intval($_POST['pax']) : null;
    if (!$pax || $pax <= 0) {
        $success = false;
        $messages .= 'Invalid PAX. ';
    }
    
    $subtotal = isset($_POST['subtotal']) ? floatval($_POST['subtotal']) : null;
    if (!$subtotal || $subtotal <= 0) {
        $success = false;
        $messages .= 'Invalid total amount. ';
    }

    // Billing address (optional fields for this endpoint)
    $billing_address = isset($_POST['billing_address']) ? $_POST['billing_address'] : '';
    $billing_city = isset($_POST['billing_city']) ? $_POST['billing_city'] : '';
    $billing_postal = isset($_POST['billing_postal']) ? $_POST['billing_postal'] : '';
    $billing_country = isset($_POST['billing_country']) ? $_POST['billing_country'] : '';

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
            echo json_encode(array(
                'success' => false,
                'message' => 'This time slot is no longer available.'
            ));
            exit();
        }

        // Insert booking with "Confirmed" status (payment already succeeded)
        $booking_status = "Confirmed";
        $created_at = date('Y-m-d H:i:s');
        
        $stmt = $conn->prepare("
            INSERT INTO Bookings 
            (bookingDate, bookingTimeslot, numPlayers, totalPrice, bookingStatus, 
            billing_address, billing_city, billing_postal, billing_country,
            created_at, Rooms_roomID, Users_userID) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
        ");

        $stmt->bind_param(
            "ssidssssssii", 
            $date, 
            $time, 
            $pax,
            $subtotal, 
            $booking_status,
            $billing_address,
            $billing_city,
            $billing_postal,
            $billing_country,
            $created_at, 
            $room_id, 
            $user_id
        );
        
        if (!$stmt->execute()) {
            throw new Exception("Failed to insert booking: " . $stmt->error);
        }

        $booking_id = $conn->insert_id;
        
        $stmt->close();
        $conn->close();

        // ===========================
        // 3. SEND SUCCESS RESPONSE
        // ===========================
        
        echo json_encode(array(
            'success' => true,
            'message' => 'Booking confirmed successfully',
            'booking_id' => $booking_id
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
?>