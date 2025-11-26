<?php
	require_once "inc/secure_session_start.php";

	if (isset($_SESSION['user_id'])) {
		header("Location: manage_account.php");
		exit;
	}
?>

<!doctype html>
<html lang="en">
<link rel="icon" type="image/x-icon" href="images/home.ico">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <meta name="description" content="Member Registration" />
    <title>Member Registration</title>
    <?php include "inc/head.inc.php"; ?>
    <link href="css/sign-in.css" rel="stylesheet" />
</head>

<body>
    <?php include "inc/nav.inc.php"; ?>
    <main>
        <form class="form-signin" action="process_register.php" method="post">
			<img class="mb-4" src="../images/home.png" alt="Logo" width="72" height="57" />
			<h1 class="h3 mb-3 fw-normal">Member Registration</h1>

			<div class="form-floating mb-3">
				<input type="text" name="username" class="form-control" id="floatingUsername" placeholder="Username" required />
				<label for="floatingUsername">Username</label>
			</div>

			<div class="form-floating mb-3">
				<input type="email" name="email" class="form-control" id="floatingEmail" placeholder="Email" required />
				<label for="floatingEmail">Email address</label>
			</div>

			<div class="form-floating mb-3">
				<input type="password" name="password" class="form-control" id="floatingPassword" placeholder="Password" required />
				<label for="floatingPassword">Password</label>
			</div>

			<div class="form-floating mb-3">
				<input type="password" name="password_confirm" class="form-control" id="floatingPasswordConfirm" placeholder="Confirm Password" required />
				<label for="floatingPasswordConfirm">Confirm Password</label>
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
    <?php include "inc/footer.inc.php"; ?>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>