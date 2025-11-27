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

// Récupérer les cours et groupes
$courses = $pdo->query("SELECT id, name FROM courses")->fetchAll(PDO::FETCH_ASSOC);
$groups = $pdo->query("SELECT id, name FROM student_groups")->fetchAll(PDO::FETCH_ASSOC);

$message = "";
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $course_id = $_POST["course_id"];
    $group_id = $_POST["group_id"];
    $date = $_POST["date"];
    $status = $_POST["status"];

    $stmt = $pdo->prepare("
        INSERT INTO attendance_sessions (course_id, group_id, date, opened_by, status)
        VALUES (?, ?, ?, ?, ?)
    ");
    $stmt->execute([$course_id, $group_id, $date, $_SESSION["user_id"], $status]);

    $message = "Session created successfully!";
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Create Session - Professor Panel</title>
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
    form { background:#fff; padding:20px; border-radius:12px; box-shadow:0 4px 12px rgba(0,0,0,0.1); max-width:500px; }
    label { display:block; margin-bottom:8px; font-weight:600; }
    select, input[type="date"] { width:100%; padding:10px; margin-bottom:20px; border:1px solid #ccc; border-radius:6px; }
    .btn { padding:10px 14px; border-radius:8px; border:none; cursor:pointer; font-weight:600; }
    .btn-primary { background:#2c3e50; color:#fff; }
    .btn-primary:hover { filter:brightness(0.95); }
    .message { margin:15px 0; color:green; font-weight:bold; }
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
      <h1>Create Session</h1>
      <p>📅 <?= date("d M Y") ?></p>
    </div>

    <?php if (!empty($message)): ?>
      <div class="message"><?= htmlspecialchars($message) ?></div>
    <?php endif; ?>

    <form method="post">
      <label for="course_id">Course</label>
      <select name="course_id" id="course_id" required>
        <option value="">-- Select Course --</option>
        <?php foreach ($courses as $c): ?>
          <option value="<?= $c['id'] ?>"><?= htmlspecialchars($c['name']) ?></option>
        <?php endforeach; ?>
      </select>

      <label for="group_id">Group</label>
      <select name="group_id" id="group_id" required>
        <option value="">-- Select Group --</option>
        <?php foreach ($groups as $g): ?>
          <option value="<?= $g['id'] ?>"><?= htmlspecialchars($g['name']) ?></option>
        <?php endforeach; ?>
      </select>

      <label for="date">Date</label>
      <input type="date" name="date" id="date" required>

      <label for="status">Status</label>
      <select name="status" id="status" required>
        <option value="open">Open</option>
        <option value="closed">Closed</option>
      </select>

      <button type="submit" class="btn btn-primary">Create Session</button>
    </form>
  </div>
</body>
</html>
