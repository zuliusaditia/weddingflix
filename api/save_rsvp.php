<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

header("Content-Type: application/json");
include "config.php";

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
  echo json_encode(["status"=>"error","message"=>"Method not allowed"]);
  exit;
}

// ambil data
$name = trim($_POST['name'] ?? '');
$guest_count = trim($_POST['guest_count'] ?? '');
$attendance = trim($_POST['attendance'] ?? '');
$message = $_POST['message'] ?: 'Selamat & doa terbaik ❤️';

// validasi
if (!$name || !$guest_count || !$attendance || !$message) {
  echo json_encode(["status"=>"error","message"=>"Data tidak lengkap"]);
  exit;
}

// insert
$stmt = $conn->prepare("INSERT INTO rsvp (name, guest_count, attendance, message) VALUES (?, ?, ?, ?)");

if (!$stmt) {
  echo json_encode([
    "status"=>"error",
    "message"=>$conn->error
  ]);
  exit;
}
$stmt->bind_param("ssss", $name, $guest_count, $attendance, $message);

if ($stmt->execute()) {
  echo json_encode(["status"=>"success"]);
} else {
  echo json_encode([
    "status"=>"error",
    "message"=>$stmt->error
  ]);
}
