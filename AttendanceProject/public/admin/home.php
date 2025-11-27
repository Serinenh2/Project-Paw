<?php require_once __DIR__.'/../../core/auth.php'; require_role(['admin']); ?>
<!doctype html><html><head>
<meta charset="utf-8"><title>Admin</title>
<link rel="stylesheet" href="../assets/libs/bootstrap.min.css">
</head><body class="container py-4">
  <h3>Admin Dashboard</h3>
  <ul class="list-group">
    <li class="list-group-item"><a href="students.php">Manage Students</a></li>
    <li class="list-group-item"><a href="stats.php">Statistics</a></li>
    <li class="list-group-item"><a href="import_export.php">Import/Export (Progres)</a></li>
  </ul>
</body></html>
