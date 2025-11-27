<?php
require_once __DIR__.'/../core/auth.php'; require_role(['admin']);
require_once __DIR__.'/../core/db_connect.php';
header('Content-Type: application/json');
$pdo = db();
echo json_encode($pdo->query("SELECT * FROM students")->fetchAll());
