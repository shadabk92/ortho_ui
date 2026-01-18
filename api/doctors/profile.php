<?php
require "../config.php";

$doctorId = $_GET['doctor_id'] ?? 0;

$stmt = $pdo->prepare("
    SELECT 
        d.id AS doctor_id,
        u.first_name,
        u.last_name,
        d.specialty,
        d.bio,
        d.profile_image,
        d.affiliation,
        d.location,
        d.website,
        (SELECT COUNT(*) FROM followers f WHERE f.doctor_id=d.id) AS followers,
        (SELECT COUNT(*) FROM courses c WHERE c.instructor_id=u.id) AS total_courses
    FROM doctors d
    JOIN users u ON u.id=d.user_id
    WHERE d.id=?
");

$stmt->execute([$doctorId]);
$data = $stmt->fetch();

echo json_encode([
    "success" => true,
    "data" => $data
]);
