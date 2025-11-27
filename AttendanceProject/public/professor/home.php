<?php
require_once __DIR__.'/../../core/auth.php'; require_role(['professor']);
require_once __DIR__.'/../../core/db_connect.php';
$pdo = db();
$sessions = $pdo->prepare("
  SELECT ses.id, c.name AS course, g.name AS grp, ses.date, ses.status
  FROM attendance_sessions ses
  JOIN courses c ON c.id = ses.course_id
  JOIN groups g ON g.id = ses.group_id
  WHERE ses.opened_by = ?
  ORDER BY ses.date DESC
");
$sessions->execute([$_SESSION['user']['id']]);
$list = $sessions->fetchAll();
?>
<!doctype html><html><head>
<meta charset="utf-8"><title>Professor</title>
<link rel="stylesheet" href="../assets/libs/bootstrap.min.css">
</head><body class="container py-4">
  <div class="d-flex justify-content-between mb-3">
    <h3>Your Sessions</h3>
    <a class="btn btn-success" href="../../api/create_session.php">Create Session</a>
  </div>
  <table class="table table-striped">
    <thead><tr><th>ID</th><th>Course</th><th>Group</th><th>Date</th><th>Status</th><th>Actions</th></tr></thead>
    <tbody>
      <?php foreach ($list as $s): ?>
      <tr>
        <td><?= $s['id'] ?></td>
        <td><?= h($s['course']) ?></td>
        <td><?= h($s['grp']) ?></td>
        <td><?= $s['date'] ?></td>
        <td><span class="badge bg-<?= $s['status']=='open'?'primary':'secondary' ?>"><?= $s['status'] ?></span></td>
        <td>
          <a class="btn btn-sm btn-outline-primary" href="session.php?id=<?= $s['id'] ?>">Mark</a>
          <?php if ($s['status']=='open'): ?>
            <a class="btn btn-sm btn-outline-warning" href="../../api/close_session.php?id=<?= $s['id'] ?>">Close</a>
          <?php endif; ?>
        </td>
      </tr>
      <?php endforeach; ?>
    </tbody>
  </table>
</body></html>
