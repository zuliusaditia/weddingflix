<?php
header("Content-Type: application/json");

require __DIR__ . '/config.php';

$conn->set_charset("utf8mb4");

$data = [];

$result = $conn->query("SELECT * FROM rsvp ORDER BY created_at DESC LIMIT 50");

if (!$result) {
    echo json_encode([
        "status" => "error",
        "message" => $conn->error
    ]);
    exit;
}

while ($row = $result->fetch_assoc()) {
    $data[] = $row;
}

echo json_encode($data, JSON_UNESCAPED_UNICODE);