<?php
/*
 * Helper function that checks input for malicious or unwanted content.
 */
function sanitize_input($data)
{
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}

/* 
 * Function to create DB connection using credentials in env var
 */
function getDBEnvVar()
{
    // Create DB connection
    $db_host = getenv('DB_HOST') ?: "db";
    $db_user = getenv('DB_USER');
    $db_pass = getenv('DB_PASS');
    $db_name = getenv('DB_NAME');
    return array($db_host, $db_user, $db_pass, $db_name);
}

// Helper function to write member data to database.
function saveMemeberToDB() 
{
    try 
    {
        global $fname, $lname, $email, $pwd, $errorMsg, $success;

        list($db_host, $db_user, $db_pass, $db_name) = getDBEnvVar();

        if (!$db_user || !$db_pass || !$db_name)
        {
            $errorMsg = "DB Environment Variables not set.";
            $success = false;
        }
        else
        {
            mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
            $conn = new mysqli($db_host, $db_user, $db_pass, $db_name);

            // Check conn
            if ($conn->connect_error)
	    {		
                $errorMsg = "Connection Failed: " . $conn->connect_error;
                $success = false;
            }
            else
            {
                // Prepare statement
                $stmt = $conn->prepare("INSERT INTO members(fname, lname, email, password) VALUES (?,?,?,?)");

                // Bind and Execute statement
                $stmt->bind_param("ssss", $fname, $lname, $email, $pwd);
	            
	    	    if (!$stmt->execute())
	    	    {
	    		    $errorMsg = "Execute failed: (" . $stmt->errno . ")" . $stmt->error;
	    		    $success = false;
	    	    }
	    	    else
	    	    {
	    		    $stmt->close();
	    	    }
            }
            $conn->close();
        }
    }
    catch (Exception $e)
    {
		echo "Exception: " . $e->getMessage();
        $errorMsg = "Exception: " . $e->getMessage();
        $success = false;
	}
}

?>

<?php
function authenticateUser()
{
    global $fname, $lname, $email, $pwd, $errorMsg, $success;
    // Create database connection.

    list($db_host, $db_user, $db_pass, $db_name) = getDBEnvVar();

    if (!$db_user || !$db_pass || !$db_name)
    {
        $errorMsg = "DB Environment Variables not set.";
        $success = false;
    }
    else
    {
        mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
        $conn = new mysqli($db_host, $db_user, $db_pass, $db_name);

        // Check connection
        if ($conn->connect_error)
        {
            $errorMsg = "Connection failed: " . $conn->connect_error;
            $success = false;
        }
        else
        {
            // Prepare the statement:
            $stmt = $conn->prepare("SELECT * FROM members WHERE email=?");
            // Bind & execute the query statement:
            $stmt->bind_param("s", $email);
            $stmt->execute();
            $result = $stmt->get_result();
            if ($result->num_rows > 0)
            {
                // Note that email field is unique, so should only have one row.
                $row = $result->fetch_assoc();
                $fname = $row["fname"];
                $lname = $row["lname"];
                $pwd = $row["password"];
                // Check if the password matches:
                if (!password_verify($_POST["pwd"], $pwd))
                {
                    // Don’t tell hackers which one was wrong, keep them guessing...
                    $errorMsg = "Email not found or password doesn't match...";
                    $success = false;
                }
            }
            else
            {
                $errorMsg = "Email not found or password doesn't match...";
                $success = false;
            }
            $stmt->close();
        }
        $conn->close();
    }
}

?>
