<?php
require "../config.php";

$userId = $_SESSION['user_id'];
$courseId = $_GET['course_id'];

$stmt = $pdo->prepare("
    SELECT id FROM enrollments 
    WHERE user_id=? AND course_id=?
");

$stmt->execute([$userId, $courseId]);

echo json_encode([
    "enrolled" => $stmt->rowCount() > 0
]);
