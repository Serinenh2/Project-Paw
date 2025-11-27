<?php
function h($s) { return htmlspecialchars($s, ENT_QUOTES, 'UTF-8'); }
function base_url() {
  $cfg = include __DIR__ . '/config.php';
  return rtrim($cfg['base_url'], '/');
}
