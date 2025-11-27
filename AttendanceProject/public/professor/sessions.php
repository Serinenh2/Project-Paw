<?php
session_start();

// Vérification de l'accès
if (!isset($_SESSION["role"]) || $_SESSION["role"] !== "professor") {
    header("Location: ../login.php");
    exit;
}

// Connexion à la base
$dsn = "mysql:host=127.0.0.1;dbname=attendance_db;charset=utf8";
$dbUser = "root";
$dbPass = ""; // mets ton mot de passe MySQL si nécessaire

try {
    $pdo = new PDO($dsn, $dbUser, $dbPass, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
    ]);
} catch (PDOException $e) {
    die("Erreur de connexion : " . htmlspecialchars($e->getMessage()));
}

// Récupérer les sessions créées par ce professeur
$sql = "
    SELECT s.id, c.name AS course, g.name AS grp, s.date, s.status
    FROM attendance_sessions AS s
    LEFT JOIN courses AS c ON c.id = s.course_id
    LEFT JOIN student_groups AS g ON g.id = s.group_id
    WHERE s.opened_by = ?
    ORDER BY s.date DESC, s.id DESC
";
$stmt = $pdo->prepare($sql);
$stmt->execute([$_SESSION["user_id"]]);
$sessions = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Manage Sessions - Professor Panel</title>
  <style>
    * { margin: 0; padding: 0; box-sizing: border-box; }
    body { font-family: 'Segoe UI', sans-serif; background: #f4f6f9; display: flex; min-height: 100vh; }

    .sidebar { width: 220px; background: #2c3e50; color: #fff; padding: 20px; display: flex; flex-direction: column; justify-content: space-between; }
    .sidebar h2 { margin-bottom: 30px; text-align: center; }
    .sidebar a { display: block; color: #fff; text-decoration: none; padding: 12px; border-radius: 6px; margin-bottom: 10px; transition: background 0.3s; }
    .sidebar a:hover { background: #34495e; }
    .logout { margin-top: 20px; text-align: center; }
    .logout a { color: #fff; text-decoration: none; background: #e74c3c; padding: 10px 15px; border-radius: 6px; transition: background 0.3s; }
    .logout a:hover { background: #c0392b; }

    .main { flex: 1; padding: 30px; }
    .header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 30px; }
    .header h1 { color: #333; }

    .tools { margin-bottom: 20px; }
    .btn { padding: 10px 14px; border-radius: 8px; border: none; cursor: pointer; font-weight: 600; text-decoration: none; }
    .btn-primary { background: #2c3e50; color: #fff; }
    .btn-primary:hover { filter: brightness(0.95); }

    .table-wrap { background: #fff; border-radius: 12px; box-shadow: 0 4px 12px rgba(0,0,0,0.1); overflow: hidden; }
    table { width: 100%; border-collapse: collapse; }
    th, td { padding: 14px; text-align: left; }
    th { background: #2c3e50; color: #fff; }
    tr:nth-child(even) { background: #f9f9f9; }
    tr:hover { background: #eef2ff; }

    .status-badge { display: inline-block; padding: 6px 10px; border-radius: 16px; font-size: 12px; font-weight: 700; }
    .open { background: #eaf7ef; color: #2ecc71; border: 1px solid #2ecc71; }
    .closed { background: #fdecec; color: #e74c3c; border: 1px solid #e74c3c; }

    .actions a { margin-right: 8px; text-decoration: none; padding: 6px 10px; border-radius: 6px; font-size: 14px; }
    .view { background: #8f94fb; color: #fff; }
    .toggle { background: #f39c12; color: #fff; }
    .delete { background: #e74c3c; color: #fff; }
    .view:hover, .toggle:hover, .delete:hover { filter: brightness(0.95); }

    .empty { background: #fff; padding: 24px; border-radius: 12px; box-shadow: 0 4px 12px rgba(0,0,0,0.08); color: #555; }
  </style>
</head>
<body>
  <!-- Sidebar -->
  <div class="sidebar">
    <div>
      <h2>Professor Panel</h2>
      <a href="dashboard.php">🏠 Dashboard</a>
      <a href="sessions.php">📝 Manage Sessions</a>
      <a href="attendance.php">👨‍🎓 Mark Attendance</a>
      <a href="reports.php">📊 Reports</a>
    </div>
    <div class="logout">
      <a href="../logout.php">Logout</a>
    </div>
  </div>

  <!-- Main -->
  <div class="main">
    <div class="header">
      <h1>Manage Sessions</h1>
      <p>📅 <?= date("d M Y") ?></p>
    </div>

    <div class="tools">
      <a class="btn btn-primary" href="create_session.php">+ Create Session</a>
    </div>

    <?php if (empty($sessions)): ?>
      <div class="empty">You have not created any sessions yet.</div>
    <?php else: ?>
      <div class="table-wrap">
        <table>
          <thead>
            <tr>
              <th>ID</th>
              <th>Course</th>
              <th>Group</th>
              <th>Date</th>
              <th>Status</th>
              <th>Actions</th>
            </tr>
          </thead>
          <tbody>
            <?php foreach ($sessions as $s): ?>
              <tr>
                <td><?= htmlspecialchars($s['id']) ?></td>
                <td><?= htmlspecialchars($s['course'] ?? '—') ?></td>
                <td><?= htmlspecialchars($s['grp'] ?? '—') ?></td>
                <td><?= htmlspecialchars($s['date']) ?></td>
                <td>
                  <?php $status = strtolower($s['status']); ?>
                  <span class="status-badge <?= $status === 'open' ? 'open' : 'closed' ?>">
                    <?= ucfirst($status) ?>
                  </span>
                </td>
                <td class="actions">
                  <a class="view" href="session_view.php?id=<?= urlencode($s['id']) ?>">View</a>
                  <a class="toggle" href="session_toggle.php?id=<?= urlencode($s['id']) ?>" onclick="return confirm('Toggle status for this session?')">
                    <?= $status === 'open' ? 'Close' : 'Reopen' ?>
                  </a>
                  <a class="delete" href="session_delete.php?id=<?= urlencode($s['id']) ?>" onclick="return confirm('Delete this session?')">Delete</a>
                </td>
              </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
      </div>
    <?php endif; ?>
  </div>
</body>
</html>

