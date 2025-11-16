<!DOCTYPE html>
<html lang="en">
    <head>
        <title>World of Pets</title>
        <?php
            include "inc/head.inc.php"
        ?>
    </head>
    <body>
        <main>
            <?php
            require_once "inc/login_functions.php";
            include "inc/nav.inc.php";
            $email = "";
            $errorMsg = "";
            $success = true;

            // Email
            if (empty($_POST["email"]))
            {
                $errorMsg .= "Email is required.<br>";
                $success = false;
            }
            else
            {
                $email = sanitize_input($_POST["email"]);
            
                // Additional check to make sure email is well formated
                if (!filter_var($email, FILTER_VALIDATE_EMAIL))
                {
                    $errorMsg .= "Invalid email format.<br>";
                    $success = false;
                }
            }
        
            // first name
            if (!empty($_POST["fname"]))
            {
                $fname = sanitize_input($_POST["fname"]);
            }
        
            // last name
            if (empty($_POST["lname"]))
            {
                $errorMsg .= "Last name required.<br>";
                $success = false;
            }
            else
            {
                $lname = sanitize_input($_POST["lname"]);
            
                // only alphabets
                if (!preg_match("/^[a-zA-Z\s'-]*$/", $lname))
                {
                    $errorMsg .= "Only letters, spaces, hypens, and apostrophes allowed.<br>";
                    $success = false;
                }
                // length
                $maxLength = 45;
                if (strlen($lname) > $maxLength)
                {
                    $errorMsg .= "Maximum 45 characters.<br>";
                    $success = false;
                }
            }
        
            // password
            if (empty($_POST["pwd"]))
            {
                $errorMsg .= "Password required.<br>";
                $success = false;
            }
            else {
                $pwd = password_hash($_POST["pwd"],PASSWORD_DEFAULT);
            }

            if (!password_verify($_POST["pwd_confirm"], $pwd))
            {
                $errorMsg .= "Passwords do not match.<br>";
                $success = false;
            }

            if ($success)
            {
                saveMemeberToDB();
            }

            if ($success)
            {
                echo "<div class='container justify-content-center mb-3'>";
                echo "<h4>Registration Successful!</h4>";
                echo "<p>Thank you for signing up," . $email . "</p>";
                echo "<a href='index.php' class='btn btn-success'>Log-in</a>";
                echo "</div>";
                
            }
            else
            {
                echo "<div class='container justify-content-center mb-3'>";
                echo "<h2>Oops!</h2>";
                echo "<h4>The following input errors were detected:</h4>";
                echo "<p>" . $errorMsg . "</p><br>";
                echo "<a href='index.php' class='btn btn-danger'>Return to Sign Up</a><br>";
                echo "</div>";
            }
            
            ?>
        </main>
        <?php
        include "inc/footer.inc.php";
        ?>
    </body>
</html>