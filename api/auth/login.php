<?php
session_start();

// Error reporting for debugging (Disable in production)
ini_set('display_errors', 1);
error_reporting(E_ALL);

header('Content-Type: application/json');

// FIXED PATH: Go up one level from 'auth' to 'api'
if (!file_exists("../config.php")) {
    http_response_code(500);
    echo json_encode(["success" => false, "message" => "Server Config Missing"]);
    exit;
}

require "../config.php"; 

// Get JSON input
$data = json_decode(file_get_contents("php://input"), true);

// Basic validation
if (!isset($data['method']) || !isset($data['identifier']) || !isset($data['password'])) {
    http_response_code(400);
    echo json_encode(["success"=>false, "message"=>"Missing credentials"]);
    exit;
}

$method = $data['method']; 
$identifier = trim($data['identifier']);
$password = $data['password'];

try {
    if ($method === "email") {
        $stmt = $pdo->prepare("SELECT * FROM users WHERE email=? AND is_active=1");
    } else {
        $stmt = $pdo->prepare("SELECT * FROM users WHERE mobile=? AND is_active=1");
    }

    $stmt->execute([$identifier]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    // Verify user and password
    if (!$user || !password_verify($password, $user['password'])) {
        http_response_code(401);
        echo json_encode(["success"=>false,"message"=>"Invalid login credentials"]);
        exit;
    }

    /* ✅ SET SESSION */
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

} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(["success" => false, "message" => "DB Error: " . $e->getMessage()]);
}
?>