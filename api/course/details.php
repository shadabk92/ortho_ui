<?php
require "../config.php";

$courseId = $_GET['course_id'];

$stmt = $pdo->prepare("
    SELECT 
        c.id, c.title, c.subtitle, c.description,
        c.price, c.original_price, c.cover_image,
        c.difficulty, c.has_certificate,
        CONCAT('Dr. ', u.first_name,' ',u.last_name) instructor,
        u.bio instructor_bio, u.profile_image,
        cat.name category
    FROM courses c
    JOIN users u ON u.id=c.instructor_id
    JOIN coursecategory cat ON cat.id=c.category_id
    WHERE c.id=?
");

$stmt->execute([$courseId]);
echo json_encode($stmt->fetch());
