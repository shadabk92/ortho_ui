<?php
require "../config.php";

if (!isset($_SESSION['reset_token'])) {
    http_response_code(403);
    exit;
}

$data = json_decode(file_get_contents("php://input"), true);
$newPassword = password_hash($data['password'], PASSWORD_BCRYPT);

$stmt = $pdo->prepare("
    UPDATE users 
    SET password=?, reset_token=NULL, reset_token_expiry=NULL
    WHERE reset_token=?
");

$stmt->execute([$newPassword, $_SESSION['reset_token']]);

unset($_SESSION['reset_token']);

echo json_encode(["success"=>true]);
