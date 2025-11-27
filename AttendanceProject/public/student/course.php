<?php
require_once __DIR__.'/../../core/auth.php'; require_role(['student']);
require_once __DIR__.'/../../core/db_connect.php';
$pdo = db();
$course_id = (int)($_GET['course_id'] ?? 0);
$student_id = $_SESSION['user']['id'];

$rows = $pdo->prepare("
  SELECT ses.id AS session_id, ses.date, ar.status
  FROM attendance_sessions ses
  LEFT JOIN attendance_records ar ON ar.session_id = ses.id AND ar.student_id = ?
  WHERE ses.course_id = ?
  ORDER BY ses.date DESC
");
$rows->execute([$student_id, $course_id]);
$data = $rows->fetchAll();
?>
<!doctype html><html><head>
<meta charset="utf-8"><title>Attendance History</title>
<link rel="stylesheet" href="../assets/libs/bootstrap.min.css">
</head><body class="container py-4">
  <h3>Attendance History</h3>
  <table class="table">
    <thead><tr><th>Date</th><th>Status</th><th>Justification</th></tr></thead>
    <tbody>
      <?php foreach ($data as $d): ?>
      <tr>
        <td><?= $d['date'] ?></td>
        <td><?= $d['status'] ?? 'N/A' ?></td>
        <td>
          <?php if (($d['status'] ?? '') === 'absent'): ?>
            <form action="../../api/upload_justification.php" method="post" enctype="multipart/form-data" class="d-flex gap-2">
              <input type="hidden" name="session_id" value="<?= $d['session_id'] ?>">
              <input type="hidden" name="course_id" value="<?= $course_id ?>">
              <input type="file" name="file" class="form-control" required>
              <input type="text" name="reason" class="form-control" placeholder="Reason" required>
              <button class="btn btn-secondary btn-sm">Submit</button>
            </form>
          <?php else: ?> — <?php endif; ?>
        </td>
      </tr>
      <?php endforeach; ?>
    </tbody>
  </table>
</body></html>
