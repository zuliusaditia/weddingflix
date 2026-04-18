<?php
header("Content-Type: application/json");

// path ke folder gallery
$dir = __DIR__ . "/../assets/img/gallery/";

$images = [];

// kalau folder ga ada
if (!is_dir($dir)) {
    echo json_encode([]);
    exit;
}

// scan semua file
$files = scandir($dir);

foreach ($files as $file) {

    // skip file aneh
    if ($file === '.' || $file === '..') continue;

    // filter hanya gambar
    $ext = strtolower(pathinfo($file, PATHINFO_EXTENSION));
    $allowed = ['jpg', 'jpeg', 'png', 'webp'];

    if (in_array($ext, $allowed)) {
        $images[] = "assets/img/gallery/" . $file;
    }
}

// urutkan terbaru di atas
rsort($images);

echo json_encode($images);