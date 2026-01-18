<?php
require "../config.php";

$userId = $_SESSION['user_id'];

$stmt = $pdo->prepare("
    SELECT 
        cert.id,
        c.title,
        CONCAT('Dr. ',u.first_name,' ',u.last_name) instructor,
        cert.issued_at,
        cert.credits,
        cert.pdf_path,
        cert.thumbnail
    FROM certificates cert
    JOIN courses c ON c.id = cert.course_id
    JOIN users u ON u.id = c.instructor_id
    WHERE cert.user_id = ?
    ORDER BY cert.issued_at DESC
");

$stmt->execute([$userId]);
echo json_encode($stmt->fetchAll());
