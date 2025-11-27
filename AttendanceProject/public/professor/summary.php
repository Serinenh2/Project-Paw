<?php
require_once __DIR__.'/../../core/auth.php'; require_role(['professor']);
require_once __DIR__.'/../../core/db_connect.php';
$pdo = db();
$course_id = (int)($_GET['course_id'] ?? 0);
$group_id  = (int)($_GET['group_id'] ?? 0);
$rows = $pdo->prepare("
  SELECT s.fullname, s.matricule, ar.status, ses.date
  FROM attendance_records ar
  JOIN students s ON s.id = ar.student_id
  JOIN attendance_sessions ses ON ses.id = ar.session_id
  WHERE ses.course_id = ? AND ses.group_id = ?
  ORDER BY s.fullname, ses.date
");
$rows->execute([$course_id, $group_id]);
$data = $rows->fetchAll();
?>
<!doctype html><html><head>
<meta charset="utf-8"><title>Summary</title>
<link rel="stylesheet" href="../assets/libs/bootstrap.min.css">
</head><body class="container py-4">
  <h3>Attendance Summary — Course <?= $course_id ?> / Group <?= $group_id ?></h3>
  <table class="table table-bordered">
    <thead><tr><th>Student</th><th>Matricule</th><th>Date</th><th>Status</th></tr></thead>
    <tbody>
      <?php foreach ($data as $d): ?>
      <tr>
        <td><?= h($d['fullname']) ?></td>
        <td><?= h($d['matricule']) ?></td>
        <td><?= $d['date'] ?></td>
        <td><span class="badge bg-<?= $d['status']=='present'?'success':'danger' ?>"><?= $d['status'] ?></span></td>
      </tr>
      <?php endforeach; ?>
    </tbody>
  </table>
</body></html>
