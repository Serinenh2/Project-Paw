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

// Récupérer les absences de l'étudiant
$sql = "
    SELECT ar.id, c.name AS course, s.date, ar.status
    FROM attendance_records ar
    INNER JOIN attendance_sessions s ON s.id = ar.session_id
    INNER JOIN courses c ON c.id = s.course_id
    WHERE ar.student_id = ? AND ar.status = 'absent'
    ORDER BY s.date DESC
";
$stmt = $pdo->prepare($sql);
$stmt->execute([$_SESSION["user_id"]]);
$absences = $stmt->fetchAll(PDO::FETCH_ASSOC);

$message = "";
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $absence_id = $_POST["absence_id"];
    $reason = $_POST["reason"];

    $stmt = $pdo->prepare("
        INSERT INTO justifications (absence_id, student_id, reason, submitted_at)
        VALUES (?, ?, ?, NOW())
    ");
    $stmt->execute([$absence_id, $_SESSION["user_id"], $reason]);

    $message = "Justification submitted successfully!";
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Submit Justification - Student Panel</title>
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
    form { background:#fff; padding:20px; border-radius:12px; box-shadow:0 4px 12px rgba(0,0,0,0.1); max-width:500px; }
    label { display:block; margin-bottom:8px; font-weight:600; }
    select, textarea { width:100%; padding:10px; margin-bottom:20px; border:1px solid #ccc; border-radius:6px; }
    textarea { height:100px; }
    .btn { padding:10px 14px; border-radius:8px; border:none; cursor:pointer; font-weight:600; }
    .btn-primary { background:#16a085; color:#fff; }
    .btn-primary:hover { filter:brightness(0.95); }
    .message { margin:15px 0; color:green; font-weight:bold; }
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
      <h1>Submit Justification</h1>
      <p>📅 <?= date("d M Y") ?></p>
    </div>

    <?php if (!empty($message)): ?>
      <div class="message"><?= htmlspecialchars($message) ?></div>
    <?php endif; ?>

    <?php if (empty($absences)): ?>
      <div class="empty">You have no absences to justify.</div>
    <?php else: ?>
      <form method="post">
        <label for="absence_id">Select Absence</label>
        <select name="absence_id" id="absence_id" required>
          <option value="">-- Choose an absence --</option>
          <?php foreach ($absences as $a): ?>
            <option value="<?= $a['id'] ?>">
              <?= htmlspecialchars($a['course']) ?> - <?= htmlspecialchars($a['date']) ?>
            </option>
          <?php endforeach; ?>
        </select>

        <label for="reason">Reason</label>
        <textarea name="reason" id="reason" required></textarea>

        <button type="submit" class="btn btn-primary">Submit</button>
      </form>
    <?php endif; ?>
  </div>
</body>
</html>
