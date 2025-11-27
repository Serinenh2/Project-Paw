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

// Récupérer les sessions du professeur
$stmt = $pdo->prepare("
    SELECT s.id, c.name AS course, g.name AS grp, s.date
    FROM attendance_sessions s
    LEFT JOIN courses c ON c.id = s.course_id
    LEFT JOIN student_groups g ON g.id = s.group_id
    WHERE s.opened_by = ?
    ORDER BY s.date DESC
");
$stmt->execute([$_SESSION["user_id"]]);
$sessions = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Si une session est choisie
$students = [];
if (isset($_GET['session_id'])) {
    $sessionId = (int)$_GET['session_id'];
    $stmt = $pdo->prepare("
        SELECT st.id, st.fullname, st.matricule
        FROM students st
        INNER JOIN student_groups g ON g.id = st.group_id
        INNER JOIN attendance_sessions s ON s.group_id = g.id
        WHERE s.id = ?
    ");
    $stmt->execute([$sessionId]);
    $students = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Enregistrement des présences
    if ($_SERVER["REQUEST_METHOD"] === "POST") {
        foreach ($students as $st) {
            $status = $_POST['status'][$st['id']] ?? 'absent';
            $stmt = $pdo->prepare("
                INSERT INTO attendance_records (session_id, student_id, status)
                VALUES (?, ?, ?)
                ON DUPLICATE KEY UPDATE status = VALUES(status)
            ");
            $stmt->execute([$sessionId, $st['id'], $status]);
        }
        $message = "Attendance saved successfully!";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Mark Attendance - Professor Panel</title>
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
    .header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 30px; }
    .header h1 { color: #333; }
    .session-select { margin-bottom: 20px; }
    select { padding: 10px; border-radius: 6px; border: 1px solid #ccc; }
    table { width: 100%; border-collapse: collapse; background: #fff; border-radius: 12px; box-shadow: 0 4px 12px rgba(0,0,0,0.1); overflow: hidden; }
    th, td { padding: 14px; text-align: left; }
    th { background: #2c3e50; color: #fff; }
    tr:nth-child(even) { background: #f9f9f9; }
    tr:hover { background: #eef2ff; }
    .btn { padding: 10px 14px; border-radius: 8px; border: none; cursor: pointer; font-weight: 600; }
    .btn-primary { background: #2c3e50; color: #fff; }
    .btn-primary:hover { filter: brightness(0.95); }
    .message { margin: 15px 0; color: green; font-weight: bold; }
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
      <h1>Mark Attendance</h1>
      <p>📅 <?= date("d M Y") ?></p>
    </div>

    <div class="session-select">
      <form method="get" action="attendance.php">
        <label for="session_id">Select Session:</label>
        <select name="session_id" id="session_id" required>
          <option value="">-- Choose a session --</option>
          <?php foreach ($sessions as $s): ?>
            <option value="<?= $s['id'] ?>" <?= isset($sessionId) && $sessionId == $s['id'] ? 'selected' : '' ?>>
              <?= htmlspecialchars($s['course']) ?> - <?= htmlspecialchars($s['grp']) ?> (<?= $s['date'] ?>)
            </option>
          <?php endforeach; ?>
        </select>
        <button type="submit" class="btn btn-primary">Load</button>
      </form>
    </div>

    <?php if (!empty($students)): ?>
      <?php if (!empty($message)): ?>
        <div class="message"><?= htmlspecialchars($message) ?></div>
      <?php endif; ?>
      <form method="post">
        <table>
          <thead>
            <tr>
              <th>Matricule</th>
              <th>Fullname</th>
              <th>Status</th>
            </tr>
          </thead>
          <tbody>
            <?php foreach ($students as $st): ?>
              <tr>
                <td><?= htmlspecialchars($st['matricule']) ?></td>
                <td><?= htmlspecialchars($st['fullname']) ?></td>
                <td>
                  <select name="status[<?= $st['id'] ?>]">
                    <option value="present">Present</option>
                    <option value="absent">Absent</option>
                    <option value="late">Late</option>
                  </select>
                </td>
              </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
        <br>
        <button type="submit" class="btn btn-primary">Save Attendance</button>
      </form>
    <?php endif; ?>
  </div>
</body>
</html>
