<?php
session_start();
require_once __DIR__ . '/db_connect.php';

function login($email, $password) {
  $pdo = db();
  $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
  $stmt->execute([$email]);
  $u = $stmt->fetch();
  if ($u && password_verify($password, $u['password_hash'])) {
    $_SESSION['user'] = ['id'=>$u['id'],'fullname'=>$u['fullname'],'role'=>$u['role']];
    return true;
  }
  return false;
}

function logout() { session_destroy(); }

function require_role($roles = []) {
  if (!isset($_SESSION['user'])) {
    header('Location: /attendance-system/public/login.php'); exit;
  }
  if ($roles && !in_array($_SESSION['user']['role'], $roles)) {
    http_response_code(403);
    echo "Forbidden"; exit;
  }
}
