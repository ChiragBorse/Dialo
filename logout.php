<?php
// Ends the session and sends the user back to the login page.
require __DIR__ . "/config.php";
$_SESSION = [];
session_destroy();
header("Location: login-signup.html");
exit;
