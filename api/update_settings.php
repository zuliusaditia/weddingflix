<?php
require 'config.php';

$key = $_POST['key'];
$value = $_POST['value'];

$stmt = $conn->prepare("UPDATE settings SET value=? WHERE key_name=?");
$stmt->bind_param("ss", $value, $key);
$stmt->execute();

echo json_encode(["status"=>"success"]);