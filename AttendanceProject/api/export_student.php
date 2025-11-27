<?php
require_once __DIR__.'/../core/auth.php'; require_role(['admin']);
require_once __DIR__.'/../core/db_connect.php';
$pdo = db();
$rows = $pdo->query("SELECT fullname, matricule, group_id FROM students")->fetchAll();

header('Content-Type: text/csv');
header('Content-Disposition: attachment; filename="students_export.csv"');

$out = fopen('php://output', 'w');
fputcsv($out, ['fullname','matricule','group_id']);
foreach ($rows as $r) fputcsv($out, $r);
fclose($out);
