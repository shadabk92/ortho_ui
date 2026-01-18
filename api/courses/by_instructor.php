<?php
require "../config.php";

$doctorId = $_GET['doctor_id'] ?? 0;

$stmt = $pdo->prepare("
    SELECT 
        c.id,
        c.title,
        c.description,
        c.cover_image,
        c.recording_video,
        ct.type_key AS course_type,
        c.created_at
    FROM courses c
    JOIN doctors d ON d.user_id=c.instructor_id
    JOIN course_types ct ON ct.id=c.course_type_id
    WHERE d.id=?
    AND c.status='published'
    ORDER BY c.created_at DESC
");

$stmt->execute([$doctorId]);

echo json_encode([
    "success" => true,
    "data" => $stmt->fetchAll()
]);
