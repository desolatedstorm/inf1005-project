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

        // query room and user name from DB if not set
        if ($room_name == null) {
            $roomName = $conn->prepare("
                SELECT roomName FROM Rooms where RoomID = ?
            ");
            $roomName->bind_param("i", $room_id);
            $roomName->execute();
            $room_result = $roomName->get_result()->fetch_assoc();
            $roomName->close();
            // check    
        }

        if ($user_name == null) {
            $user = $conn->prepare("
                SELECT username, email FROM Users where userID = ?
            ");
            $user->bind_param("i", $user_id);
            $user->execute();
            $user_result = $user->get_result()->fetch_assoc();
            $user->close();
            //check
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
            (bookingRef, bookingDate, bookingTimeslot, numPlayers, totalPrice, bookingStatus, 
            created_at, Rooms_roomID, Users_userID) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)
        ");

        $stmt->bind_param(
            "sssidssii", 
            $ref,
            $date, 
            $time, 
            $pax,
            $subtotal, 
            $booking_status,
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