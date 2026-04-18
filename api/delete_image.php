<?php
header("Content-Type: application/json");

$path = $_POST['path'] ?? '';

if (!$path) {
  echo json_encode(["status" => "error"]);
  exit;
}

$fullPath = __DIR__ . "/../" . $path;

if (file_exists($fullPath)) {
  unlink($fullPath);
  echo json_encode(["status" => "success"]);
} else {
  echo json_encode(["status" => "not_found"]);
}