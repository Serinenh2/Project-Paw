<?php
require_once __DIR__.'/../core/auth.php'; require_role(['admin']);
require_once __DIR__.'/../core/db_connect.php';
$pdo = db();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $fullname = trim($_POST['fullname'] ?? '');
  $matricule = trim($_POST['matricule'] ?? '');
  $group_id = (int)($_POST['group_id'] ?? 0);
  if (!$fullname || !$matricule) die("Invalid input");
  $stmt = $pdo->prepare("INSERT INTO students(fullname, matricule, group_id) VALUES (?,?,?)");
  $stmt->execute([$fullname, $matricule, $group_id ?: null]);
  header('Location: ../public/admin/students.php'); exit;
}
?>
<!doctype html><html><head>
<meta charset="utf-8"><title>Add Student</title>
<link rel="stylesheet" href="../public/assets/libs/bootstrap.min.css">
</head><body class="container py-4">
  <h3>Add student</h3>
  <form method="post" class="card p-3" style="max-width:520px">
    <div class="mb-2"><label>Fullname</label><input name="fullname" class="form-control" required></div>
    <div class="mb-2"><label>Matricule</label><input name="matricule" class="form-control" required></div>
    <div class="mb-2"><label>Group ID (optional)</label><input name="group_id" class="form-control"></div>
    <button class="btn btn-primary">Save</button>
  </form>
</body></html>
