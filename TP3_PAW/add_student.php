<?php
if ($_POST) {
    $file = "data/students.json";
    $students = file_exists($file) ? json_decode(file_get_contents($file), true) : [];
    $students[] = [
        "student_id" => $_POST['student_id'],
        "name"       => $_POST['name'],
        "group"      => $_POST['group']
    ];
    file_put_contents($file, json_encode($students, JSON_PRETTY_PRINT));
    $success = "✔ Student Added!";
}
?>
<!DOCTYPE html>
<html>
<head> <title>Add Student</title> <!-- GLOBAL STYLE --> 
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
<div class="container mt-5">
    <div class="col-md-6 mx-auto bg-white p-5 shadow rounded-4">
        <h2 class="text-center fw-bold text-primary mb-4">➕ Add Student</h2>
        <?php if(isset($success)) echo "<div class='alert alert-success'>$success</div>"; ?>
        <form method="POST">
            <input name="student_id" class="form-control mb-3" placeholder="Student ID" required>
            <input name="name" class="form-control mb-3" placeholder="Full Name" required>
            <input name="group" class="form-control mb-3" placeholder="Group" required>
            <button class="btn btn-primary w-100">Save</button>
        </form>
        <a href="index.php" class="btn btn-outline-secondary mt-3 w-100">← Back</a>
    </div>
</div>
</body>
</html>

