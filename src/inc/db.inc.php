<?php

function getDBEnvVar()
{
    // Create DB connection
    $db_host = getenv('DB_HOST') ?: "db";
    $db_user = getenv('DB_USER');
    $db_pass = getenv('DB_PASS');
    $db_name = getenv('DB_NAME');
    return array($db_host, $db_user, $db_pass, $db_name);
}

function getDBconnection()
{
    list($db_host, $db_user, $db_pass, $db_name) = getDBEnvVar();
    $conn = new mysqli($db_host, $db_user, $db_pass, $db_name);

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }
    return $conn;
}

?>