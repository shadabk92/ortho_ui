<?php
require "../config.php";

$userId   = $_SESSION['user_id'];
$lessonId = $_POST['lesson_id'];
$progress = $_POST['progress'];

$completed = $progress >= 90 ? 1 : 0;

$stmt = $pdo->prepare("
    INSERT INTO lesson_progress (user_id, lesson_id, progress_percent, completed)
    VALUES (?,?,?,?)
    ON DUPLICATE KEY UPDATE
    progress_percent=?, completed=?
");

$stmt->execute([$userId,$lessonId,$progress,$completed,$progress,$completed]);
echo json_encode(["saved"=>true]);
