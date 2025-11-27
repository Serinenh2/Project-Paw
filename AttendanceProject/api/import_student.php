<?php
require_once __DIR__.'/../core/auth.php'; require_role(['admin']);
require_once __DIR__.'/../core/db_connect.php';
$pdo = db();

if (!isset($_FILES['file']) || $_FILES['file']['error']!==UPLOAD_ERR_OK) die("Upload error");
$csv = file($_FILES['file']['tmp_name']);

$header = str_getcsv(array_shift($csv)); // expect: fullname, matricule, group_id
$map = array_flip($header);

$pdo->beginTransaction();
try {
  foreach ($csv as $line) {
    $cols = str_getcsv($line);
    $fullname = trim($cols[$map['fullname']] ?? '');
    $matricule = trim($cols[$map['matricule']] ?? '');
    $group_id  = (int)($cols[$map['group_id']] ?? 0);
    if ($fullname && $matricule) {
      $stmt = $pdo->prepare("INSERT INTO students(fullname, matricule, group_id) VALUES (?,?,?)");
      $stmt->execute([$fullname, $matricule, $group_id ?: null]);
    }
  }
  $pdo->commit();
  header('Location: ../public/admin/students.php');
} catch (Exception $e) {
  $pdo->rollBack();
  die("Import failed: " . $e->getMessage());
}
