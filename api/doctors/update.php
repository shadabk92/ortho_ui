<?php
require "../config.php";

$data = json_decode(file_get_contents("php://input"), true);

$stmt = $pdo->prepare("
    UPDATE doctors
    SET specialty=?, experience_years=?, bio=?
    WHERE user_id=?
");

$stmt->execute([
    $data['specialty'],
    $data['experience_years'],
    $data['bio'],
    $data['user_id']
]);

echo json_encode(["success"=>true]);
