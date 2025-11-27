<?php
session_start();

// Vérification de l'accès
if (!isset($_SESSION["role"]) || $_SESSION["role"] !== "admin") {
    header("Location: ../login.php");
    exit;
}

if (!isset($_GET['id'])) {
    die("Student ID missing.");
}
$studentId = (int)$_GET['id'];

$dsn = "mysql:host=127.0.0.1;dbname=attendance_db;charset=utf8";
$dbUser = "root";
$dbPass = "";

try {
    $pdo = new PDO($dsn, $dbUser, $dbPass, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
    ]);

    // Charger l'étudiant
    $stmt = $pdo->prepare("SELECT * FROM students WHERE id = ?");
    $stmt->execute([$studentId]);
    $student = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$student) {
        die("Student not found.");
    }

    $message = "";
    if ($_SERVER["REQUEST_METHOD"] === "POST") {
        $fullname = trim($_POST["fullname"]);
        $matricule = trim($_POST["matricule"]);
        $email = trim($_POST["email"]);
        $group_id = $_POST["group_id"];

        $stmt = $pdo->prepare("
            UPDATE students SET fullname = ?, matricule = ?, email = ?, group_id = ?
            WHERE id = ?
        ");
        $stmt->execute([$fullname, $matricule, $email, $group_id, $studentId]);

        $message = "✅ Student updated successfully!";
        // Recharger les données
        $stmt = $pdo->prepare("SELECT * FROM students WHERE id = ?");
        $stmt->execute([$studentId]);
        $student = $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Charger les groupes
    $groups = $pdo->query("SELECT id, name FROM student_groups")->fetchAll(PDO::FETCH_ASSOC);

} catch (PDOException $e) {
    die("Erreur : " . htmlspecialchars($e->getMessage()));
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Edit Student - Admin Panel</title>
  <style>
    body { font-family: 'Segoe UI', sans-serif; background:#f4f6f9; margin:0; padding:0; }
    .container { max-width:700px; margin:50px auto; background:#fff; padding:30px; border-radius:12px; box-shadow:0 6px 18px rgba(0,0,0,0.1); }
    h1 { margin-bottom:20px; color:#2c3e50; text-align:center; }
    form { display:flex; flex-direction:column; }
    label { margin-bottom:6px; font-weight:600; color:#333; }
    input, select { padding:12px; margin-bottom:20px; border:1px solid #ccc; border-radius:8px; font-size:15px; }
    input:focus, select:focus { border-color:#2c3e50; outline:none; }
    .btn { padding:12px; border:none; border-radius:8px; font-weight:600; cursor:pointer; font-size:15px; }
    .btn-primary { background:#2c3e50; color:#fff; }
    .btn-primary:hover { background:#34495e; }
    .message { margin-bottom:20px; padding:12px; background:#eaf7ef; border:1px solid #2ecc71; color:#2ecc71; border-radius:8px; text-align:center; font-weight:bold; }
    .back-link { display:block; text-align:center; margin-top:20px; color:#2c3e50; text-decoration:none; }
    .back-link:hover { text-decoration:underline; }
  </style>
</head>
<body>
  <div class="container">
    <h1>Edit Student</h1>

    <?php if (!empty($message)): ?>
      <div class="message"><?= htmlspecialchars($message) ?></div>
    <?php endif; ?>

    <form method="post">
      <label for="fullname">Fullname</label>
      <input type="text" name="fullname" id="fullname" value="<?= htmlspecialchars($student['fullname']) ?>" required>

      <label for="matricule">Matricule</label>
      <input type="text" name="matricule" id="matricule" value="<?= htmlspecialchars($student['matricule']) ?>" required>

      <label for="email">Email</label>
      <input type="email" name="email" id="email" value="<?= htmlspecialchars($student['email']) ?>" required>

      <label for="group_id">Group</label>
      <select name="group_id" id="group_id" required>
        <?php foreach ($groups as $g): ?>
          <option value="<?= $g['id'] ?>" <?= $student['group_id'] == $g['id'] ? 'selected' : '' ?>>
            <?= htmlspecialchars($g['name']) ?>
          </option>
        <?php endforeach; ?>
      </select>

      <button type="submit" class="btn btn-primary">💾 Save Changes</button>
    </form>

    <a href="students.php" class="back-link">⬅ Back to Students List</a>
  </div>
</body>
</html>

