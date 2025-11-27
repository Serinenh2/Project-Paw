<?php
session_start();

// Vérification de l'accès
if (!isset($_SESSION["role"]) || $_SESSION["role"] !== "admin") {
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

// Récupérer les absences par étudiant
$sql = "
    SELECT st.fullname AS student,
           st.matricule,
           c.name AS course,
           COUNT(ar.id) AS absences
    FROM attendance_records ar
    INNER JOIN students st ON st.id = ar.student_id
    INNER JOIN attendance_sessions s ON s.id = ar.session_id
    INNER JOIN courses c ON c.id = s.course_id
    WHERE ar.status = 'absent'
    GROUP BY st.id, c.id
    ORDER BY absences DESC
";
$stmt = $pdo->query($sql);
$reports = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Reports - Admin Panel</title>
  <style>
    * { margin: 0; padding: 0; box-sizing: border-box; }
    body { font-family: 'Segoe UI', sans-serif; background: #f4f6f9; display: flex; min-height: 100vh; }

    .sidebar { width: 220px; background: #4e54c8; color: #fff; padding: 20px; display: flex; flex-direction: column; justify-content: space-between; }
    .sidebar h2 { margin-bottom: 30px; text-align: center; }
    .sidebar a { display: block; color: #fff; text-decoration: none; padding: 12px; border-radius: 6px; margin-bottom: 10px; transition: background 0.3s; }
    .sidebar a:hover { background: #3b3fc1; }
    .logout { margin-top: 20px; text-align: center; }
    .logout a { color: #fff; text-decoration: none; background: #e74c3c; padding: 10px 15px; border-radius: 6px; transition: background 0.3s; }
    .logout a:hover { background: #c0392b; }

    .main { flex: 1; padding: 30px; }
    .header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 30px; }
    .header h1 { color: #333; }

    .table-wrap { background: #fff; border-radius: 12px; box-shadow: 0 4px 12px rgba(0,0,0,0.1); overflow: hidden; }
    table { width: 100%; border-collapse: collapse; }
    th, td { padding: 14px; text-align: left; }
    th { background: #4e54c8; color: #fff; }
    tr:nth-child(even) { background: #f9f9f9; }
    tr:hover { background: #eef2ff; }

    .highlight { font-weight: bold; color: #e74c3c; }
    .empty { background: #fff; padding: 24px; border-radius: 12px; box-shadow: 0 4px 12px rgba(0,0,0,0.08); color: #555; }
  </style>
</head>
<body>
  <!-- Sidebar -->
  <div class="sidebar">
    <div>
      <h2>Admin Panel</h2>
      <a href="dashboard.php">🏠 Dashboard</a>
      <a href="students.php">👨‍🎓 Manage Students</a>
      <a href="professors.php">👨‍🏫 Manage Professors</a>
      <a href="courses.php">📚 Manage Courses</a>
      <a href="attendance.php">📝 Attendance</a>
      <a href="reports.php">📊 Reports</a>
    </div>
    <div class="logout">
      <a href="../logout.php">Logout</a>
    </div>
  </div>

  <!-- Main -->
  <div class="main">
    <div class="header">
      <h1>Attendance Reports</h1>
      <p>📅 <?= date("d M Y") ?></p>
    </div>

    <?php if (empty($reports)): ?>
      <div class="empty">No absence records found.</div>
    <?php else: ?>
      <div class="table-wrap">
        <table>
          <thead>
            <tr>
              <th>Student</th>
              <th>Matricule</th>
              <th>Course</th>
              <th>Absences</th>
            </tr>
          </thead>
          <tbody>
            <?php foreach ($reports as $r): ?>
              <tr>
                <td><?= htmlspecialchars($r['student']) ?></td>
                <td><?= htmlspecialchars($r['matricule']) ?></td>
                <td><?= htmlspecialchars($r['course']) ?></td>
                <td class="<?= $r['absences'] >= 3 ? 'highlight' : '' ?>">
                  <?= htmlspecialchars($r['absences']) ?>
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
