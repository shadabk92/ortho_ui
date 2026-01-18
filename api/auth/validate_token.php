<?php
require "../config.php";

$token = $_GET['token'] ?? '';

$stmt = $pdo->prepare("
    SELECT id FROM users 
    WHERE reset_token=? AND reset_token_expiry > NOW()
");
$stmt->execute([$token]);

if ($stmt->rowCount() === 0) {
    echo json_encode(["valid"=>false]);
    exit;
}

$_SESSION['reset_token'] = $token;
echo json_encode(["valid"=>true]);
