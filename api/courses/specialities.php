<?php
require "../config.php";

$stmt = $pdo->query("SELECT id, name FROM coursecategory ORDER BY name");
echo json_encode($stmt->fetchAll());
