<?php
header("Content-Type: application/json");

// folder tujuan
$targetDir = __DIR__ . "/../assets/img/gallery/";

// buat folder kalau belum ada
if (!is_dir($targetDir)) {
    mkdir($targetDir, 0777, true);
}

$response = [];

if (!isset($_FILES['images'])) {
    echo json_encode([
        "status" => "error",
        "message" => "No file uploaded"
    ]);
    exit;
}

// allowed file
$allowedTypes = ['image/jpeg', 'image/png', 'image/jpg', 'image/webp'];

foreach ($_FILES['images']['tmp_name'] as $key => $tmp_name) {

    $fileType = $_FILES['images']['type'][$key];
    $fileSize = $_FILES['images']['size'][$key];

    // validasi type
    if (!in_array($fileType, $allowedTypes)) {
        continue;
    }

    // validasi size (max 3MB)
    if ($fileSize > 3 * 1024 * 1024) {
        continue;
    }

    // ambil extension
    $ext = pathinfo($_FILES['images']['name'][$key], PATHINFO_EXTENSION);

    // rename biar unik
    $newName = time() . "_" . uniqid() . "." . $ext;

    $targetFile = $targetDir . $newName;

    // pindahin file
    if (move_uploaded_file($tmp_name, $targetFile)) {
        $response[] = [
            "status" => "success",
            "file" => "assets/img/gallery/" . $newName
        ];
    }
}


echo json_encode([
    "status" => "done",
    "files" => $response
]);

var_dump($_FILES);
exit;