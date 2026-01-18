<?php
require "../config.php";

$userId = $_SESSION['user_id'];
$zip = new ZipArchive();
$zipName = "certificates.zip";

$zip->open($zipName, ZipArchive::CREATE | ZipArchive::OVERWRITE);

$stmt = $pdo->prepare("SELECT pdf_path FROM certificates WHERE user_id=?");
$stmt->execute([$userId]);

foreach ($stmt->fetchAll() as $c) {
    $zip->addFile("../".$c['pdf_path'], basename($c['pdf_path']));
}

$zip->close();

header("Content-Type: application/zip");
header("Content-Disposition: attachment; filename=$zipName");
readfile($zipName);
unlink($zipName);
