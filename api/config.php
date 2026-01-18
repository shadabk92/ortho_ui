<?php
$pdo = new PDO(
    "mysql:host=127.0.0.1;port=3308;dbname=orthodb;charset=utf8mb4",
    "root",
    "root8039",
    [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
    ]
);
