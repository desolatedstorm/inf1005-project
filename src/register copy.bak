<!DOCTYPE html>
<html lang="en">
    <head>
        <title>Register</title>
        <?php
            include "inc/head.inc.php";
        ?>
    </head>
    <body>
        <?php
        include "inc/nav.inc.php"; 
        ?>

        <main class="container">
            <h1>Member Registration</h1>
            <p>
                For existing members, please go to the 
                <a href="index.php">Sign In Page</a>.
            </p>
            <form action="process_register.php" method="post">
                <div class="mb-3">
                    <label for="fname" class="form-label">First Name:</label>
                    <input type="text" id="fname" name="fname" class="form-control" placeholder="Enter first name">
                </div>

                <div class="mb-3">
                    <label for="lname" class="form-label">Last Name:</label>
                    <input maxlength="45" type="text" id="lname" name="lname" class="form-control" placeholder="Enter last name" required>
                </div>

                <div class="mb-3">
                    <label for="email" class="form-label">Email:</label>
                    <input type="email" id="email" name="email" class="form-control" placeholder="Enter email" required>
                </div>

                <div class="mb-3">
                    <label for="pwd" class="form-label">Password:</label>
                    <input type="password" id="pwd" name="pwd" class="form-control" placeholder="Enter password" required>
                </div>
                
                <div class="mb-3">
                    <label for="pwd_confirm" class="form-label">Confirm Password:</label>
                    <input type="password" id="pwd_confirm" name="pwd_confirm" class="form-control" placeholder="Confirm password" required>
                </div>
                
                <div class="mb-3 form-check">
                    <label class="form-check-label">
                        <input type="checkbox" name="agree" class="form-check-input" required>
                        Agree to terms and conditions.
                    </label>
                </div>

                <div class="mb-3">
                    <button type="submit" class="btn btn-primary">Submit</button>
                </div>
            </form>
        </main>
        <?php
        include "inc/footer.inc.php";
        ?>
    </body>
</html>