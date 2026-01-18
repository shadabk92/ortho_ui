<?php
require "../config.php";

$data = json_decode(file_get_contents("php://input"), true);

$stmt = $pdo->prepare("
    INSERT INTO course_participants (course_id, user_id)
    VALUES (?,?)
");

$stmt->execute([
    $data['course_id'],
    $data['user_id']
]);

echo json_encode(["success"=>true]);
