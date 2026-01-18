<?php
// FILE: api/auth/signup.php
require "../config.php"; 

$data = json_decode(file_get_contents("php://input"), true);

// Validate Input
if (!isset($data['email']) || !isset($data['password']) || !isset($data['role'])) {
    http_response_code(400);
    echo json_encode(["success"=>false, "message"=>"Missing required fields"]);
    exit;
}

$role = $data['role']; // 'doctor' or 'healthcare_professional'
$firstName = $data['first_name'];
$lastName  = $data['last_name'];
$email     = $data['email'];
$password  = password_hash($data['password'], PASSWORD_BCRYPT); // Secure Hash

try {
    // 1. Check if Email Exists
    $stmt = $pdo->prepare("SELECT id FROM users WHERE email = ?");
    $stmt->execute([$email]);
    if ($stmt->fetch()) {
        http_response_code(409);
        echo json_encode(["success"=>false, "message"=>"Email already registered"]);
        exit;
    }

    // 2. Get User Type ID
    $stmt = $pdo->prepare("SELECT id FROM usertypes WHERE type_key = ?");
    $stmt->execute([$role]);
    $usertype = $stmt->fetch();

    if (!$usertype) {
        http_response_code(400);
        echo json_encode(["success"=>false,"message"=>"Invalid role selected", "debug_error" => "Role '$role' not found in usertypes table"]);
        exit;
    }

    // 3. Create User
    $pdo->beginTransaction(); // Start Transaction

    $stmt = $pdo->prepare("INSERT INTO users (usertype_id, first_name, last_name, email, password, is_active) VALUES (?,?,?,?,?,1)");
    $stmt->execute([$usertype['id'], $firstName, $lastName, $email, $password]);
    $userId = $pdo->lastInsertId();

    // 4. Create Role Profile
    if ($role === 'doctor') {
        $stmt = $pdo->prepare("INSERT INTO doctors (user_id, license_number, specialty) VALUES (?,?,?)");
        $stmt->execute([$userId, $data['license_number'] ?? '', $data['specialty'] ?? '']);
    } 
    elseif ($role === 'healthcare_professional') {
        $stmt = $pdo->prepare("INSERT INTO healthcare_professionals (user_id, profession, organization) VALUES (?,?,?)");
        $stmt->execute([$userId, $data['profession'] ?? '', $data['organization'] ?? '']);
    }

    $pdo->commit(); // Save changes

    echo json_encode(["success" => true, "message" => "Account created successfully"]);

} catch (PDOException $e) {
    $pdo->rollBack(); // Undo if error
    http_response_code(500);
    echo json_encode(["success" => false, "message" => "Signup Failed", "debug_error" => $e->getMessage()]);
}
?>