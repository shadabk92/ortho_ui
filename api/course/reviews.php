<?php
require "../config.php";

$courseId = $_GET['course_id'];

$stmt = $pdo->prepare("
    SELECT r.rating, r.review,
           CONCAT(u.first_name,' ',u.last_name) user
    FROM course_reviews r
    JOIN users u ON u.id=r.user_id
    WHERE r.course_id=?
");

$stmt->execute([$courseId]);
echo json_encode($stmt->fetchAll());
