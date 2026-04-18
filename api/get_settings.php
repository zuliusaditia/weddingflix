<?php
require 'config.php';

$result = $conn->query("SELECT * FROM settings");

$data = [];

while ($row = $result->fetch_assoc()) {
  $data[$row['key_name']] = $row['value'];
}

echo json_encode($data);