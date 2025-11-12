<!doctype html>
<html lang="en">
<link rel="icon" type="image/x-icon" href="../images/home.ico">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <meta name="description" content="Member Login" />
    <title>Member Login</title>
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
            max-width: 330px;
            width: 100%;
        }
    </style>
</head>

<body>
    <?php
    include "inc/nav.inc.php";
    ?>
    <main>
        <form class="form-signin">
            <img class="mb-4" src="images/home.png" alt="Logo" width="72" height="57" />
            <h1 class="h3 mb-3 fw-normal">Please sign in</h1>
            <div class="form-floating">
                <input type="email" class="form-control" id="floatingInput" placeholder="name@example.com" />
                <label for="floatingInput">Email address</label>
            </div>
            <div class="form-floating">
                <input type="password" class="form-control" id="floatingPassword" placeholder="Password" />
                <label for="floatingPassword">Password</label>
            </div>
            <div class="form-check text-start my-3">
                <input class="form-check-input" type="checkbox" value="remember-me" id="checkDefault" />
                <label class="form-check-label" for="checkDefault">
                    Remember me
                </label>
            </div>
            <button class="btn btn-primary w-100 py-2" type="submit">
                Sign in
            </button>
        </form>
    </main>
    <div class="text-center p-3">
        <p class="mb-0">
            Don't have an account yet? Please go to the
            <a href="register.php">Member Registration page</a>.
        </p>
    </div>
    <?php
    include "inc/footer.inc.php";
    ?>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>