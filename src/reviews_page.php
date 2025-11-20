<!doctype html>
<html lang="en">
<link rel="icon" type="image/x-icon" href="../images/home.ico">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <meta name="description" content="Member Login" />

    <title>Let Us Know Your Thoughts!</title>
    <?php
    include "inc/head.inc.php";
    ?>
    <link href="css/style.css" rel="stylesheet" />
</head>

<html>


<body>
    <?php
    include "inc/nav.inc.php";
    ?>
    <main>

        <head>
            <meta charset="utf-8">
            <meta name="viewport" content="width=device-width, initial-scale=1">
            <link href="css/bootstrap.css" rel="stylesheet" type="text/css" />
            <link href="css/rating.css" rel="stylesheet" type="text/css" />
            <link rel="stylesheet" href="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.4/css/bootstrap.min.css">
            <link href='http://fonts.googleapis.com/css?family=Roboto' rel='stylesheet' type='text/css'>
            <title>Comment Box</title>
        </head>

        <body>
            <!------------container------->

            <div class="container">

                <h1>Ratings Page:</h1>
                <!-------Wrap------------>
                <div id="wrap">
                    <div id="main">
                        <div class="row">
                            <div class="col-md-5">
                                <h3 class="heading">Comments and Responses</h3>
                            </div>
                            <div class="col-md-7">
                                <div id="upper_blank"></div>
                            </div>
                        </div>
                    </div>

                    <p>Your email address will not be published. Required fields are marked *</p>

                    <input type="radio" id="star1" name="rating" value="1" /><input type="radio" id="star2"
                        name="rating" value="2" /><input type="radio" id="star3" name="rating" value="3" /><input
                        type="radio" id="star4" name="rating" value="4" /><input type="radio" id="star5" name="rating"
                        value="5" />

                    <label for="star1" aria-label="Banana">1 star</label><label for="star2">2 stars</label><label
                        for="star3">3
                        stars</label><label for="star4">4 stars</label><label for="star5">5 stars</label>
                </div>

                <div id='form'>
                    <div class="row">
                        <div class="col-md-12">

                            <form action="" method="POST" id="commentform">

                                <div id="comment-name" class="form-row">
                                    <input type="text" placeholder="Name (required)" name="dname" id="name">
                                </div>
                                <div id="comment-email" class="form-row">
                                    <input type="text" placeholder="Mail (will not be published) (required)"
                                        name="demail" id="email">
                                </div>
                                <div id="comment-message" class="form-row">
                                    <textarea name="comment" placeholder="Message" id="comment"></textarea>
                                </div>
                                <a href="#"><input type="submit" name="dsubmit" id="commentSubmit"
                                        value="Submit Comment"></a>
                            </form>

                        </div>
                    </div>
                </div>
            </div>
            </div>
        </body>

</html>

</div>
<?php
include "inc/footer.inc.php";
?>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>