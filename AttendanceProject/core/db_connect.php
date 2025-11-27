<?php
function db() {
  static $pdo = null;
  if ($pdo) return $pdo;
  $cfg = include __DIR__ . '/config.php';
  try {
    $dsn = "mysql:host={$cfg['db_host']};dbname={$cfg['db_name']};charset=utf8mb4";
    $pdo = new PDO($dsn, $cfg['db_user'], $cfg['db_pass'], [
      PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
      PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    ]);
    return $pdo;
  } catch (PDOException $e) {
    include_once __DIR__ . '/logger.php';
    log_error('DB', $e->getMessage());
    if ($cfg['debug']) {
      die('Connection failed: ' . htmlspecialchars($e->getMessage()));
    } else {
      die('Connection failed');
    }
  }
}
