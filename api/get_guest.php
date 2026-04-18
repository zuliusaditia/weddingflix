<?php
require 'config.php';

$slug = $_GET['to'] ?? '';

$result = $conn->query("SELECT * FROM guests WHERE slug='$slug' LIMIT 1");

$data = $result->fetch_assoc();

echo json_encode($data);