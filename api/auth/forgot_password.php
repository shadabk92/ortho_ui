<?php
require "../config.php";
require "../mail/mailer.php";

$data = json_decode(file_get_contents("php://input"), true);
$email = trim($data['email']);

$stmt = $pdo->prepare("SELECT id FROM users WHERE email=? AND is_active=1");
$stmt->execute([$email]);
$user = $stmt->fetch();

if (!$user) {
    echo json_encode(["success"=>true]); // security: don't reveal
    exit;
}

$token = bin2hex(random_bytes(32));
$expiry = date("Y-m-d H:i:s", strtotime("+30 minutes"));

$stmt = $pdo->prepare("
    UPDATE users SET reset_token=?, reset_token_expiry=? WHERE id=?
");
$stmt->execute([$token, $expiry, $user['id']]);

$resetLink = "https://yourdomain.com/reset-password.html?token=$token";

sendResetEmail($email, $resetLink);

echo json_encode(["success"=>true]);
