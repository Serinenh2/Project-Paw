<?php require_once __DIR__.'/../../core/auth.php'; require_role(['admin']); ?>
<!doctype html><html><head>
<meta charset="utf-8"><title>Import/Export</title>
<link rel="stylesheet" href="../assets/libs/bootstrap.min.css">
</head><body class="container py-4">
  <h3>Import/Export (Progres-compatible CSV)</h3>
  <div class="mb-3">
    <a class="btn btn-outline-primary" href="../../api/export_students.php">Export Students (CSV)</a>
  </div>
  <form action="../../api/import_students.php" method="post" enctype="multipart/form-data" class="card p-3" style="max-width:520px">
    <div class="mb-2"><input type="file" name="file" accept=".csv" class="form-control" required></div>
    <button class="btn btn-secondary">Import CSV</button>
  </form>
</body></html>
