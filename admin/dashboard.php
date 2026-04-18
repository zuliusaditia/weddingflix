<?php session_start();
if (!isset($_SESSION['login'])) {
  header("Location: login.php");
  exit;
}
?>

<!DOCTYPE html>
<html>
<head>
<title>WEDFLIX CMS</title>
<style>
body { margin:0; font-family:sans-serif; background:#0b0b0f; color:#fff; }

.sidebar {
  width:220px;
  height:100vh;
  background:#111;
  position:fixed;
  padding:20px;
}

.sidebar h2 { margin-bottom:20px; }

.sidebar a {
  display:block;
  padding:10px;
  color:#fff;
  text-decoration:none;
  margin-bottom:10px;
}

.sidebar a:hover { background:#e50914; }

.content {
  margin-left:240px;
  padding:30px;
}

.hidden { display:none; }

input {
  display:block;
  margin-bottom:10px;
  padding:10px;
  width:300px;
}
button {
  padding:10px;
  background:#e50914;
  border:none;
  color:#fff;
}
</style>
</head>
<body>

<div class="sidebar">
  <h2>🎬 WEDFLIX</h2>

  <a onclick="showSection('guests')">Guests</a>
  <a onclick="showSection('gallery')">Gallery</a>
  <a onclick="showSection('content')">Content</a>
  <a href="logout.php">Logout</a>
</div>

<div class="content">

  <!-- CONTENT SECTION -->
  <div id="contentSection">
    <h2>Edit Content</h2>

    <input id="bride" placeholder="Bride Name">
    <input id="groom" placeholder="Groom Name">
    <input id="date" placeholder="Wedding Date">

    <button onclick="saveContent()">Save</button>
  </div>

  <!-- GUEST SECTION -->
  <div id="guestsSection" class="hidden">
    <h2>Guests</h2>
    <iframe src="index.php" style="width:100%;height:500px;border:none;"></iframe>
  </div>

  <!-- GALLERY -->
  <div id="gallerySection" class="hidden">
    <h2>Gallery</h2>
    <iframe src="gallery.php" style="width:100%;height:500px;border:none;"></iframe>
  </div>

</div>

<script>

// SWITCH SECTION
function showSection(section) {
  document.getElementById("contentSection").classList.add("hidden");
  document.getElementById("guestsSection").classList.add("hidden");
  document.getElementById("gallerySection").classList.add("hidden");

  document.getElementById(section + "Section").classList.remove("hidden");
}

// LOAD SETTINGS
async function loadSettings() {
  const res = await fetch('../api/get_settings.php');
  const data = await res.json();

  document.getElementById("bride").value = data.bride_name || '';
  document.getElementById("groom").value = data.groom_name || '';
  document.getElementById("date").value = data.wedding_date || '';
}

loadSettings();

// SAVE SETTINGS
async function saveContent() {

  await fetch('../api/update_settings.php', {
    method:'POST',
    body:new URLSearchParams({
      key:'bride_name',
      value:document.getElementById("bride").value
    })
  });

  await fetch('../api/update_settings.php', {
    method:'POST',
    body:new URLSearchParams({
      key:'groom_name',
      value:document.getElementById("groom").value
    })
  });

  await fetch('../api/update_settings.php', {
    method:'POST',
    body:new URLSearchParams({
      key:'wedding_date',
      value:document.getElementById("date").value
    })
  });

  alert("Saved!");
}

</script>

</body>
</html>