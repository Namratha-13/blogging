<?php
define("DB_SERVER", "localhost");
define("DB_USERNAME", "root");
define("DB_PASSWORD", "");
define("DB_NAME", "blogms"); // Changed from 'blogs' to 'blogms'

$link = mysqli_connect(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME);
if ($link === false) {
    die("Error: Could not connect to the Database. Exception: " . mysqli_connect_error());
}
?>