<?php
// FILE: api/config.php

// 1. CORS Headers
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
header("Content-Type: application/json; charset=UTF-8");

// Handle Preflight
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit();
}

// 2. ERROR HANDLING WRAPPER
// Turn off standard error printing so it doesn't break JSON
ini_set('display_errors', 0); 
error_reporting(E_ALL);

// Start Output Buffering (captures accidental echoes)
ob_start();

// Shutdown Function: Catches Fatal PHP Errors (500s)
register_shutdown_function(function() {
    $error = error_get_last();
    if ($error !== NULL && in_array($error['type'], [E_ERROR, E_PARSE, E_CORE_ERROR, E_COMPILE_ERROR])) {
        ob_clean(); // Remove HTML garbage
        http_response_code(500);
        echo json_encode([
            "success" => false,
            "message" => "Critical Server Error",
            "debug_error" => [
                "type" => "Fatal PHP Error",
                "message" => $error['message'],
                "file" => $error['file'],
                "line" => $error['line']
            ]
        ]);
    }
    ob_end_flush();
});

// 3. Start Session
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// 4. Database Connection
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
    ob_clean();
    http_response_code(500);
    echo json_encode([
        "success" => false, 
        "message" => "Database Connection Failed",
        "debug_error" => $e->getMessage()
    ]);
    exit;
}
?>