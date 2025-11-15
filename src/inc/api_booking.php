<?php // USE AS API
    header('Content-Type: application/json');

    require_once 'login_functions.php'; // edit once db class done

    list($db_host, $db_user, $db_pass, $db_name) = getDBEnvVar();

    // Define all available timeslots (24-hour format for database)
    // might have to change timings or seperate array of timing for each room
    $all_timeslots = array(
        '11:00:00',
        '14:00:00', //1pm to 2pm lunch break
        '16:00:00',
        '18:00:00',
        '20:00:00',
    );

    // Check if the request is POST
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {

        // Get the posted date
        $date = isset($_POST['date']) ? $_POST['date'] : null;

        // Validate date format (YYYY-MM-DD)
        if ($date && preg_match('/^\d{4}-\d{2}-\d{2}$/', $date)) {

            try {
                // Create database connection
                $conn = new mysqli($db_host, $db_user, $db_pass, $db_name);

                // Check connection
                if ($conn->connect_error) {
                    throw new Exception("Connection failed: " . $conn->connect_error);
                }

                // Prepare statement to get booked timeslots for the selected date
                $stmt = $conn->prepare("SELECT bookingTimeslot FROM Bookings 
                                        WHERE bookingDate = ? 
                                        AND bookingStatus != 'Cancelled'");
                $stmt->bind_param("s", $date);
                $stmt->execute();
                $result = $stmt->get_result();

                // Collect booked timeslots
                $booked_slots = array();
                while ($row = $result->fetch_assoc()) {
                    $booked_slots[] = $row['bookingTimeslot'];
                }

                // Calculate available timeslots (all slots minus booked slots)
                $available_slots = array_diff($all_timeslots, $booked_slots);

                // Convert to 12-hour format for display
                $display_slots = array();
                foreach ($available_slots as $slot) {
                    $time = DateTime::createFromFormat('H:i:s', $slot);
                    $display_slots[] = $time->format('g:i A');
                }

                // Re-index array to remove gaps from array_diff
                $display_slots = array_values($display_slots);

                // Close connections
                $stmt->close();
                $conn->close();

                // Return success response
                echo json_encode(array(
                    'success' => true,
                    'date' => $date,
                    'available_slots' => $display_slots,
                    'total_available' => count($display_slots),
                    'message' => 'Timeslots retrieved successfully'
                ));
                exit;

            } catch (Exception $e) {
                echo json_encode(array(
                    'success' => false,
                    'message' => 'Database error: ' . $e->getMessage()
                ));
            }

        } else {
            // Return error response for invalid date
            echo json_encode(array(
                'success' => false,
                'message' => 'Invalid date format. Expected YYYY-MM-DD'
            ));
        }

    } else {
        // Return error for non-POST requests
        echo json_encode(array(
            'success' => false,
            'message' => 'Only POST requests are allowed'
        ));
    }
?>