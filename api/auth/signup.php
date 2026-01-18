<?php
// FILE: api/auth/signup.php

// Debugging (Remove in production)
ini_set('display_errors', 1);
error_reporting(E_ALL);

// Check if config exists
if (!file_exists("../config.php")) {
    http_response_code(500);
    echo json_encode(["success" => false, "message" => "Server Config Missing"]);
    exit;
}

require "../config.php"; // Includes Headers + DB Connection + Session Start

$data = json_decode(file_get_contents("php://input"), true);

// Basic Validation
if (!isset($data['email']) || !isset($data['password']) || !isset($data['role'])) {
    http_response_code(400);
    echo json_encode(["success"=>false, "message"=>"Missing required fields"]);
    exit;
}

$role = $data['role']; // doctor | healthcare_professional
$firstName = $data['first_name'];
$lastName  = $data['last_name'];
$email     = $data['email'];
$password  = password_hash($data['password'], PASSWORD_BCRYPT);

try {
    // 1. Check if Email Already Exists
    $stmt = $pdo->prepare("SELECT id FROM users WHERE email = ?");
    $stmt->execute([$email]);
    if ($stmt->fetch()) {
        http_response_code(409); // 409 Conflict
        echo json_encode(["success"=>false, "message"=>"Email already registered"]);
        exit;
    }

    // 2. Get User Type ID
    // Note: Ensure your database table 'usertypes' actually has 'healthcare_professional' as a key
    $stmt = $pdo->prepare("SELECT id FROM usertypes WHERE type_key = ?");
    $stmt->execute([$role]);
    $usertype = $stmt->fetch();

    if (!$usertype) {
        http_response_code(400);
        echo json_encode(["success"=>false,"message"=>"Invalid role selected"]);
        exit;
    }

    // 3. Insert into Users
    $stmt = $pdo->prepare("INSERT INTO users (usertype_id, first_name, last_name, email, password, is_active) VALUES (?,?,?,?,?,1)");
    $stmt->execute([$usertype['id'], $firstName, $lastName, $email, $password]);
    $userId = $pdo->lastInsertId();

    // 4. Insert Role Specific Data
    if ($role === 'doctor') {
        $stmt = $pdo->prepare("INSERT INTO doctors (user_id, license_number, specialty) VALUES (?,?,?)");
        $stmt->execute([$userId, $data['license_number'], $data['specialty']]);
    }
    elseif ($role === 'healthcare_professional') {
        $stmt = $pdo->prepare("INSERT INTO healthcare_professionals (user_id, profession, organization) VALUES (?,?,?)");
        $stmt->execute([$userId, $data['profession'], $data['organization']]);
    }

    echo json_encode(["success" => true, "message" => "Account created successfully"]);

} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(["success" => false, "message" => "DB Error: " . $e->getMessage()]);
}
?>