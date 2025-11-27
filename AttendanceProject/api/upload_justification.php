<?php
require_once __DIR__.'/../core/auth.php'; require_role(['student']);
require_once __DIR__.'/../core/db_connect.php';
$pdo = db();

$student_id = $_SESSION['user']['id'];
$course_id = (int)($_POST['course_id'] ?? 0);
$session_id = (int)($_POST['session_id'] ?? 0);
$reason = trim($_POST['reason'] ?? '');

if (!isset($_FILES['file']) || $_FILES['file']['error'] !== UPLOAD_ERR_OK) die("File upload error");
$dir = __DIR__ . '/../uploads';
if (!is_dir($dir)) mkdir($dir, 0777, true);
$fname = 'just_' . $student_id . '_' . time() . '_' . preg_replace('/[^a-zA-Z0-9._-]/', '_', basename($_FILES['file']['name']));
$target = $dir . '/' . $fname;
move_uploaded_file($_FILES['file']['tmp_name'], $target);

$stmt = $pdo->prepare("INSERT INTO justifications(student_id, course_id, session_id, file_path, reason) VALUES (?,?,?,?,?)");
$stmt->execute([$student_id, $course_id, $session_id, $fname, $reason]);

echo "<div class='alert alert-success m-3'>Justification submitted.</div><a href='../public/student/home.php'>Back</a>";
