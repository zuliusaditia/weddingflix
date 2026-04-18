<?php
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Gallery Manager - WEDFLIX</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">

  <style>
    body {
      margin: 0;
      font-family: sans-serif;
      background: #0b0b0f;
      color: #fff;
    }

    .container {
      max-width: 1100px;
      margin: auto;
      padding: 30px;
    }

    h1 {
      margin-bottom: 20px;
    }

    .upload-area {
      border: 2px dashed rgba(255,255,255,0.2);
      padding: 40px;
      text-align: center;
      border-radius: 12px;
      margin-bottom: 20px;
      transition: 0.3s;
    }

    .upload-area.dragover {
      border-color: #e50914;
      background: rgba(229,9,20,0.05);
    }

    .upload-area button {
      margin-top: 10px;
      background: #e50914;
      border: none;
      padding: 10px 18px;
      color: #fff;
      cursor: pointer;
      border-radius: 6px;
    }

    .preview-grid {
      display: grid;
      grid-template-columns: repeat(auto-fill, minmax(120px, 1fr));
      gap: 10px;
      margin-bottom: 20px;
    }

    .preview-item {
      position: relative;
    }

    .preview-item img {
      width: 100%;
      border-radius: 8px;
    }

    .remove-preview {
      position: absolute;
      top: 5px;
      right: 5px;
      background: rgba(0,0,0,0.6);
      border: none;
      color: #fff;
      cursor: pointer;
      border-radius: 4px;
    }

    .btn {
      background: #e50914;
      border: none;
      padding: 10px 18px;
      color: #fff;
      cursor: pointer;
      border-radius: 6px;
      font-weight: 600;
    }

    .gallery-grid {
      display: grid;
      grid-template-columns: repeat(auto-fill, minmax(160px, 1fr));
      gap: 16px;
      margin-top: 20px;
    }

    .gallery-item {
      position: relative;
      border-radius: 12px;
      overflow: hidden;
      background: #111;
      transition: 0.3s;
    }

    .gallery-item img {
      width: 100%;
      height: 160px;
      object-fit: cover;
    }

    .gallery-item:hover {
      transform: scale(1.05);
    }

    .delete-btn {
      position: absolute;
      top: 8px;
      right: 8px;
      background: rgba(0,0,0,0.7);
      border: none;
      color: #fff;
      padding: 6px 10px;
      cursor: pointer;
      border-radius: 6px;
    }

    .empty {
      opacity: 0.6;
      margin-top: 20px;
    }
  </style>
</head>
<body>

<div class="container">

  <h1>📸 Gallery Manager</h1>

  <!-- UPLOAD AREA -->
  <div class="upload-area" id="dropArea">
    <p>Drag & Drop Foto di sini</p>
    <span>atau</span><br>
    <button type="button" id="chooseBtn">Pilih File</button>
    <input type="file" id="fileInput" multiple hidden>
  </div>

  <!-- PREVIEW -->
  <div id="previewContainer" class="preview-grid"></div>

  <!-- UPLOAD BUTTON -->
  <button id="uploadBtn" class="btn" style="display:none;">
    Upload Sekarang
  </button>

  <!-- GALLERY -->
  <div id="galleryGrid" class="gallery-grid"></div>
  <p id="emptyText" class="empty" style="display:none;">Belum ada foto</p>

</div>

<script>

// ===============================
// PREVENT FORM REFRESH GLOBAL
// ===============================
document.addEventListener("submit", e => e.preventDefault());

// ===============================
// ELEMENTS
// ===============================
const dropArea = document.getElementById("dropArea");
const fileInput = document.getElementById("fileInput");
const previewContainer = document.getElementById("previewContainer");
const uploadBtn = document.getElementById("uploadBtn");
const chooseBtn = document.getElementById("chooseBtn");

let filesToUpload = [];

// ===============================
// BUTTON FIX
// ===============================
chooseBtn.addEventListener("click", () => fileInput.click());

// ===============================
// DRAG & DROP
// ===============================
dropArea.addEventListener("dragover", e => {
  e.preventDefault();
  dropArea.classList.add("dragover");
});

dropArea.addEventListener("dragleave", () => {
  dropArea.classList.remove("dragover");
});

dropArea.addEventListener("drop", e => {
  e.preventDefault();
  dropArea.classList.remove("dragover");
  handleFiles(e.dataTransfer.files);
});

// ===============================
// FILE INPUT
// ===============================
fileInput.addEventListener("change", () => {
  handleFiles(fileInput.files);
});

// ===============================
// HANDLE FILE
// ===============================
function handleFiles(files) {
  for (let file of files) {

    if (!file.type.startsWith("image/")) continue;

    if (file.size > 3 * 1024 * 1024) {
      alert("Max 3MB");
      continue;
    }

    filesToUpload.push(file);

    const reader = new FileReader();
    reader.onload = e => {
      const div = document.createElement("div");
      div.classList.add("preview-item");

      div.innerHTML = `
        <img src="${e.target.result}">
        <button class="remove-preview" type="button">✕</button>
      `;

      div.querySelector("button").onclick = () => {
        div.remove();
        filesToUpload = filesToUpload.filter(f => f !== file);
      };

      previewContainer.appendChild(div);
    };

    reader.readAsDataURL(file);
  }

  if (filesToUpload.length > 0) {
    uploadBtn.style.display = "block";
  }
}

// ===============================
// UPLOAD
// ===============================
uploadBtn.addEventListener("click", async () => {

  const formData = new FormData();

  filesToUpload.forEach(file => {
    formData.append("images[]", file);
  });

  uploadBtn.innerText = "Uploading...";
  uploadBtn.disabled = true;

  try {
    const res = await fetch("../api/upload_image.php", {
      method: "POST",
      body: formData
    });

    const data = await res.json();
    console.log("UPLOAD RESULT:", data);

  } catch (err) {
    console.error("UPLOAD ERROR:", err);
  }

  filesToUpload = [];
  previewContainer.innerHTML = "";
  uploadBtn.style.display = "none";
  uploadBtn.disabled = false;
  uploadBtn.innerText = "Upload Sekarang";

  loadGallery();
});

// ===============================
// LOAD GALLERY
// ===============================
async function loadGallery() {
  try {
    const res = await fetch("../api/get_gallery.php");
    const data = await res.json();

    const grid = document.getElementById("galleryGrid");
    const empty = document.getElementById("emptyText");

    if (!data || data.length === 0) {
      grid.innerHTML = "";
      empty.style.display = "block";
      return;
    }

    empty.style.display = "none";

    grid.innerHTML = data.map(img => `
      <div class="gallery-item">
        <img src="../${img}">
        <button class="delete-btn" onclick="deleteImage('${img}')" type="button">✕</button>
      </div>
    `).join("");

  } catch (err) {
    console.error("LOAD ERROR:", err);
  }
}

// ===============================
// DELETE
// ===============================
async function deleteImage(path) {
  if (!confirm("Hapus foto ini?")) return;

  await fetch("../api/delete_image.php", {
    method: "POST",
    body: new URLSearchParams({ path })
  });

  loadGallery();
}

// ===============================
// INIT
// ===============================
loadGallery();

</script>

</body>
</html>