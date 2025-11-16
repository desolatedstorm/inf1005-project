<?php

include "inc/db.inc.php";
$conn = getDbConnection();

//variable to hold list of rooms and number of rooms
$rooms = [];
$roomCount = 0;

//to-do: implement a variable imagePath and also add data into the DB
$sql = "SELECT roomID, roomName, roomFearLevel, roomExperienceType, roomGenre FROM Rooms";
$result = $conn->query($sql);

//check for results
if ($result && $result->num_rows > 0) {
    $rooms = $result->fetch_all(MYSQLI_ASSOC);
    $roomCount = $result->num_rows;
}

//close connection
$conn->close();

//helper function to get the right css color for fear factor
function getBadgeColor($fearLevel)
{
    switch ($fearLevel) {
        case 'Very Scary':
            return 'bg-danger';
        case 'Scary':
            return 'bg-warning text-dark';
        case 'Mildly Scary':
            return 'bg-info text-dark';
        case 'Not Scary':
            return 'bg-secondary';
        default:
            return 'bg-light text-dark';
    }
}

//helper function to slugify text
function slugify($text)
{
    //replaces all spaces with hyphens
    $text = str_replace(' ', '-', $text);
    //and converts to lowercase
    return strtolower($text);
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <title>Esacpe Quest</title>
    <?php include "inc/head.inc.php" ?>
</head>

<body>
    <?php include "inc/nav.inc.php" ?>
    <?php include "inc/header.inc.php" ?>

    <!-- search bar -->
    <section class="search-section section-gap text-center">
        <div class="input-group rounded mx-auto w-100 w-sm-75 w-md-50" style="max-width:480px; padding-top:20px; padding-bottom:20px;">
            <input type="search" class="form-control rounded" onkeyup="filterRooms()" placeholder="Search for rooms..." aria-label="Search"
                aria-describedby="search-addon" />
            <button type="button" class="btn btn-outline-primary" id="search-addon">Search</button>
        </div>
    </section>

    <main class="page-content section-gap">
        <div class="container">

            <!-- filter -->
            <div class="filter-box">
                <h4 class="mb-3">Find Your Perfect Challenge</h4>

                <div class="mb-3">
                    <h6>Fear Factor</h6>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" name="fear" value="all" id="fearAll" checked>
                        <label class="form-check-label" for="fearAll">All</label>
                    </div>

                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" name="fear" value="very-scary" id="fearVeryScary">
                        <label class="form-check-label" for="fearVeryScary">Very Scary</label>
                    </div>

                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" name="fear" value="scary" id="fearScary">
                        <label class="form-check-label" for="fearScary">Scary</label>
                    </div>

                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" name="fear" value="mildly-scary"
                            id="fearMildlyScary">
                        <label class="form-check-label" for="fearMildlyScary">Mildly Scary</label>
                    </div>

                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" name="fear" value="not-scary" id="fearNotScary">
                        <label class="form-check-label" for="fearNotScary">Not Scary</label>
                    </div>
                </div>
                <div class="mb-3">
                    <h6>Experience Type</h6>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" name="actor" value="all" id="actorAll" checked>
                        <label class="form-check-label" for="actorAll">All</label>
                    </div>

                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" name="actor" value="live-actor" id="actorLive">
                        <label class="form-check-label" for="actorLive">Live Actor</label>
                    </div>

                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" name="actor" value="no-live-actor" id="actorNoLive">
                        <label class="form-check-label" for="actorNoLive">No Live Actor</label>
                    </div>
                </div>

                <div class="mb-3">
                    <h6>Genre</h6>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="checkbox" value="horror" id="genreHorror">
                        <label class="form-check-label" for="genreHorror">Horror</label>
                    </div>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="checkbox" value="thriller" id="genreThriller">
                        <label class="form-check-label" for="genreThriller">Thriller</label>
                    </div>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="checkbox" value="fantasy" id="genreFantasy">
                        <label class="form-check-label" for="genreFantasy">Fantasy</label>
                    </div>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="checkbox" value="adventure" id="genreAdventure">
                        <label class="form-check-label" for="genreAdventure">Adventure</label>
                    </div>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="checkbox" value="murder-mystery" id="genreMurderMystery">
                        <label class="form-check-label" for="genreMurderMystery">Murder Mystery</label>
                    </div>
                </div>
            </div>

            <!-- replaced static room count with dynamic allocation -->
            <section id="Rooms">
                <h5 class="mb-3" style="padding-top: 40px;">Rooms</h5>
                <div class="text-center my-4">
                    <p class="text-muted">Showing <?php echo $roomCount; ?> rooms</p>
                </div>

                <div class="row g-4" id="roomContainer">
                    <div class="col-md-4 room-card" data-fear="mildly-scary">
                        <div class="card" href="/pharaoh_room.php">
                            <img src="/images/P_Curse.jpg"
                                class="card-img-top" alt="Pharaoh's Curse">
                            <div class="card-body">
                                <span class="badge bg-warning text-dark">Mildly Scary</span>

                                <h5 class="card-title mt-2">The Pharaoh's Curse</h5>
                                <p class="text-muted mb-0">Rating: ★4.8</p>
                                <a href="/pharaoh_room.php" class="stretched-link"></a>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-4 room-card" data-fear="very-scary">
                        <div class="card" href="/haunt_room.php">
                            <img src="/images/Haunt.jpg"
                                class="card-img-top" alt="Haunted Mansion">
                            <div class="card-body">
                                <span class="badge bg-danger">Very Scary</span>
                                <h5 class="card-title mt-2">Haunted Mansion</h5>
                                <p class="text-muted mb-0">Rating: ★4.9</p>
                                <a href="/metro_room.php" class="stretched-link"></a>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-4 room-card" data-fear="not-scary">
                        <div class="card">
                            <img src="/images/metro.png"
                                class="card-img-top" alt="Metro">
                            <div class="card-body">
                                <span class="badge bg-secondary">Not Scary</span>
                                <h5 class="card-title mt-2">Metro</h5>
                                <p class="text-muted mb-0">Rating: ★4.6</p>
                                <a href="/metro_room.php" class="stretched-link"></a>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- rooms (no mroe hard coding) -->
                <!-- if filter derives no results -->
                <div class="row g-4" id="roomContainer">

                    <?php if (empty($rooms)): ?>
                        <div class="col-12">
                            <p class="text-center h5">No rooms matching your criteria were found.</p>
                        </div>
                    <?php else: ?>

                        <?php foreach ($rooms as $room):
                            //sanitise data attributes for filter script
                            $dataFear = slugify($room['roomFearLevel']);
                            $dataActor = slugify($room['roomExperienceType']);
                            $dataGenre = slugify($room['roomGenre']);
                        ?>
                            <!-- in built data to make filter easier -->
                            <div class="col-md-4 room-card"
                                data-fear="<?php echo $dataFear; ?>"
                                data-actor="<?php echo $dataActor; ?>"
                                data-genre="<?php echo $dataGenre; ?>"
                                data-title="<?php echo htmlspecialchars($room['roomName']); ?>">

                                <div class="card">
                                    <img src="<?php echo htmlspecialchars($room['imagePath'] ?? '/images/placeholder.png'); ?>"
                                        class="card-img-top" alt="<?php echo htmlspecialchars($room['roomName']); ?>">

                                    <div class="card-body">
                                        <span class="badge <?php echo getBadgeColor($room['roomFearLevel']); ?>">
                                            <?php echo htmlspecialchars($room['roomFearLevel']); ?>
                                        </span>

                                        <h5 class="card-title mt-2"><?php echo htmlspecialchars($room['roomName']); ?></h5>

                                        <!-- to-do: somehow make rating more dynamic -->
                                        <p class="text-muted mb-0">Rating: ★4.8</p>

                                        <a href="room.php?id=<?php echo $room['roomID']; ?>" class="stretched-link"></a>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
            </section>
        </div>
    </main>
    <script src="js/main.js"></script>
    <?php include "inc/footer.inc.php" ?>

</body>

</html>