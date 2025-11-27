<?php
function log_error($channel, $message) {
  $line = date('c') . " [$channel] $message" . PHP_EOL;
  file_put_contents(__DIR__ . '/../error.log', $line, FILE_APPEND);
}
