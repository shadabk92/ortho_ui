<?php
require "../config.php";

$userId = $_SESSION['user_id'];

$stmt = $pdo->query("
    SELECT c.title, c.cover_image, e.progress_percent,
           CONCAT('Dr. ', u.first_name, ' ', u.last_name) instructor
    FROM enrollments e
    JOIN courses c ON c.id=e.course_id
    JOIN users u ON u.id=c.instructor_id
    WHERE e.user_id=$userId AND e.completed=0
    LIMIT 2
");

echo json_encode($stmt->fetchAll());
