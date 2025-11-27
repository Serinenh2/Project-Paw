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

// Vérifier l'ID de la session
if (!isset($_GET['id'])) {
    die("Session ID missing.");
}
$sessionId = (int)$_GET['id'];

// Récupérer les infos de la session
$stmt = $pdo->prepare("
    SELECT s.id, c.name AS course, g.name AS grp, s.date, s.status
    FROM attendance_sessions s
    LEFT JOIN courses c ON c.id = s.course_id
    LEFT JOIN student_groups g ON g.id = s.group_id
    WHERE s.id = ? AND s.opened_by = ?
");
$stmt->execute([$sessionId, $_SESSION["user_id"]]);
$session = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$session) {
    die("Session not found or not yours.");
}

// Récupérer les étudiants et leur statut
$stmt = $pdo->prepare("
    SELECT st.fullname, st.matricule, ar.status
    FROM students st
    INNER JOIN attendance_records ar ON ar.student_id = st.id
    WHERE ar.session_id = ?
");
$stmt->execute([$sessionId]);
$records = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>View Session - Professor Panel</title>
  <style>
    body { font-family: 'Segoe UI', sans-serif; background: #f4f6f9; display: flex; min-height: 100vh; margin:0; }
    .sidebar { width: 220px; background: #2c3e50; color: #fff; padding: 20px; display: flex; flex-direction: column; justify-content: space-between; }
    .sidebar h2 { margin-bottom: 30px; text-align: center; }
    .sidebar a { display: block; color: #fff; text-decoration: none; padding: 12px; border-radius: 6px; margin-bottom: 10px; transition: background 0.3s; }
    .sidebar a:hover { background: #34495e; }
    .logout { margin-top: 20px; text-align: center; }
    .logout a { color: #fff; text-decoration: none; background: #e74c3c; padding: 10px 15px; border-radius: 6px; transition: background 0.3s; }
    .logout a:hover { background: #c0392b; }
    .main { flex: 1; padding: 30px; }
    .header { margin-bottom: 30px; }
    .header h1 { color: #333; }
    .info { background:#fff; padding:20px; border-radius:12px; box-shadow:0 4px 12px rgba(0,0,0,0.1); margin-bottom:20px; }
    .table-wrap { background: #fff; border-radius: 12px; box-shadow: 0 4px 12px rgba(0,0,0,0.1); overflow: hidden; }
    table { width: 100%; border-collapse: collapse; }
    th, td { padding: 14px; text-align: left; }
    th { background: #2c3e50; color: #fff; }
    tr:nth-child(even) { background: #f9f9f9; }
    tr:hover { background: #eef2ff; }
    .status-badge { padding:6px 10px; border-radius:16px; font-size:12px; font-weight:700; }
    .present { background:#eaf7ef; color:#2ecc71; border:1px solid #2ecc71; }
    .absent { background:#fdecec; color:#e74c3c; border:1px solid #e74c3c; }
    .late { background:#fff3e0; color:#f39c12; border:1px solid #f39c12; }
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
      <h1>View Session #<?= htmlspecialchars($session['id']) ?></h1>
    </div>

    <div class="info">
      <p><strong>Course:</strong> <?= htmlspecialchars($session['course']) ?></p>
      <p><strong>Group:</strong> <?= htmlspecialchars($session['grp']) ?></p>
      <p><strong>Date:</strong> <?= htmlspecialchars($session['date']) ?></p>
      <p><strong>Status:</strong> <?= ucfirst(htmlspecialchars($session['status'])) ?></p>
    </div>

    <div class="table-wrap">
      <table>
        <thead>
          <tr>
            <th>Matricule</th>
            <th>Fullname</th>
            <th>Status</th>
          </tr>
        </thead>
        <tbody>
          <?php if (empty($records)): ?>
            <tr><td colspan="3">No attendance records yet.</td></tr>
          <?php else: ?>
            <?php foreach ($records as $r): ?>
              <tr>
                <td><?= htmlspecialchars($r['matricule']) ?></td>
                <td><?= htmlspecialchars($r['fullname']) ?></td>
                <td>
                  <span class="status-badge <?= strtolower($r['status']) ?>">
                    <?= ucfirst($r['status']) ?>
                  </span>
                </td>
              </tr>
            <?php endforeach; ?>
          <?php endif; ?>
        </tbody>
      </table>
    </div>
  </div>
</body>
</html>
