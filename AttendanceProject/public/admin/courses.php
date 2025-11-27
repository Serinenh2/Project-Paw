<?php
session_start();
if (!isset($_SESSION["role"]) || $_SESSION["role"] !== "admin") {
    header("Location: ../login.php");
    exit;
}

$pdo = new PDO("mysql:host=127.0.0.1;dbname=attendance_db;charset=utf8", "root", "");
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

$stmt = $pdo->query("SELECT id, name, code FROM courses");
$courses = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Manage Courses</title>
  <style>
    body{font-family:'Segoe UI';background:#f4f6f9;display:flex;min-height:100vh;}
    .sidebar{width:220px;background:#4e54c8;color:#fff;padding:20px;}
    .sidebar a{display:block;color:#fff;text-decoration:none;padding:12px;border-radius:6px;margin-bottom:10px;}
    .sidebar a:hover{background:#3b3fc1;}
    .main{flex:1;padding:30px;}
    table{width:100%;border-collapse:collapse;background:#fff;border-radius:12px;box-shadow:0 4px 12px rgba(0,0,0,0.1);}
    th,td{padding:14px;text-align:left;}
    th{background:#4e54c8;color:#fff;}
    tr:nth-child(even){background:#f9f9f9;}
    tr:hover{background:#eef2ff;}
    .actions a{padding:6px 10px;border-radius:6px;font-size:14px;text-decoration:none;margin-right:8px;}
    .edit{background:#3498db;color:#fff;} .edit:hover{background:#2980b9;}
    .delete{background:#e74c3c;color:#fff;} .delete:hover{background:#c0392b;}
  </style>
</head>
<body>
  <div class="sidebar">
    <h2>Admin Panel</h2>
    <a href="dashboard.php">🏠 Dashboard</a>
    <a href="students.php">👨‍🎓 Manage Students</a>
    <a href="professors.php">👨‍🏫 Manage Professors</a>
    <a href="courses.php">📚 Manage Courses</a>
    <a href="attendance.php">📝 Attendance</a>
    <a href="reports.php">📊 Reports</a>
  </div>
  <div class="main">
    <h1>Manage Courses</h1>
    <table>
      <thead><tr><th>ID</th><th>Name</th><th>Code</th><th>Actions</th></tr></thead>
      <tbody>
        <?php foreach($courses as $c): ?>
          <tr>
            <td><?= $c['id'] ?></td>
            <td><?= htmlspecialchars($c['name']) ?></td>
            <td><?= htmlspecialchars($c['code']) ?></td>
            <td class="actions">
              <a href="edit_course.php?id=<?= $c['id'] ?>" class="edit">Edit</a>
              <a href="delete_course.php?id=<?= $c['id'] ?>" class="delete" onclick="return confirm('Delete this course?')">Delete</a>
            </td>
          </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  </div>
</body>
</html>
