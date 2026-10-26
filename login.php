<?php
// Receives login form data as JSON, checks it against MySQL, starts a session.
header("Content-Type: application/json");
require __DIR__ . "/config.php";

$data = json_decode(file_get_contents("php://input"), true);

$email    = trim($data["email"] ?? "");
$password = $data["password"] ?? "";

if ($email === "" || $password === "") {
    echo json_encode(["ok" => false, "message" => "Please enter your email and password."]);
    exit;
}

$stmt = $conn->prepare("SELECT id, full_name, password_hash FROM users WHERE email = ?");
$stmt->bind_param("s", $email);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();

if (!$user || !password_verify($password, $user["password_hash"])) {
    echo json_encode(["ok" => false, "message" => "Email or password is incorrect."]);
    exit;
}

$_SESSION["user_id"]    = $user["id"];
$_SESSION["user_name"]  = $user["full_name"];
$_SESSION["user_email"] = $email;

echo json_encode(["ok" => true, "message" => "Welcome back, {$user['full_name']}!", "redirect" => "chat.html"]);

$stmt->close();
$conn->close();
