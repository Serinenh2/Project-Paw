<?php
require_once __DIR__.'/../core/auth.php'; require_role(['admin','professor']);
require_once __DIR__.'/../core/db_connect.php';
header('Content-Type: application/json');
$pdo = db();
$course_id = (int)($_GET['course_id'] ?? 0);
$group_id  = (int)($_GET['group_id'] ?? 0);
$stmt = $pdo->prepare("
  SELECT s.fullname, s.matricule,
    SUM(ar.status='present') AS presents,
    SUM(ar.status='absent') AS absents
  FROM attendance_records ar
  JOIN students s ON s.id = ar.student_id
  JOIN attendance_sessions ses ON ses.id = ar.session_id
  WHERE ses.course_id = ? AND ses.group_id = ?
  GROUP BY s.id
");
$stmt->execute([$course_id, $group_id]);
echo json_encode($stmt->fetchAll());
