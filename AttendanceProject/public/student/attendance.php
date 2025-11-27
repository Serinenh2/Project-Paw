<?php
session_start();

// Vérification de l'accès
if (!isset($_SESSION["role"]) || $_SESSION["role"] !== "student") {
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

// Récupérer les présences de l'étudiant
$sql = "
    SELECT c.name AS course,
           s.date,
           ar.status
    FROM attendance_records ar
    INNER JOIN attendance_sessions s ON s.id = ar.session_id
    INNER JOIN courses c ON c.id = s.course_id
    WHERE ar.student_id = ?
    ORDER BY s.date DESC
";
$stmt = $pdo->prepare($sql);
$stmt->execute([$_SESSION["user_id"]]);
$records = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Calculer les stats
$total = count($records);
$presents = count(array_filter($records, fn($r) => $r['status'] === 'present'));
$absents = count(array_filter($records, fn($r) => $r['status'] === 'absent'));
$lates   = count(array_filter($records, fn($r) => $r['status'] === 'late'));
$rate = $total > 0 ? round(($presents / $total) * 100, 2) : 0;
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>My Attendance - Student Panel</title>
  <style>
    body { font-family: 'Segoe UI', sans-serif; background: #f4f6f9; display: flex; min-height: 100vh; margin:0; }
    .sidebar { width: 220px; background: #16a085; color: #fff; padding: 20px; display: flex; flex-direction: column; justify-content: space-between; }
    .sidebar h2 { margin-bottom: 30px; text-align: center; }
    .sidebar a { display: block; color: #fff; text-decoration: none; padding: 12px; border-radius: 6px; margin-bottom: 10px; transition: background 0.3s; }
    .sidebar a:hover { background: #1abc9c; }
    .logout { margin-top: 20px; text-align: center; }
    .logout a { color: #fff; text-decoration: none; background: #e74c3c; padding: 10px 15px; border-radius: 6px; transition: background 0.3s; }
    .logout a:hover { background: #c0392b; }
    .main { flex: 1; padding: 30px; }
    .header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 30px; }
    .header h1 { color: #333; }
    .cards { display: flex; gap: 20px; margin-bottom: 30px; }
    .card { flex:1; background:#fff; padding:20px; border-radius:12px; box-shadow:0 4px 12px rgba(0,0,0,0.1); text-align:center; }
    .card h3 { margin-bottom:10px; color:#16a085; }
    .card p { font-size:22px; font-weight:bold; }
    .table-wrap { background: #fff; border-radius: 12px; box-shadow: 0 4px 12px rgba(0,0,0,0.1); overflow: hidden; }
    table { width: 100%; border-collapse: collapse; }
    th, td { padding: 14px; text-align: left; }
    th { background: #16a085; color: #fff; }
    tr:nth-child(even) { background: #f9f9f9; }
    tr:hover { background: #eef2ff; }
    .status-badge { padding:6px 10px; border-radius:16px; font-size:12px; font-weight:700; }
    .present { background:#eaf7ef; color:#2ecc71; border:1px solid #2ecc71; }
    .absent { background:#fdecec; color:#e74c3c; border:1px solid #e74c3c; }
    .late { background:#fff3e0; color:#f39c12; border:1px solid #f39c12; }
    .empty { background:#fff; padding:24px; border-radius:12px; box-shadow:0 4px 12px rgba(0,0,0,0.08); color:#555; }
  </style>
</head>
<body>
  <!-- Sidebar -->
  <div class="sidebar">
    <div>
      <h2>Student Panel</h2>
      <a href="dashboard.php">🏠 Dashboard</a>
      <a href="attendance.php">📅 My Attendance</a>
      <a href="justifications.php">📝 Submit Justification</a>
      <a href="profile.php">👤 My Profile</a>
    </div>
    <div class="logout">
      <a href="../logout.php">Logout</a>
    </div>
  </div>

  <!-- Main -->
  <div class="main">
    <div class="header">
      <h1>My Attendance</h1>
      <p>📅 <?= date("d M Y") ?></p>
    </div>

    <div class="cards">
      <div class="card"><h3>Total Sessions</h3><p><?= $total ?></p></div>
      <div class="card"><h3>Presents</h3><p><?= $presents ?></p></div>
      <div class="card"><h3>Absences</h3><p><?= $absents ?></p></div>
      <div class="card"><h3>Lates</h3><p><?= $lates ?></p></div>
      <div class="card"><h3>Attendance Rate</h3><p><?= $rate ?>%</p></div>
    </div>

    <?php if (empty($records)): ?>
      <div class="empty">No attendance records found.</div>
    <?php else: ?>
      <div class="table-wrap">
        <table>
          <thead>
            <tr>
              <th>Course</th>
              <th>Date</th>
              <th>Status</th>
            </tr>
          </thead>
          <tbody>
            <?php foreach ($records as $r): ?>
              <tr>
                <td><?= htmlspecialchars($r['course']) ?></td>
                <td><?= htmlspecialchars($r['date']) ?></td>
                <td>
                  <span class="status-badge <?= strtolower($r['status']) ?>">
                    <?= ucfirst($r['status']) ?>
                  </span>
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
