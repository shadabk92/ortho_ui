<?php
require "../config.php";

$stmt = $pdo->prepare("UPDATE users SET is_active=0 WHERE id=?");
$stmt->execute([$_GET['id']]);

echo json_encode(["success"=>true]);
