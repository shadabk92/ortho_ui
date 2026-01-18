<?php
require "../config.php";

if (!isset($_SESSION['user_id'])) {
    echo json_encode(["logged_in"=>false]);
    exit;
}

echo json_encode([
    "logged_in" => true,
    "user" => [
        "id" => $_SESSION['user_id'],
        "name" => $_SESSION['name']
    ]
]);
