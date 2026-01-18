<?php
require "../config.php";

$page = $_GET['page'] ?? 1;
$limit = 8;
$offset = ($page - 1) * $limit;

$search = $_GET['search'] ?? '';
$difficulty = $_GET['difficulty'] ?? [];
$category = $_GET['category'] ?? [];

$where = "c.status='published'";
$params = [];

if ($search) {
    $where .= " AND c.title LIKE ?";
    $params[] = "%$search%";
}

if ($difficulty) {
    $in = implode(',', array_fill(0, count($difficulty), '?'));
    $where .= " AND c.difficulty IN ($in)";
    $params = array_merge($params, $difficulty);
}

if ($category) {
    $in = implode(',', array_fill(0, count($category), '?'));
    $where .= " AND c.category_id IN ($in)";
    $params = array_merge($params, $category);
}

$sql = "
SELECT 
    c.id, c.title, c.difficulty, c.cover_image, c.has_certificate,
    CONCAT('Dr. ', u.first_name,' ',u.last_name) instructor
FROM courses c
JOIN users u ON u.id=c.instructor_id
WHERE $where
ORDER BY c.created_at DESC
LIMIT $limit OFFSET $offset
";

$stmt = $pdo->prepare($sql);
$stmt->execute($params);

echo json_encode($stmt->fetchAll());
