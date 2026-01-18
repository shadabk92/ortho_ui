<?php
require "../config.php";

$data = json_decode(file_get_contents("php://input"), true);

$role = $data['role']; // doctor | healthcare_professional
$firstName = $data['first_name'];
$lastName  = $data['last_name'];
$email     = $data['email'];
$password  = password_hash($data['password'], PASSWORD_BCRYPT);

/* 1️⃣ Get usertype ID */
$stmt = $pdo->prepare("SELECT id FROM usertypes WHERE type_key = ?");
$stmt->execute([$role]);
$usertype = $stmt->fetch();

if (!$usertype) {
    http_response_code(400);
    echo json_encode(["success"=>false,"message"=>"Invalid role"]);
    exit;
}

/* 2️⃣ Insert into users */
$stmt = $pdo->prepare("
    INSERT INTO users (usertype_id, first_name, last_name, email, password)
    VALUES (?,?,?,?,?)
");
$stmt->execute([
    $usertype['id'],
    $firstName,
    $lastName,
    $email,
    $password
]);

$userId = $pdo->lastInsertId();

/* 3️⃣ Insert role-specific data */
if ($role === 'doctor') {
    $stmt = $pdo->prepare("
        INSERT INTO doctors (user_id, license_number, specialty)
        VALUES (?,?,?)
    ");
    $stmt->execute([
        $userId,
        $data['license_number'],
        $data['specialty']
    ]);
}

if ($role === 'healthcare_professional') {
    $stmt = $pdo->prepare("
        INSERT INTO healthcare_professionals (user_id, profession, organization)
        VALUES (?,?,?)
    ");
    $stmt->execute([
        $userId,
        $data['profession'],
        $data['organization']
    ]);
}

echo json_encode([
    "success" => true,
    "message" => "Account created successfully"
]);
