<?php
require_once __DIR__.'/../core/auth.php'; require_role(['professor']);
require_once __DIR__.'/../core/db_connect.php';
$pdo = db();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $course_id = (int)($_POST['course_id'] ?? 0);
  $group_id  = (int)($_POST['group_id'] ?? 0);
  $date      = $_POST['date'] ?? date('Y-m-d');

  try {
    $stmt = $pdo->prepare("INSERT INTO attendance_sessions(course_id, group_id, date, opened_by, status) VALUES (?,?,?,?, 'open')");
    $stmt->execute([$course_id, $group_id, $date, $_SESSION['user']['id']]);
    $id = $pdo->lastInsertId();
    echo "<div class='alert alert-success m-3'>Session created (#$id)</div><a href='../public/professor/home.php'>Back</a>";
  } catch (PDOException $e) {
    echo "<div class='alert alert-danger m-3'>Error: ".htmlspecialchars($e->getMessage())."</div>";
  }
  exit;
}
?>
<!doctype html><html><head>
<meta charset="utf-8"><title>Create Session</title>
<link rel="stylesheet" href="../public/assets/libs/bootstrap.min.css">
</head><body class="container py-4">
  <h3>Create new session</h3>
  <form method="post" class="card p-3">
    <div class="mb-2"><label>Course ID</label><input name="course_id" class="form-control" required></div>
    <div class="mb-2"><label>Group ID</label><input name="group_id" class="form-control" required></div>
    <div class="mb-2"><label>Date</label><input name="date" type="date" class="form-control" value="<?= date('Y-m-d') ?>"></div>
    <button class="btn btn-primary">Create</button>
  </form>
</body></html>
