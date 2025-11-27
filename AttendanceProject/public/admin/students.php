<?php
session_start();

// Vérification de l'accès
if (!isset($_SESSION["role"]) || $_SESSION["role"] !== "admin") {
    header("Location: ../login.php");
    exit;
}

// Connexion à la base
$host = "127.0.0.1";
$dbname = "attendance_db";
$user = "root";
$pass = ""; // mets ton mot de passe MySQL si nécessaire

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Récupérer les étudiants avec leur groupe
    $stmt = $pdo->query("
        SELECT students.id, students.fullname, students.matricule, student_groups.name AS grp
        FROM students
        LEFT JOIN student_groups ON student_groups.id = students.group_id
    ");
    $students = $stmt->fetchAll(PDO::FETCH_ASSOC);

} catch (PDOException $e) {
    die("Erreur : " . $e->getMessage());
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Manage Students - Admin Panel</title>
  <style>
    * { margin: 0; padding: 0; box-sizing: border-box; }
    body { font-family: 'Segoe UI', sans-serif; background: #f4f6f9; display: flex; min-height: 100vh; }

    /* Sidebar */
    .sidebar { width: 220px; background: #4e54c8; color: #fff; padding: 20px; display: flex; flex-direction: column; justify-content: space-between; }
    .sidebar h2 { margin-bottom: 30px; text-align: center; }
    .sidebar a { display: block; color: #fff; text-decoration: none; padding: 12px; border-radius: 6px; margin-bottom: 10px; transition: background 0.3s; }
    .sidebar a:hover { background: #3b3fc1; }

    /* Main */
    .main { flex: 1; padding: 30px; }
    .header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 30px; }
    .header h1 { color: #333; }

    /* Table */
    table { width: 100%; border-collapse: collapse; background: #fff; border-radius: 12px; overflow: hidden; box-shadow: 0 4px 12px rgba(0,0,0,0.1); }
    th, td { padding: 14px; text-align: left; }
    th { background: #4e54c8; color: #fff; }
    tr:nth-child(even) { background: #f9f9f9; }
    tr:hover { background: #eef2ff; }

    .actions a { margin-right: 10px; text-decoration: none; padding: 6px 10px; border-radius: 6px; font-size: 14px; }
    .edit { background: #3498db; color: #fff; }
    .edit:hover { background: #2980b9; }
    .delete { background: #e74c3c; color: #fff; }
    .delete:hover { background: #c0392b; }

    .logout { margin-top: 20px; text-align: center; }
    .logout a { color: #fff; text-decoration: none; background: #e74c3c; padding: 10px 15px; border-radius: 6px; transition: background 0.3s; }
    .logout a:hover { background: #c0392b; }
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

  <!-- Main content -->
  <div class="main">
    <div class="header">
      <h1>Manage Students</h1>
      <p>📅 <?= date("d M Y") ?></p>
    </div>

    <table>
      <thead>
        <tr>
          <th>ID</th>
          <th>Fullname</th>
          <th>Matricule</th>
          <th>Group</th>
          <th>Actions</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($students as $s): ?>
          <tr>
            <td><?= htmlspecialchars($s['id']) ?></td>
            <td><?= htmlspecialchars($s['fullname']) ?></td>
            <td><?= htmlspecialchars($s['matricule']) ?></td>
            <td><?= htmlspecialchars($s['grp'] ?? '—') ?></td>
            <td class="actions">
              <a href="edit_student.php?id=<?= $s['id'] ?>" class="edit">Edit</a>
              <a href="delete_student.php?id=<?= $s['id'] ?>" class="delete" onclick="return confirm('Delete this student?')">Delete</a>
            </td>
          </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  </div>
</body>
</html>

