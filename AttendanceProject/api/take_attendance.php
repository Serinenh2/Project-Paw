<?php
require_once __DIR__.'/../core/auth.php'; require_role(['professor']);
require_once __DIR__.'/../core/db_connect.php';
header('Content-Type: application/json');

$pdo = db();
$session_id = (int)($_POST['session_id'] ?? 0);
$statusMap  = $_POST['status'] ?? [];

try {
  $st = $pdo->prepare("SELECT status FROM attendance_sessions WHERE id=?");
  $st->execute([$session_id]);
  $session = $st->fetch();
  if (!$session) throw new Exception('Session not found');
  if ($session['status'] === 'closed') throw new Exception('Session closed');

  $pdo->beginTransaction();
  foreach ($statusMap as $student_id => $stt) {
    $stt = $stt==='present' ? 'present' : 'absent';
    $sel = $pdo->prepare("SELECT id FROM attendance_records WHERE session_id=? AND student_id=?");
    $sel->execute([$session_id, $student_id]);
    if ($sel->fetch()) {
      $upd = $pdo->prepare("UPDATE attendance_records SET status=? WHERE session_id=? AND student_id=?");
      $upd->execute([$stt, $session_id, $student_id]);
    } else {
      $ins = $pdo->prepare("INSERT INTO attendance_records(session_id, student_id, status) VALUES (?,?,?)");
      $ins->execute([$session_id, $student_id, $stt]);
    }
  }
  $pdo->commit();
  echo json_encode(['ok'=>true, 'message'=>'Attendance saved']);
} catch (Exception $e) {
  if ($pdo->inTransaction()) $pdo->rollBack();
  echo json_encode(['ok'=>false, 'message'=>$e->getMessage()]);
}
