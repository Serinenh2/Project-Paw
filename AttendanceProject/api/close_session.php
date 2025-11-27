<?php
require_once __DIR__.'/../core/auth.php'; require_role(['professor']);
require_once __DIR__.'/../core/db_connect.php';
$pdo = db();
$id = (int)($_GET['id'] ?? 0);
$pdo->prepare("UPDATE attendance_sessions SET status='closed' WHERE id=?")->execute([$id]);
header('Location: ../public/professor/home.php');
