<?php
session_start();

// Vérification de l'accès
if (!isset($_SESSION["role"]) || $_SESSION["role"] !== "student") {
    header("Location: ../login.php");
    exit;
}

// Ici tu peux récupérer les infos depuis la base si tu veux.
// Pour l'exemple, on affiche directement "Serine".
$studentName = " BOUTABA Serine nour el houda";
$studentEmail = "serine@student.uni.dz"; // tu peux remplacer par la vraie valeur
$studentMatricule = "STU-001";           // idem
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>My Profile - Student Panel</title>
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
    .profile-card { background:#fff; padding:30px; border-radius:12px; box-shadow:0 4px 12px rgba(0,0,0,0.1); max-width:500px; }
    .profile-card h2 { margin-bottom:20px; color:#16a085; }
    .profile-item { margin-bottom:15px; }
    .profile-item strong { display:inline-block; width:120px; color:#333; }
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
      <h1>My Profile</h1>
      <p>📅 <?= date("d M Y") ?></p>
    </div>

    <div class="profile-card">
      <h2>Student Information</h2>
      <div class="profile-item"><strong>Name:</strong> <?= htmlspecialchars($studentName) ?></div>
      <div class="profile-item"><strong>Email:</strong> <?= htmlspecialchars($studentEmail) ?></div>
      <div class="profile-item"><strong>Matricule:</strong> <?= htmlspecialchars($studentMatricule) ?></div>
    </div>
  </div>
</body>
</html>
