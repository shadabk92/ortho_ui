<?php
require "../config.php";

$lessonId = $_GET['lesson_id'];

$stmt = $pdo->prepare("
    SELECT
      (SELECT id FROM course_lessons WHERE sort_order < l.sort_order AND course_id=l.course_id ORDER BY sort_order DESC LIMIT 1) prev,
      (SELECT id FROM course_lessons WHERE sort_order > l.sort_order AND course_id=l.course_id ORDER BY sort_order ASC LIMIT 1) next
    FROM course_lessons l
    WHERE l.id=?
");
$stmt->execute([$lessonId]);

echo json_encode($stmt->fetch());
