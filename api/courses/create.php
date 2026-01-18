<?php
require "../config.php";

$data = json_decode(file_get_contents("php://input"), true);

$stmt = $pdo->prepare("
    INSERT INTO courses
    (course_type_id, category_id, subcategory_id, title, description,
     instructor_id, session_date, start_time, end_time,
     max_participants, status)
    VALUES (?,?,?,?,?,?,?,?,?,?,?)
");

$stmt->execute([
    $data['course_type_id'],
    $data['category_id'],
    $data['subcategory_id'],
    $data['title'],
    $data['description'],
    $data['instructor_id'],
    $data['session_date'],
    $data['start_time'],
    $data['end_time'],
    $data['max_participants'],
    'draft'
]);

$courseId = $pdo->lastInsertId();

/* TAGS */
foreach ($data['tags'] as $tagName) {
    $stmt = $pdo->prepare("INSERT IGNORE INTO course_tags (name) VALUES (?)");
    $stmt->execute([$tagName]);

    $tagId = $pdo->lastInsertId() ?: 
        $pdo->query("SELECT id FROM course_tags WHERE name='$tagName'")->fetch()['id'];

    $pdo->prepare("INSERT INTO course_tag_map VALUES (?,?)")
        ->execute([$courseId, $tagId]);
}

echo json_encode(["success"=>true,"course_id"=>$courseId]);
