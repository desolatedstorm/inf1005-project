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
    <div class="input-group rounded mx-auto w-100 w-sm-75 w-md-50" style="max-width:480px;">
        <input type="search" class="form-control rounded" placeholder="Search for rooms..." aria-label="Search"
            aria-describedby="search-addon" />
        <button type="button" class="btn btn-outline-primary" id="search-addon">Search</button>
    </div>
    <?php
    include "inc/footer.inc.php"
        ?>

    <!-- Here's the flag CTF{HTML_1s_fun!} -->
</body>

</html>