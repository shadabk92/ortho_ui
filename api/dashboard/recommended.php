<?php
require "../config.php";

$stmt = $pdo->query("
    SELECT c.title, c.cover_image,
           CONCAT('Dr. ', u.first_name, ' ', u.last_name) instructor
    FROM courses c
    JOIN users u ON u.id=c.instructor_id
    WHERE c.status='published'
    ORDER BY RAND()
    LIMIT 3
");

echo json_encode($stmt->fetchAll());
