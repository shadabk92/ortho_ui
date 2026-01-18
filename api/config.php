<?php
// FILE: api/config.php

// 1. Handle CORS (Cross-Origin Resource Sharing)
// This allows your HTML file to fetch data from this PHP file without security blocks.
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
header("Content-Type: application/json; charset=UTF-8");

// Handle "Preflight" OPTIONS request (Browser checks)
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit();
}

// 2. Start Session (Global)
// We do this here so you don't have to write session_start() in every single API file.
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// 3. Database Connection
$host = '127.0.0.1';
$db   = 'orthoui_db';
$user = 'orthoui_user';
$pass = 'OrthoPass123!';
$port = "3306";
$charset = 'utf8mb4';

$dsn = "mysql:host=$host;port=$port;dbname=$db;charset=$charset";
$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES   => false,
];

try {
    $pdo = new PDO($dsn, $user, $pass, $options);
} catch (\PDOException $e) {
    // 4. Error Handling
    // Instead of crashing with a 502/500 error, we return a JSON response.
    http_response_code(500);
    echo json_encode([
        "success" => false, 
        "message" => "Database Connection Failed: " . $e->getMessage()
    ]);
    exit; // Stop execution immediately
}
?>