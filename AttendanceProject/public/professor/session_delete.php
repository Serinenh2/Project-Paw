<?php
session_start();

// Vérification de l'accès
if (!isset($_SESSION["role"]) || $_SESSION["role"] !== "professor") {
    header("Location: ../login.php");
    exit;
}

if (!isset($_GET['id'])) {
    die("Session ID missing.");
}

$sessionId = (int)$_GET['id'];

$dsn = "mysql:host=127.0.0.1;dbname=attendance_db;charset=utf8";
$dbUser = "root";
$dbPass = "";

try {
    $pdo = new PDO($dsn, $dbUser, $dbPass, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
    ]);

    // Vérifier que la session appartient au professeur
    $stmt = $pdo->prepare("SELECT id FROM attendance_sessions WHERE id = ? AND opened_by = ?");
    $stmt->execute([$sessionId, $_SESSION["user_id"]]);
    $session = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$session) {
        die("Session not found or not yours.");
    }

    // Supprimer les enregistrements liés
    $pdo->prepare("DELETE FROM attendance_records WHERE session_id = ?")->execute([$sessionId]);

    // Supprimer la session
    $pdo->prepare("DELETE FROM attendance_sessions WHERE id = ?")->execute([$sessionId]);

    header("Location: sessions.php");
    exit;

} catch (PDOException $e) {
    die("Erreur : " . htmlspecialchars($e->getMessage()));
}
