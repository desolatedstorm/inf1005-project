<?php 
header('Content-Type: application/json');

//require_once '../vendor/autoload.php';
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

    // Billing address
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
    // ===========================
    
    try {
        $conn = new mysqli($db_host, $db_user, $db_pass, $db_name);
        
        if ($conn->connect_error) {
            throw new Exception("Connection failed: " . $conn->connect_error);
        }

        // Start transaction
        $conn->begin_transaction();

        // Verify that this user has a valid hold on this slot
        $check_hold = $conn->prepare("
            SELECT holdID FROM BookingHolds 
            WHERE bookingDate = ? 
            AND bookingTimeslot = ? 
            AND Rooms_roomID = ? 
            AND Users_userID = ?
            AND hold_expires_at > NOW()
        ");
        $check_hold->bind_param("ssii", $date, $time, $room_id, $user_id);
        $check_hold->execute();
        $hold_result = $check_hold->get_result();
        $check_hold->close();
        
        if ($hold_result->num_rows === 0) {
            $conn->rollback();
            echo json_encode(array(
                'success' => false,
                'message' => 'Your hold on this time slot has expired. Please select the slot again.'
            ));
            exit();
        }

        // Double-check slot is still available (not booked by someone else)
        $check_booking = $conn->prepare("
            SELECT COUNT(*) as count FROM Bookings 
            WHERE bookingDate = ? 
            AND bookingTimeslot = ? 
            AND Rooms_roomID = ? 
            AND bookingStatus IN ('Confirmed', 'Completed')
        ");
        $check_booking->bind_param("ssi", $date, $time, $room_id);
        $check_booking->execute();
        $booking_result = $check_booking->get_result();
        $booking_row = $booking_result->fetch_assoc();
        $check_booking->close();
        
        if ($booking_row['count'] > 0) {
            $conn->rollback();
            echo json_encode(array(
                'success' => false,
                'message' => 'This time slot is no longer available.'
            ));
            exit();
        }

        // Insert booking with "Confirmed" status
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
            "ssifsssssiii", 
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

        // Remove the hold since booking is now confirmed
        $delete_hold = $conn->prepare("
            DELETE FROM BookingHolds 
            WHERE bookingDate = ? 
            AND bookingTimeslot = ? 
            AND Rooms_roomID = ? 
            AND Users_userID = ?
        ");
        $delete_hold->bind_param("ssii", $date, $time, $room_id, $user_id);
        $delete_hold->execute();
        $delete_hold->close();

        // Commit transaction
        $conn->commit();
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
        $user_email = getUserEmail($user_id);
        $subject = "Booking Confirmation #$booking_id";
        $message = "Your booking has been confirmed!\n\n";
        $message .= "Booking ID: $booking_id\n";
        $message .= "Date: $date\n";
        $message .= "Time: $time\n";
        $message .= "Players: $pax\n";
        $message .= "Total: $$subtotal\n";
        
        mail($user_email, $subject, $message);
        */

    } catch (Exception $e) {
        if (isset($conn)) {
            $conn->rollback();
            $conn->close();
        }
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