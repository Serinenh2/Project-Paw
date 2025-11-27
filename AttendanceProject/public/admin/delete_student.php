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

    // Supprimer les enregistrements liés
    $pdo->prepare("DELETE FROM attendance_records WHERE student_id = ?")->execute([$studentId]);

    // Supprimer l'étudiant
    $pdo->prepare("DELETE FROM students WHERE id = ?")->execute([$studentId]);

    header("Location: students.php");
    exit;

} catch (PDOException $e) {
    die("Erreur : " . htmlspecialchars($e->getMessage()));
}
