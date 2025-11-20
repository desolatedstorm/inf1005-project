<?php
include "inc/functions.php";

// Placeholder for Admin Check
// session_start();
// if (!isset($_SESSION['user_id']) || $_SESSION['is_admin'] !== 1) {
//     header("Location: login.php");
//     exit();
// }

$conn = getDbConnection();
$rooms = [];

// Fetch all rooms to display in the list
$sql = "SELECT roomID, roomName, roomDifficulty, imagePath FROM Rooms ORDER BY roomID DESC";
$result = $conn->query($sql);
if ($result && $result->num_rows > 0) {
    $rooms = $result->fetch_all(MYSQLI_ASSOC);
}
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <title>Manage Rooms - Escape Quest</title>
    <?php include "inc/head.inc.php" ?>
    <link rel="stylesheet" href="css/rooms.css">
</head>

<body>
    <?php include "inc/nav.inc.php" ?>

    <main class="page-content section-gap">
        <div class="container">
            <?php if (isset($_GET['msg']) && $_GET['msg'] == 'deleted'): ?>
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <strong>Success!</strong> The room and its image have been deleted.
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            <?php endif; ?>2
            <h2 class="text-center mb-5 text-warning">Manage Rooms</h2>

            <div class="table-responsive">
                <table class="table table-dark table-hover align-middle">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Image</th>
                            <th>Room Name</th>
                            <th>Difficulty</th>
                            <th class="text-end">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($rooms)): ?>
                            <tr>
                                <td colspan="5" class="text-center py-4">No rooms found.</td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($rooms as $room): ?>
                                <tr>
                                    <td><?php echo $room['roomID']; ?></td>
                                    <td>
                                        <img src="<?php echo htmlspecialchars($room['imagePath'] ?? 'images/placeholder.png'); ?>"
                                            alt="Thumbnail"
                                            style="width: 60px; height: 40px; object-fit: cover; border-radius: 4px;">
                                    </td>
                                    <td><?php echo htmlspecialchars($room['roomName']); ?></td>
                                    <td><?php echo htmlspecialchars($room['roomDifficulty']); ?></td>
                                    <td class="text-end">

                                        <!-- deletion form -->
                                        <form action="process_delete_room.php" method="POST"
                                            onsubmit="return confirm('Are you sure you want to delete \'<?php echo htmlspecialchars($room['roomName']); ?>\'? This cannot be undone.');"
                                            style="display: inline-block;">

                                            <input type="hidden" name="roomID" value="<?php echo $room['roomID']; ?>">
                                            <button type="submit" class="btn btn-danger btn-sm">Delete</button>
                                        </form>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>

            <div class="text-center mt-4">
                <a href="create_room.php" class="btn btn-success me-2">Create New Room</a>
                <a href="index.php" class="btn btn-outline-light">Back to Home</a>
            </div>
        </div>
    </main>

    <?php include "inc/footer.inc.php" ?>
</body>

</html>