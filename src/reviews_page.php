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

<body>
    <?php
    include "inc/nav.inc.php";
    ?>
    <main>

        <section style="background-color: #d94125;">
  <div class="container my-5 py-5 text-body">
    <div class="row d-flex justify-content-center">
      <div class="col-md-10 col-lg-8 col-xl-6">
        <div class="card">
          <div class="card-body p-4">
            <div class="d-flex flex-start w-100">
              <img class="rounded-circle shadow-1-strong me-3"
                src="https://mdbcdn.b-cdn.net/img/Photos/Avatars/img%20(21).webp" alt="avatar" width="65"
                height="65" />
              <div class="w-100">
                <h5>Add a comment</h5>
                <ul data-mdb-rating-init class="rating mb-3" data-mdb-toggle="rating">
                  <li>
                    <i class="far fa-star fa-sm text-danger" title="Bad"></i>
                  </li>
                  <li>
                    <i class="far fa-star fa-sm text-danger" title="Poor"></i>
                  </li>
                  <li>
                    <i class="far fa-star fa-sm text-danger" title="OK"></i>
                  </li>
                  <li>
                    <i class="far fa-star fa-sm text-danger" title="Good"></i>
                  </li>
                  <li>
                    <i class="far fa-star fa-sm text-danger" title="Excellent"></i>
                  </li>
                </ul>
                <div data-mdb-input-init class="form-outline">
                  <textarea class="form-control" id="textAreaExample" rows="4"></textarea>
                  <label class="form-label" for="textAreaExample">What is your view?</label>
                </div>
                <div class="d-flex justify-content-between mt-3">
                  <button  type="button" data-mdb-button-init data-mdb-ripple-init class="btn btn-success">Danger</button>
                  <button  type="button" data-mdb-button-init data-mdb-ripple-init class="btn btn-danger">
                    Send <i class="fas fa-long-arrow-alt-right ms-1"></i>
                  </button>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>


        </div>
        <?php
        include "inc/footer.inc.php";
        ?>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>