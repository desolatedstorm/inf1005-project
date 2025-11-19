<?php 
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
    
    $date = isset($_POST['date']) ? $_POST['date'] : null;
    if (!$date || !preg_match('/^\d{4}-\d{2}-\d{2}$/', $date)) {
        $success = false;
        $messages .= "Invalid date format. ";
    }

    $time = isset($_POST['time']) ? $_POST['time'] : null;
    if (!$time || !preg_match('/^\d{2}:\d{2}:\d{2}$/', $time)) {
        $success = false;
        $messages .= 'Invalid time format. ';
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

    if (!$success) {
        echo json_encode(array(
            'success' => false,
            'message' => $messages
        ));
        exit();
    }

    // ===========================
    // 2. HOLD THE SLOT
    // ===========================
    
    try {
        $conn = new mysqli($db_host, $db_user, $db_pass, $db_name);
        
        if ($conn->connect_error) {
            throw new Exception("Connection failed: " . $conn->connect_error);
        }

        // Start transaction
        $conn->begin_transaction();

        // Clean up expired holds (older than 5 minutes)
        $cleanup_stmt = $conn->prepare("
            DELETE FROM BookingHolding
            WHERE expires_at < NOW()
        ");
        $cleanup_stmt->execute();
        $cleanup_stmt->close();

        // Check if slot is already booked (confirmed/completed)
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
                'message' => 'This time slot is already booked.'
            ));
            exit();
        }

        // Check if slot is currently held by another user
        $check_hold = $conn->prepare("
            SELECT COUNT(*) as count, Users_userID FROM BookingHolding 
            WHERE holdDate = ? 
            AND holdTimeslot = ? 
            AND Rooms_roomID = ? 
            AND expires_at > NOW()
            GROUP BY Users_userID
        ");
        $check_hold->bind_param("ssi", $date, $time, $room_id);
        $check_hold->execute();
        $hold_result = $check_hold->get_result();
        $hold_row = $hold_result->fetch_assoc();
        $check_hold->close();
        
        if ($hold_row && $hold_row['count'] > 0 && $hold_row['Users_userID'] != $user_id) {
            $conn->rollback();
            echo json_encode(array(
                'success' => false,
                'message' => 'This time slot is currently being booked by another user. Please try again in a few minutes.'
            ));
            exit();
        }

        // Delete any existing holds by this user (in case they're rebooking)
        $delete_old_hold = $conn->prepare("
            DELETE FROM BookingHolding 
            WHERE Users_userID = ?
        ");
        $delete_old_hold->bind_param("i", $user_id);
        $delete_old_hold->execute();
        $delete_old_hold->close();

        // Create new hold (expires in 5 minutes)
        $hold_expires_at = date('Y-m-d H:i:s', strtotime('+5 minutes'));
        // $created_at = date('Y-m-d H:i:s');
        
        $insert_hold = $conn->prepare("
            INSERT INTO BookingHolding
            (holdDate, holdTimeslot, expires_at, Rooms_roomID, Users_userID) 
            VALUES (?, ?, ?, ?, ?)
        "); //created_at, $created_at
        $insert_hold->bind_param("sssii", $date, $time, $hold_expires_at, $room_id, $user_id);
        
        if (!$insert_hold->execute()) {
            throw new Exception("Failed to create hold: " . $insert_hold->error);
        }

        $hold_id = $conn->insert_id;
        $insert_hold->close();

        // Commit transaction
        $conn->commit();
        $conn->close();

        // ===========================
        // 3. RETURN SUCCESS WITH HOLD ID
        // ===========================
        
        echo json_encode(array(
            'success' => true,
            'message' => 'Slot held successfully for 5 minutes',
            'hold_id' => $hold_id,
            'expires_at' => $hold_expires_at,
            'expires_in_seconds' => 300 // 5 minutes
        ));

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