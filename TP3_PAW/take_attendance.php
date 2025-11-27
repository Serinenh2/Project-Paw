<?php
$file = "data/students.json";
$students = file_exists($file) ? json_decode(file_get_contents($file), true) : [];
$date = date("Y-m-d");
$att_file = "attendance/attendance_$date.json";

if ($_POST) {
    if (file_exists($att_file)) $error = "⚠ Already taken!";
    else {
        foreach ($_POST['status'] as $id => $status)
            $data[] = ["student_id" => $id, "status" => $status];
        file_put_contents($att_file, json_encode($data, JSON_PRETTY_PRINT));
        $success = "✔ Saved!";
    }
}
?>
<!DOCTYPE html>
<html>
<head> <title>Attendance</title> <!-- GLOBAL STYLE -->
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
    <h3 class="fw-bold mb-4">📋 Attendance — <?= $date ?></h3>
    <?php if(isset($error)) echo "<div class='alert alert-danger'>$error</div>"; ?>
    <?php if(isset($success)) echo "<div class='alert alert-success'>$success</div>"; ?>

    <form method="POST">
        <table class="table table-hover">
            <tr> <th>Name</th> <th>Status</th> </tr>
            <?php foreach ($students as $s): ?>
            <tr>
                <td><?= $s['name'] ?> <small class="text-muted">(<?= $s['student_id'] ?>)</small></td>
                <td>
                    <select name="status[<?= $s['student_id'] ?>]" class="form-select">
                        <option value="present">Present</option>
                        <option value="absent">Absent</option>
                    </select>
                </td>
            </tr>
            <?php endforeach; ?>
        </table>
        <button class="btn btn-success w-100">Save</button>
    </form>
    <a href="index.php" class="btn btn-outline-secondary mt-3 w-100">← Back</a>
</div>
</body>
</html>
