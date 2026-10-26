<?php
// Tells the chat page who is currently logged in (reads the PHP session).
header("Content-Type: application/json");
require __DIR__ . "/config.php";

if (!empty($_SESSION["user_id"])) {
    echo json_encode([
        "ok" => true,
        "name" => $_SESSION["user_name"],
        "email" => $_SESSION["user_email"]
    ]);
} else {
    echo json_encode(["ok" => false]);
}
