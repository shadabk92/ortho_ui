<?php
require "../config.php";

$userId = $_SESSION['user_id'];
$courseId = $_POST['course_id'];

$stmt = $pdo->prepare("
    INSERT IGNORE INTO enrollments (user_id, course_id)
    VALUES (?, ?)
");

$stmt->execute([$userId, $courseId]);

echo json_encode(["success" => true]);
