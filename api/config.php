<?php
$conn = new mysqli(
  "localhost",
  "root",
  "",
  "weddingflix"
);

if ($conn->connect_error) {
  die("Koneksi database gagal");
}
