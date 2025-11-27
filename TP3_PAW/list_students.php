<?php
include "config/db_connect.php";
$conn = connectDB();
$students = $conn->query("SELECT * FROM students");
?>
<!DOCTYPE html>
<html>
<head><title>Students</title><!-- GLOBAL STYLE -->
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
<div class="container mt-5 bg-white p-5 shadow rounded-4">
    <h3 class="fw-bold">👨‍🎓 Students</h3>
    <table class="table table-hover mt-3">
        <tr><th>ID</th><th>Name</th><th>Matricule</th><th>Group</th><th>Action</th></tr>
        <?php foreach($students as $s): ?>
        <tr>
            <td><?=$s['id']?></td>
            <td><?=$s['fullname']?></td>
            <td><?=$s['matricule']?></td>
            <td><?=$s['group_id']?></td>
            <td>
                <a href="update_student.php?id=<?=$s['id']?>" class="btn btn-warning btn-sm">Edit</a>
                <a href="delete_student.php?id=<?=$s['id']?>" class="btn btn-danger btn-sm">Delete</a>
            </td>
        </tr>
        <?php endforeach; ?>
    </table>
    <a href="index.php" class="btn btn-outline-secondary w-100 mt-3">← Back</a>
</div>
</body>
</html>

