<?php
require "../config.php";

$lessonId = $_GET['lesson_id'];

$stmt = $pdo->prepare("
    SELECT c.message,c.created_at,
           CONCAT(u.first_name,' ',u.last_name) user,
           u.role
    FROM course_chat c
    JOIN users u ON u.id=c.user_id
    WHERE c.lesson_id=?
    ORDER BY c.created_at ASC
");

$stmt->execute([$lessonId]);
echo json_encode($stmt->fetchAll());
