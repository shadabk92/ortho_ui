<?php
require "../config.php";

$stmt = $pdo->prepare("
    INSERT INTO course_chat (course_id, lesson_id, user_id, message)
    VALUES (?,?,?,?)
");

$stmt->execute([
    $_POST['course_id'],
    $_POST['lesson_id'],
    $_SESSION['user_id'],
    $_POST['message']
]);

echo json_encode(["sent"=>true]);
