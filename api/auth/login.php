<?php
require "../config.php";

$data = json_decode(file_get_contents("php://input"), true);

$method = $data['method']; // email | phone
$identifier = trim($data['identifier']);
$password = $data['password'];

if (!$identifier || !$password) {
    http_response_code(400);
    echo json_encode(["success"=>false,"message"=>"Missing credentials"]);
    exit;
}

if ($method === "email") {
    $stmt = $pdo->prepare("SELECT * FROM users WHERE email=? AND is_active=1");
} else {
    $stmt = $pdo->prepare("SELECT * FROM users WHERE mobile=? AND is_active=1");
}

$stmt->execute([$identifier]);
$user = $stmt->fetch();

if (!$user || !password_verify($password, $user['password'])) {
    http_response_code(401);
    echo json_encode(["success"=>false,"message"=>"Invalid login credentials"]);
    exit;
}

/* ✅ SESSION */
$_SESSION['user_id'] = $user['id'];
$_SESSION['usertype_id'] = $user['usertype_id'];
$_SESSION['name'] = $user['first_name'];

echo json_encode([
    "success" => true,
    "message" => "Login successful",
    "user" => [
        "id" => $user['id'],
        "name" => $user['first_name']
    ]
]);
