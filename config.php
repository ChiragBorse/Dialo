<?php
// Database connection used by signup.php, login.php and chat.php.
// Default XAMPP MySQL settings: user "root", empty password.
// If you set a MySQL password in XAMPP, put it below.

$DB_HOST = "localhost";
$DB_USER = "root";
$DB_PASS = "";
$DB_NAME = "opal_mind";

$conn = new mysqli($DB_HOST, $DB_USER, $DB_PASS, $DB_NAME);

if ($conn->connect_error) {
    http_response_code(500);
    die(json_encode(["ok" => false, "message" => "Database connection failed. Is MySQL running in XAMPP, and did you import opal_mind.sql?"]));
}

session_start();
