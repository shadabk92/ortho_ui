<?php
// FILE: api/auth/session.php

if (!file_exists("../config.php")) {
    http_response_code(500);
    exit;
}
require "../config.php"; 

if (!isset($_SESSION['user_id'])) {
    // Return 200 OK but with logged_in false
    echo json_encode(["logged_in" => false]);
    exit;
}

echo json_encode([
    "logged_in" => true,
    "user" => [
        "id" => $_SESSION['user_id'],
        "name" => $_SESSION['name'],
        "usertype_id" => $_SESSION['usertype_id']
    ]
]);
?>