<?php
require_once __DIR__.'/../core/auth.php'; require_role(['admin']);
require_once __DIR__.'/../core/db_connect.php';
$pdo = db();
$id = (int)($_GET['id'] ?? 0);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $fullname = trim($_POST['fullname'] ?? '');
  $matricule = trim($_POST['matricule'] ?? '');
  $group_id = (int)($_POST['group_id'] ?? 0);
  $stmt = $pdo->prepare("UPDATE students SET fullname=?, matricule=?, group_id=? WHERE id=?");
  $stmt->execute([$fullname, $matricule, $group_id ?: null, $id]);
  header('Location: ../public/admin/students.php'); exit;
}

$st = $pdo->prepare("SELECT * FROM students WHERE id=?"); $st->execute([$id]); $st = $st->fetch();
if (!$st) { echo "Student not found"; exit; }
?>
<!doctype html><html><head>
<meta charset="utf-8"><title>Edit Student</title>
<link rel="stylesheet" href="../public/assets/libs/bootstrap.min.css">
</head><body class="container py-4">
  <h3>Edit student #<?= $id ?></h3>
  <form method="post" class="card p-3" style="max-width:520px">
    <div class="mb-2"><label>Fullname</label><input name="fullname" class="form-control" value="<?= h($st['fullname']) ?>"></div>
    <div class="mb-2"><label>Matricule</label><input name="matricule" class="form-control" value="<?= h($st['matricule']) ?>"></div>
    <div class="mb-2"><label>Group ID</label><input name="group_id" class="form-control" value="<?= h($st['group_id']) ?>"></div>
    <button class="btn btn-primary">Update</button>
  </form>
</body></html>
