<!DOCTYPE html>
<html lang="en">
<link rel="icon" type="image/x-icon" href="/images/home.ico">

<head>
    <title>Escape Room</title>
    <?php
    include "inc/head.inc.php"
        ?>
</head>

<body>
    <?php
    include "inc/nav.inc.php"
        ?>
    <?php
    include "inc/header.inc.php"
        ?>
    <section class="search-section section-gap text-center">
        <div class="input-group rounded mx-auto w-100 w-sm-75 w-md-50" style="max-width:480px;">
            <input type="search" class="form-control rounded" placeholder="Search for rooms..." aria-label="Search"
                aria-describedby="search-addon" />
            <button type="button" class="btn btn-outline-primary" id="search-addon">Search</button>
        </div>
    </section>

    <main class="page-content section-gap">
        <div class="container">
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
                        <input class="form-check-input" type="radio" name="actor" value="live" id="actorLive">
                        <label class="form-check-label" for="actorLive">Live Actor</label>
                    </div>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" name="actor" value="no-live" id="actorNoLive">
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
            <h5 class="mb-3">Rooms</h5>
            <div class="text-center my-4">
                <p class="text-muted">Showing 3 rooms</p>
            </div>

            <div class="row g-4" id="roomContainer">
                <div class="col-md-4 room-card" data-fear="mildly-scary">
                    <div class="card">
                        <img src=""
                            class="card-img-top" alt="Pharaoh's Curse">
                        <div class="card-body">
                            <span class="badge bg-warning text-dark">Mildly Scary</span>
                            <h5 class="card-title mt-2">The Pharaoh's Curse</h5>
                            <p class="text-muted mb-0">Rating: ★4.8</p>
                        </div>
                    </div>
                </div>

                <div class="col-md-4 room-card" data-fear="very-scary">
                    <div class="card">
                        <img src=""
                            class="card-img-top" alt="Haunted Mansion">
                        <div class="card-body">
                            <span class="badge bg-danger">Very Scary</span>
                            <h5 class="card-title mt-2">Haunted Mansion</h5>
                            <p class="text-muted mb-0">Rating: ★4.9</p>
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
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
    <script src="js/main.js"></script>

    <?php
    include "inc/footer.inc.php"
        ?>

    <!-- Here's the flag CTF{HTML_1s_fun!} -->
</body>

</html>