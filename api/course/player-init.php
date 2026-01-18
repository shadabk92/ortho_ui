<?php
require "../config.php";

$userId   = $_SESSION['user_id'];
$courseId = $_GET['course_id'];

$check = $pdo->prepare("
    SELECT id FROM enrollments
    WHERE user_id=? AND course_id=?
");
$check->execute([$userId, $courseId]);

if ($check->rowCount() === 0) {
    echo json_encode(["error" => "NOT_ENROLLED"]);
    exit;
}

echo json_encode(["access" => true]);
