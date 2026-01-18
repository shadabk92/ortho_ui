<?php
require "../config.php";

$id = $_GET['id'];
$userId = $_SESSION['user_id'];

$stmt = $pdo->prepare("
    SELECT pdf_path 
    FROM certificates 
    WHERE id=? AND user_id=?
");
$stmt->execute([$id,$userId]);

$file = $stmt->fetch();

header("Content-Type: application/pdf");
header("Content-Disposition: attachment; filename=certificate.pdf");
readfile("../".$file['pdf_path']);
