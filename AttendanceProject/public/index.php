<?php require_once __DIR__.'/../core/auth.php'; require_role(['student','professor','admin']); ?>
<!doctype html><html lang="en"><head>
<meta charset="utf-8"><title>Home</title>
<link rel="stylesheet" href="assets/libs/bootstrap.min.css">
<link rel="stylesheet" href="assets/css/style.css">
</head><body class="container py-4">
  <div class="d-flex justify-content-between align-items-center mb-3">
    <h3>Welcome, <?= htmlspecialchars($_SESSION['user']['fullname']) ?></h3>
    <a class="btn btn-outline-dark" href="logout.php">Logout</a>
  </div>
  <div class="list-group">
    <?php if ($_SESSION['user']['role']=='professor'): ?>
      <a class="list-group-item list-group-item-action" href="professor/home.php">Professor Dashboard</a>
    <?php endif; ?>
    <?php if ($_SESSION['user']['role']=='student'): ?>
      <a class="list-group-item list-group-item-action" href="student/home.php">Student Dashboard</a>
    <?php endif; ?>
    <?php if ($_SESSION['user']['role']=='admin'): ?>
      <a class="list-group-item list-group-item-action" href="admin/home.php">Admin Dashboard</a>
    <?php endif; ?>
  </div>
</body></html>
