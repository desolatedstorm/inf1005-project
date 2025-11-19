<!doctype html>
<html lang="en">
<link rel="icon" type="image/x-icon" href="../images/home.ico">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <meta name="description" content="Member Registration" />
    <title>Member Registration</title>
    <?php
    include "inc/head.inc.php";
    ?>
    <link href="css/sign-in.css" rel="stylesheet" />
    <style>
        html,
        body {
            height: 100%;
        }

        body {
            display: flex;
            flex-direction: column;
            background-color: #f5f5f5;
        }

        main {
            flex: 1;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 2rem 0;
        }

        .form-signin {
            max-width: 380px;
            width: 100%;
        }
    </style>
</head>

<body>
    <?php
    include "inc/nav.inc.php";
    ?>
    <main>
        <form class="form-signin" action="process_register.php" method="post">
            <img class="mb-4" src="images/home.png" alt="Logo" width="72" height="57" />
            <h1 class="h3 mb-3 fw-normal">Member Registration</h1>
            <div class="form-floating mb-3">
                <input type="text" id="fname" name="fname" class="form-control" id="floatingFname"
                    placeholder="First name" />
                <label for="floatingFname">First Name</label>
            </div>

            <div class="form-floating mb-3">
                <input maxlength="45" type="text" id="lname" name="lname" class="form-control" id="floatingLname"
                    placeholder="Last name" required />
                <label for="floatingLname">Last Name</label>
            </div>

            <div class="form-floating mb-3">
                <input type="email" id="email" name="email" class="form-control" id="floatingEmail" placeholder="Email"
                    required />
                <label for="floatingEmail">Email address</label>
            </div>

            <div class="form-floating mb-3">
                <input type="password" id="pwd" name="pwd" class="form-control" id="floatingPwd" placeholder="Password"
                    required />
                <label for="floatingPwd">Password</label>
            </div>

            <div class="form-floating mb-3">
                <input type="password" id="pwd_confirm" name="pwd_confirm" class="form-control" id="floatingPwdConfirm"
                    placeholder="Confirm password" required />
                <label for="floatingPwdConfirm">Confirm Password</label>
            </div>

            <div class="form-check text-start my-3">
                <input type="checkbox" name="agree" class="form-check-input" id="checkTerms" required />
                <label class="form-check-label" for="checkTerms">
                    I agree to terms and conditions.
                </label>
            </div>

            <button type="submit" class="btn btn-primary w-100 py-2">Register</button>
        </form>
    </main>
    <div class="text-center p-3">
        <p class="mb-0">
            Already a member? Please go to the
            <a href="login.php">Sign In Page</a>.
        </p>
    </div>
    <?php
    include "inc/footer.inc.php";
    ?>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>