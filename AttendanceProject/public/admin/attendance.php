<?php
session_start();

// Vérification de l'accès
if (!isset($_SESSION["role"]) || $_SESSION["role"] !== "admin") {
    header("Location: ../login.php");
    exit;
}

$dsn = "mysql:host=127.0.0.1;dbname=attendance_db;charset=utf8";
$dbUser = "root";
$dbPass = "";

try {
    $pdo = new PDO($dsn, $dbUser, $dbPass, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
    ]);
} catch (PDOException $e) {
    die("Erreur de connexion : " . htmlspecialchars($e->getMessage()));
}

// Récupérer tous les enregistrements d'assiduité
$sql = "
    SELECT ar.id,
           st.fullname AS student,
           st.matricule,
           c.name AS course,
           g.name AS grp,
           s.date,
           ar.status,
           u.fullname AS professor
    FROM attendance_records ar
    INNER JOIN students st ON st.id = ar.student_id
    INNER JOIN attendance_sessions s ON s.id = ar.session_id
    INNER JOIN courses c ON c.id = s.course_id
    INNER JOIN student_groups g ON g.id = s.group_id
    INNER JOIN users u ON u.id = s.opened_by
    ORDER BY s.date DESC
";
$stmt = $pdo->query($sql);
$records = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Calculer les totaux
$totalPresents = 0;
$totalAbsents = 0;
$totalLates = 0;
foreach ($records as $r) {
    if ($r['status'] === 'present') $totalPresents++;
    if ($r['status'] === 'absent')  $totalAbsents++;
    if ($r['status'] === 'late')    $totalLates++;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Attendance - Admin Panel</title>
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
  <style>
    body { font-family: 'Segoe UI', sans-serif; background:#f4f6f9; margin:0; padding:30px; }
    h1 { color:#2c3e50; margin-bottom:20px; }
    .table-wrap { background:#fff; border-radius:12px; box-shadow:0 4px 12px rgba(0,0,0,0.1); overflow:hidden; margin-bottom:30px; }
    table { width:100%; border-collapse:collapse; }
    th, td { padding:14px; text-align:left; }
    th { background:#2c3e50; color:#fff; }
    tr:nth-child(even) { background:#f9f9f9; }
    tr:hover { background:#eef2ff; }
    .status-badge { padding:6px 10px; border-radius:16px; font-size:12px; font-weight:700; }
    .present { background:#eaf7ef; color:#2ecc71; border:1px solid #2ecc71; }
    .absent { background:#fdecec; color:#e74c3c; border:1px solid #e74c3c; }
    .late { background:#fff3e0; color:#f39c12; border:1px solid #f39c12; }
    .empty { background:#fff; padding:24px; border-radius:12px; box-shadow:0 4px 12px rgba(0,0,0,0.08); color:#555; }
    .chart-container { width:600px; margin:0 auto; background:#fff; padding:20px; border-radius:12px; box-shadow:0 4px 12px rgba(0,0,0,0.1); }
  </style>
</head>
<body>
  <h1>Attendance Records</h1>

  <?php if (empty($records)): ?>
    <div class="empty">No attendance records found.</div>
  <?php else: ?>
    <div class="table-wrap">
      <table>
        <thead>
          <tr>
            <th>Student</th>
            <th>Matricule</th>
            <th>Course</th>
            <th>Group</th>
            <th>Date</th>
            <th>Status</th>
            <th>Professor</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($records as $r): ?>
            <tr>
              <td><?= htmlspecialchars($r['student']) ?></td>
              <td><?= htmlspecialchars($r['matricule']) ?></td>
              <td><?= htmlspecialchars($r['course']) ?></td>
              <td><?= htmlspecialchars($r['grp']) ?></td>
              <td><?= htmlspecialchars($r['date']) ?></td>
              <td><span class="status-badge <?= strtolower($r['status']) ?>"><?= ucfirst($r['status']) ?></span></td>
              <td><?= htmlspecialchars($r['professor']) ?></td>
            </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    </div>

    <!-- Chart -->
    <div class="chart-container">
      <canvas id="attendanceChart"></canvas>
    </div>

    <script>
      const ctx = document.getElementById('attendanceChart').getContext('2d');
      const attendanceChart = new Chart(ctx, {
        type: 'pie',
        data: {
          labels: ['Presents', 'Absences', 'Lates'],
          datasets: [{
            data: [<?= $totalPresents ?>, <?= $totalAbsents ?>, <?= $totalLates ?>],
            backgroundColor: [
              'rgba(46, 204, 113, 0.7)', // green
              'rgba(231, 76, 60, 0.7)',  // red
              'rgba(243, 156, 18, 0.7)'  // orange
            ],
            borderColor: [
              'rgba(46, 204, 113, 1)',
              'rgba(231, 76, 60, 1)',
              'rgba(243, 156, 18, 1)'
            ],
            borderWidth: 1
          }]
        },
        options: {
          responsive: true,
          plugins: {
            legend: { position: 'bottom' },
            title: { display: true, text: 'Global Attendance Distribution' }
          }
        }
      });
    </script>
  <?php endif; ?>
</body>
</html>



