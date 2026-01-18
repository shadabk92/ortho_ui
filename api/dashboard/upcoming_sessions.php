<?php
require "../config.php";

$stmt = $pdo->query("
    SELECT c.title, s.session_date, s.start_time,
           CONCAT('Dr. ', u.first_name, ' ', u.last_name) instructor
    FROM live_sessions s
    JOIN courses c ON c.id=s.course_id
    JOIN users u ON u.id=c.instructor_id
    WHERE s.session_date >= CURDATE()
    ORDER BY s.session_date ASC
    LIMIT 2
");

echo json_encode($stmt->fetchAll());
