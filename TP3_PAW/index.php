<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>AWP Dashboard</title>
    <!-- GLOBAL STYLE HERE -->
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

<nav class="navbar navbar-dark bg-dark px-4">
  <a class="navbar-brand fw-bold">📚 AWP · Student System</a>
</nav>

<div class="container mt-5">
    <h2 class="text-center mb-4 fw-bold">Dashboard</h2>

    <div class="row g-4">
        <div class="col-md-4">
            <a href="add_student.php" class="text-decoration-none">
                <div class="card p-4 card-btn text-center shadow rounded-4">
                    <h4>➕ Add Student</h4>
                    <p class="text-muted">Save to JSON</p>
                </div>
            </a>
        </div>
        <div class="col-md-4">
            <a href="take_attendance.php" class="text-decoration-none">
                <div class="card p-4 card-btn text-center shadow rounded-4">
                    <h4>📋 Attendance</h4>
                </div>
            </a>
        </div>
        <div class="col-md-4">
            <a href="list_students.php" class="text-decoration-none">
                <div class="card p-4 card-btn text-center shadow rounded-4">
                    <h4>👨‍🎓 Students (DB)</h4>
                </div>
            </a>
        </div>
        <div class="col-md-4">
            <a href="create_session.php" class="text-decoration-none">
                <div class="card p-4 card-btn text-center shadow rounded-4">
                    <h4>📝 Create Session</h4>
                </div>
            </a>
        </div>
        <div class="col-md-4">
            <a href="test_connection.php" class="text-decoration-none">
                <div class="card p-4 card-btn text-center shadow rounded-4">
                    <h4>🛠 Test DB</h4>
                </div>
            </a>
        </div>
    </div>
</div>
</body>
</html>
