<?php
require_once __DIR__.'/../../core/auth.php'; require_role(['student']);
require_once __DIR__.'/../../core/db_connect.php';
$pdo = db();
$student_id = $_SESSION['user']['id'];
$st = $pdo->prepare("
  SELECT students.*, groups.name AS grp, courses.name AS course, courses.id AS course_id
  FROM students
  JOIN groups ON groups.id = students.group_id
  JOIN courses ON courses.id = groups.course_id
  WHERE students.id = ?
");
$st->execute([$student_id]);
$st = $st->fetch();
?>
<!doctype html><html><head>
<meta charset="utf-8"><title>Student</title>
<link rel="stylesheet" href="../assets/libs/bootstrap.min.css">
</head><body class="container py-4">
  <h3>Your Courses</h3>
  <?php if ($st): ?>
    <div class="list-group">
      <a class="list-group-item list-group-item-action" href="course.php?course_id=<?= $st['course_id'] ?>">
        <?= h($st['course']) ?> (Group: <?= h($st['grp']) ?>)
      </a>
    </div>
  <?php else: ?>
    <div class="alert alert-warning">No assigned group/course.</div>
  <?php endif; ?>
</body></html>
