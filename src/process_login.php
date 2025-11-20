<!DOCTYPE html>
<html lang="en">
    <head>
        <title>Login Results</title>
        <?php
        session_start();
        include "inc/head.inc.php";
        ?>
    </head>
    <body>
        <?php
        require_once "inc/login_functions.php";
        $errorMsg = "";
        $success = true;

        if (empty($_POST["email"]))
        {
            $errorMsg .= "Email is required.<br>";
            $success = false;
        }
        else
        {
            $email = sanitize_input($_POST["email"]);

            if (!filter_var($email, FILTER_VALIDATE_EMAIL))
            {
                $errorMsg .= "Invalid Email format.<br>";
                $success = false;
            }
        }

        if (empty($_POST["pwd"]))
        {
            $errorMsg .= "Password is required.<br>";
            $success = false;
        }
        else
        {
            $pwd = password_hash($_POST["pwd"], PASSWORD_DEFAULT);
        }

        if ($success)
        {
            authenticateUser();
        }

        if ($success)
        {
            // Store user information in session
            $_SESSION['logged_in'] = true;
            $_SESSION['user_id'] = $user_id ?? null;
            $_SESSION['user_fname'] = $fname;
            $_SESSION['user_lname'] = $lname;
            $_SESSION['user_email'] = $email;
            
            echo "<div class=container justify-content-center mb-3>";
            echo "<h2>Login sucessful!</h2>";
            echo "<h4>Welcome back, " . $fname . " " . $lname . ".</h4><br>";
            echo "<a href='index.php' class='btn btn-success'>Return to Home</a>";
            echo "</div>";
        }
        else
        {
            echo "<div class='container justify-content-center mb-3'>";
            echo "<h2>Oops!</h2>";
            echo "<h4>The following errors were detected:</h4>";
            echo "<p>" . $errorMsg . "</p>";
            echo "<a href='login.php' class='btn btn-warning'>Return to Login</a>";
            echo "</div>";
        }

        include "inc/footer.inc.php"
        ?>
    </body>
</html>