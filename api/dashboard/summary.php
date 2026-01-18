<?php
require "../config.php";

$userId = $_SESSION['user_id'];

$totalCompleted = $pdo->query("
    SELECT COUNT(*) FROM enrollments 
    WHERE user_id=$userId AND completed=1
")->fetchColumn();

$certificates = $pdo->query("
    SELECT COUNT(*) FROM certificates WHERE user_id=$userId
")->fetchColumn();

$totalHours = $pdo->query("
    SELECT SUM(progress_percent) * 0.5 FROM enrollments WHERE user_id=$userId
")->fetchColumn();

echo json_encode([
    "completed" => (int)$totalCompleted,
    "certificates" => (int)$certificates,
    "hours" => round($totalHours,1)
]);
