<?php
require_once __DIR__.'/../../core/auth.php'; require_role(['admin']);
require_once __DIR__.'/../../core/db_connect.php';
$pdo = db();
$stats = $pdo->query("SELECT status, COUNT(*) AS cnt FROM attendance_records GROUP BY status")->fetchAll();
?>
<!doctype html><html><head>
<meta charset="utf-8"><title>Statistics</title>
<link rel="stylesheet" href="../assets/libs/bootstrap.min.css">
<script src="../assets/libs/jquery.min.js"></script>
</head><body class="container py-4">
  <h3>Statistics</h3>
  <ul class="list-group mb-3" style="max-width:480px">
    <?php foreach ($stats as $s): ?>
      <li class="list-group-item d-flex justify-content-between">
        <span><?= h($s['status']) ?></span><span class="badge bg-dark"><?= $s['cnt'] ?></span>
      </li>
    <?php endforeach; ?>
  </ul>
  <p class="text-muted">You can plug Chart.js later; the requirement is jQuery, so we keep it simple.</p>
</body></html>
