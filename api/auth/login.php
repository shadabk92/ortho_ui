<?php
// FILE: api/auth/login.php
require "../config.php"; 

$input = file_get_contents("php://input");
$data = json_decode($input, true);

// Debug: Check if JSON is valid
if ($data === null && !empty($input)) {
    http_response_code(400);
    echo json_encode(["success" => false, "message" => "Invalid JSON format received", "debug_error" => $input]);
    exit;
}

if (!isset($data['method']) || !isset($data['identifier']) || !isset($data['password'])) {
    http_response_code(400);
    echo json_encode(["success"=>false, "message"=>"Missing required login fields"]);
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
    $user = $stmt->fetch();

    // Verify Password
    if (!$user || !password_verify($password, $user['password'])) {
        http_response_code(401);
        echo json_encode([
            "success"=>false,
            "message"=>"Invalid login credentials",
            // DEBUG: This tells you exactly why it failed in Console
            "debug_error" => "User found: " . ($user ? "Yes" : "No") . ". Password Match: " . ($user && password_verify($password, $user['password']) ? "Yes" : "No")
        ]);
        exit;
    }

    // Set Session
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
    echo json_encode(["success" => false, "message" => "Database Query Failed", "debug_error" => $e->getMessage()]);
}
?>