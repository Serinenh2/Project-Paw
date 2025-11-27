<?php
include "config/db_connect.php";
$conn = connectDB();

if ($_POST) {
    $stmt = $conn->prepare("INSERT INTO attendance_sessions(course_id, group_id, date, opened_by, status)
            VALUES (?, ?, CURDATE(), ?, 'open')");
    $stmt->execute([$_POST['course_id'], $_POST['group_id'], $_POST['opened_by']]);
    $id = $conn->lastInsertId();
    $success = "✔ Session Created! ID = $id";
}
?>
<!DOCTYPE html>
<html>
<head><title>Create Session</title><!-- GLOBAL STYLE -->
<!-- BOOTSTRAP -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

<!-- GOOGLE FONTS -->
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;500;600&display=swap" rel="stylesheet">

<!-- CUSTOM STYLE -->
<style>
    body {
        font-family: 'Poppins', sans-serif;
        background: #f5f5f5;
    }
    .card-btn { transition: 0.3s; }
    .card-btn:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 20px rgba(0, 0, 0, 0.2);
    }
    .rounded-4 { border-radius: 1.5rem; }
</style>

</head>
<body>
<div class="container mt-5 bg-white p-5 rounded-4 shadow">
    <h3 class="fw-bold mb-4">📝 Create Session</h3>
    <?php if(isset($success)) echo "<div class='alert alert-success'>$success</div>"; ?>
    <form method="POST">
        <input name="course_id" class="form-control mb-3" placeholder="Course ID">
        <input name="group_id" class="form-control mb-3" placeholder="Group ID">
        <input name="opened_by" class="form-control mb-3" placeholder="Professor ID">
        <button class="btn btn-warning w-100">Create</button>
    </form>
    <a href="index.php" class="btn btn-outline-secondary mt-3 w-100">← Back</a>
</div>
</body>
</html>

