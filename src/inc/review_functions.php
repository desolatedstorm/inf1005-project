<?php
/**
 * Get average rating for a specific room
 * @param string $room_name The room name
 * @return float Average rating or 0 if no reviews
 */
function getAverageRating($room_name) {
    require_once "db.inc.php";
    $conn = getDbConnection();
    
    $stmt = $conn->prepare("SELECT AVG(rating) as avg_rating, COUNT(*) as review_count FROM Reviews R JOIN Rooms M ON R.Rooms_roomID = M.roomID WHERE M.roomName = ?");
    $stmt->bind_param("s", $room_name);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($row = $result->fetch_assoc()) {
        $avg = $row['avg_rating'] ?? 0;
        $stmt->close();
        $conn->close();
        return round($avg, 1);
    }
    
    $stmt->close();
    $conn->close();
    return 0;
}

/**
 * Get number of reviews for a specific room
 * @param string $room_name The room name
 * @return int Number of reviews
 */
function getReviewCount($room_name) {
    require_once "db.inc.php";
    $conn = getDbConnection();
    
    $stmt = $conn->prepare("SELECT COUNT(*) as review_count FROM Reviews R JOIN Rooms M ON R.Rooms_roomID = M.roomID WHERE M.roomName = ?");
    $stmt->bind_param("s", $room_name);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($row = $result->fetch_assoc()) {
        $count = $row['review_count'] ?? 0;
        $stmt->close();
        $conn->close();
        return $count;
    }
    
    $stmt->close();
    $conn->close();
    return 0;
}

/**
 * Get all reviews for a specific room
 * @param string $room_name The room name
 * @return array Array of review objects
 */
function getRoomReviews($room_name) {
    require_once "db.inc.php";
    $conn = getDbConnection();
    
    $stmt = $conn->prepare("SELECT r.*, u.username, u.email 
            FROM Reviews r 
            JOIN Users u ON r.Users_userID = u.userID 
            JOIN Rooms rm ON r.Rooms_roomID = rm.roomID
            WHERE rm.roomName = ? 
            ORDER BY r.created_at DESC");

    $stmt->bind_param("s", $room_name);
    $stmt->execute();
    $result = $stmt->get_result();
    
    $reviews = [];
    while ($row = $result->fetch_assoc()) {
        $reviews[] = $row;
    }
    
    $stmt->close();
    $conn->close();
    return $reviews;
}

/**
 * Generate star rating HTML
 * @param float $rating The rating value (0-5)
 * @return string HTML for star display
 */
function displayStarRating($rating) {
    $full_stars = floor($rating);
    $half_star = ($rating - $full_stars) >= 0.5 ? 1 : 0;
    $empty_stars = 5 - $full_stars - $half_star;
    
    $html = '';
    
    // Full stars
    for ($i = 0; $i < $full_stars; $i++) {
        $html .= '★';
    }
    
    // Half star
    if ($half_star) {
        $html .= '⯨';
    }
    
    // Empty stars
    for ($i = 0; $i < $empty_stars; $i++) {
        $html .= '☆';
    }
    
    return $html;
}
?>
