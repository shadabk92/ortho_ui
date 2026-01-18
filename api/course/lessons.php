<?php
require "../config.php";

$courseId = $_GET['course_id'];
$userId   = $_SESSION['user_id'];

$stmt = $pdo->prepare("
    SELECT l.id, l.module_title, l.title,
           COALESCE(p.completed,0) completed
    FROM course_lessons l
    LEFT JOIN lesson_progress p
        ON p.lesson_id=l.id AND p.user_id=?
    WHERE l.course_id=?
    ORDER BY l.sort_order
");

$stmt->execute([$userId, $courseId]);
echo json_encode($stmt->fetchAll());
