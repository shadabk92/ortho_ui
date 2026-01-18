<?php
require "../config.php";

$lessonId = $_GET['lesson_id'];

$lesson = $pdo->prepare("
    SELECT id,title,video_url,transcript,is_live,duration_sec
    FROM course_lessons
    WHERE id=?
");
$lesson->execute([$lessonId]);

$res = $pdo->prepare("
    SELECT file_name,file_url
    FROM lesson_resources
    WHERE lesson_id=?
");
$res->execute([$lessonId]);

echo json_encode([
    "lesson" => $lesson->fetch(),
    "resources" => $res->fetchAll()
]);
