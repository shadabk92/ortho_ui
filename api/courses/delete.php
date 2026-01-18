<?php
require "../config.php";

$stmt = $pdo->prepare("UPDATE courses SET status='archived' WHERE id=?");
$stmt->execute([$_GET['id']]);

echo json_encode(["success"=>true]);
