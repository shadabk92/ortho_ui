<?php
require "../config.php";

$stmt = $pdo->prepare("
    INSERT IGNORE INTO followers (doctor_id, user_id)
    VALUES (?,?)
");

$stmt->execute([
    $_POST['doctor_id'],
    $_POST['user_id']
]);

echo json_encode(["success"=>true]);
