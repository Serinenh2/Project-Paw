<?php
include "config/db_connect.php";
$conn = connectDB();

$status = $conn ? "success" : "failed";
$message = $conn ? "✔ Database Connection Successful!" : "❌ Failed to Connect to Database!";
$color = $conn ? "success" : "danger";
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Test DB Connection</title>

    <!-- BOOTSTRAP -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- GOOGLE FONT -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;500;600&display=swap" rel="stylesheet">

    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background: linear-gradient(135deg, #d8e9ff, #f0f3f5);
            height: 100vh;
        }
        .card {
            border-radius: 1.5rem;
            transition: 0.3s;
        }
        .card:hover {
            transform: translateY(-5px);
        }
        .status-icon {
            font-size: 4rem;
        }
    </style>
</head>
<body>

<div class="container d-flex justify-content-center align-items-center vh-100">
    <div class="card shadow p-5 text-center" style="max-width: 450px;">
        <h3 class="fw-bold mb-4">🛠 Test Database Connection</h3>

        <?php if($status == 'success'): ?>
            <div class="text-success status-icon">✔</div>
        <?php else: ?>
            <div class="text-danger status-icon">✖</div>
        <?php endif; ?>

        <div class="alert alert-<?= $color ?> mt-4">
            <?= $message ?>
        </div>

        <a href="index.php" class="btn btn-outline-secondary mt-3 w-100">← Back to Dashboard</a>
    </div>
</div>

</body>
</html>


