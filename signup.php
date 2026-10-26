<?php
// Receives sign-up form data as JSON, stores it in MySQL, starts a session.
header("Content-Type: application/json");
require __DIR__ . "/config.php";

$data = json_decode(file_get_contents("php://input"), true);

$name     = trim($data["name"] ?? "");
$email    = trim($data["email"] ?? "");
$password = $data["password"] ?? "";

if ($name === "" || $email === "" || $password === "") {
    echo json_encode(["ok" => false, "message" => "Please fill in every field."]);
    exit;
}
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    echo json_encode(["ok" => false, "message" => "That email address doesn't look right."]);
    exit;
}
if (strlen($password) < 6) {
    echo json_encode(["ok" => false, "message" => "Password must be at least 6 characters."]);
    exit;
}

// Check if the email is already registered
$stmt = $conn->prepare("SELECT id FROM users WHERE email = ?");
$stmt->bind_param("s", $email);
$stmt->execute();
$stmt->store_result();
if ($stmt->num_rows > 0) {
    echo json_encode(["ok" => false, "message" => "An account with that email already exists. Try logging in instead."]);
    exit;
}
$stmt->close();

// Insert the new user with a securely hashed password
$hash = password_hash($password, PASSWORD_DEFAULT);
$stmt = $conn->prepare("INSERT INTO users (full_name, email, password_hash) VALUES (?, ?, ?)");
$stmt->bind_param("sss", $name, $email, $hash);

if ($stmt->execute()) {
    $_SESSION["user_id"]   = $stmt->insert_id;
    $_SESSION["user_name"] = $name;
    $_SESSION["user_email"] = $email;
    echo json_encode(["ok" => true, "message" => "Account created! Welcome, $name.", "redirect" => "chat.html"]);
} else {
    echo json_encode(["ok" => false, "message" => "Something went wrong while saving your account."]);
}
$stmt->close();
$conn->close();
