<?php
session_start();

// Vérification de l'accès
if (!isset($_SESSION["role"]) || $_SESSION["role"] !== "student") {
    header("Location: ../login.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Student Dashboard - Attendance System</title>
  <style>
    * { margin: 0; padding: 0; box-sizing: border-box; }
    body { font-family: 'Segoe UI', sans-serif; background: #f4f6f9; display: flex; min-height: 100vh; }

    .sidebar { width: 220px; background: #16a085; color: #fff; padding: 20px; display: flex; flex-direction: column; justify-content: space-between; }
    .sidebar h2 { margin-bottom: 30px; text-align: center; }
    .sidebar a { display: block; color: #fff; text-decoration: none; padding: 12px; border-radius: 6px; margin-bottom: 10px; transition: background 0.3s; }
    .sidebar a:hover { background: #1abc9c; }

    .main { flex: 1; padding: 30px; }
    .header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 30px; }
    .header h1 { color: #333; }

    .card-container { display: grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap: 20px; }
    .card { background: #fff; padding: 20px; border-radius: 12px; box-shadow: 0 4px 12px rgba(0,0,0,0.1); text-align: center; transition: transform 0.3s; }
    .card:hover { transform: translateY(-5px); }
    .card h3 { margin-bottom: 10px; color: #16a085; }
    .card p { font-size: 24px; font-weight: bold; color: #333; }

    .logout { margin-top: 20px; text-align: center; }
    .logout a { color: #fff; text-decoration: none; background: #e74c3c; padding: 10px 15px; border-radius: 6px; transition: background 0.3s; }
    .logout a:hover { background: #c0392b; }
  </style>
</head>
<body>
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

  <div class="main">
    <div class="header">
      <h1>Welcome, Student</h1>
      <p>📅 <?= date("d M Y") ?></p>
    </div>

    <div class="card-container">
      <div class="card">
        <h3>Courses Enrolled</h3>
        <p>5</p>
      </div>
      <div class="card">
        <h3>Attendance Rate</h3>
        <p>92%</p>
      </div>
      <div class="card">
        <h3>Absences</h3>
        <p>3</p>
      </div>
      <div class="card">
        <h3>Pending Justifications</h3>
        <p>1</p>
      </div>
    </div>
  </div>
</body>
</html>
