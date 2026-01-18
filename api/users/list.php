<?php
require "../config.php";

$stmt = $pdo->query("
    SELECT u.id, u.first_name, u.last_name, u.email, ut.type_key AS role
    FROM users u
    JOIN usertypes ut ON ut.id = u.usertype_id
");

echo json_encode([
    "success" => true,
    "data" => $stmt->fetchAll()
]);
