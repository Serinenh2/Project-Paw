<?php
require_once __DIR__.'/../core/auth.php'; require_role(['admin']);
require_once __DIR__.'/../core/db_connect.php';
$pdo = db();
$id = (int)($_GET['id'] ?? 0);
$pdo->prepare("DELETE FROM students WHERE id=?")->execute([$id]);
header('Location: ../public/admin/students.php');
