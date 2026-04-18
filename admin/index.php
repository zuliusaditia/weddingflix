<?php
require __DIR__ . '/../api/config.php';

// ===============================
// HELPER
// ===============================
function createSlug($name) {
  return strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $name)));
}

// ===============================
// ADD MANUAL
// ===============================
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['name'])) {

  $name = $_POST['name'];
  $phone = $_POST['phone'] ?? '';

  $slug = createSlug($name);

  $stmt = $conn->prepare("INSERT INTO guests (name, slug, phone) VALUES (?, ?, ?)");
  $stmt->bind_param("sss", $name, $slug, $phone);
  $stmt->execute();

  header("Location: index.php");
  exit;
}

// ===============================
// BULK CSV UPLOAD
// ===============================
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['csv'])) {

  $file = $_FILES['csv']['tmp_name'];

  if (($handle = fopen($file, "r")) !== FALSE) {

    while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {

      $name = trim($data[0]);
      $phone = trim($data[1] ?? '');

      if (!$name) continue;

      $slug = createSlug($name);

      $stmt = $conn->prepare("INSERT INTO guests (name, slug, phone) VALUES (?, ?, ?)");
      $stmt->bind_param("sss", $name, $slug, $phone);
      $stmt->execute();
    }

    fclose($handle);
  }

  header("Location: index.php");
  exit;
}

// ===============================
// DELETE
// ===============================
if (isset($_GET['delete'])) {
  $id = intval($_GET['delete']);
  $conn->query("DELETE FROM guests WHERE id=$id");
  header("Location: index.php");
  exit;
}

// ===============================
// MARK AS SENT
// ===============================
if (isset($_GET['sent'])) {
  $id = intval($_GET['sent']);
  $conn->query("UPDATE guests SET is_sent=1 WHERE id=$id");
  header("Location: index.php");
  exit;
}

// ===============================
// GET DATA
// ===============================
$result = $conn->query("SELECT * FROM guests ORDER BY id DESC");
?>

<!DOCTYPE html>
<html>
<head>
  <title>WEDFLIX ADMIN</title>
  <style>
    body {
      background:#0b0b0f;
      color:#fff;
      font-family:Arial;
      padding:40px;
    }

    h1 { margin-bottom:20px; }

    form { margin-bottom:20px; }

    input {
      padding:10px;
      width:250px;
      border:none;
      border-radius:6px;
      margin-right:10px;
    }

    button {
      padding:10px 16px;
      background:#e50914;
      color:#fff;
      border:none;
      border-radius:6px;
      cursor:pointer;
    }

    table {
      width:100%;
      border-collapse: collapse;
      margin-top:20px;
    }

    th, td {
      padding:12px;
      border-bottom:1px solid #333;
      text-align:left;
    }

    .btn-copy { background:#22c55e; margin-right:6px; }
    .btn-delete { background:#ef4444; }
    .btn-wa { background:#25D366; margin-right:6px; }

    .link {
      font-size:12px;
      color:#aaa;
    }
  </style>
</head>
<body>

<h1>👥 WEDFLIX GUEST MANAGER</h1>

<!-- ADD MANUAL -->
<form method="POST">
  <input type="text" name="name" placeholder="Nama Tamu..." required>
  <input type="text" name="phone" placeholder="628xxxx (WA)">
  <button type="submit">Tambah</button>
</form>

<!-- CSV UPLOAD -->
<form method="POST" enctype="multipart/form-data">
  <input type="file" name="csv" accept=".csv" required>
  <button type="submit">Upload CSV</button>
</form>

<p style="font-size:12px; color:#aaa;">
Format CSV: nama,phone (contoh: Zulius,628123456789)
</p>

<a href="template_guests.csv" download>
  <button type="button" style="background:#3b82f6; margin-top:10px;">
    Download Template CSV
  </button>
</a>

<!-- LIST -->
<table>
  <tr>
    <th>Nama</th>
    <th>Phone</th>
    <th>Link</th>
    <th>Aksi</th>
    <th>Status</th>
  </tr>

  <?php while($row = $result->fetch_assoc()): 
    $link = "https://ourinvitation.net/?to=" . $row['slug'];
  ?>

  <tr>
    <td><?= $row['name'] ?></td>
    <td><?= $row['phone'] ?></td>
    <td class="link"><?= $link ?></td>

    <td>
      <button class="btn-copy" onclick="copyLink('<?= $link ?>')">Copy</button>

      <button class="btn-wa" 
        onclick="sendWA('<?= $row['id'] ?>','<?= $row['name'] ?>','<?= $row['phone'] ?>','<?= $link ?>')">
        WA
      </button>

      <a href="?delete=<?= $row['id'] ?>" onclick="return confirm('Hapus?')">
        <button class="btn-delete">Delete</button>
      </a>
    </td>

    <td>
      <?= $row['is_sent'] ? '✅ Sent' : '❌ Pending' ?>
    </td>

  </tr>

  <?php endwhile; ?>

</table>

<script>

function copyLink(link) {
  navigator.clipboard.writeText(link);
  alert("Link copied!");
}

function generateMessage(name, link) {
  return `Assalamualaikum Warahmatullahi Wabarakatuh

Dengan penuh rasa syukur dan tanpa mengurangi rasa hormat, kami mengundang Bapak/Ibu/Saudara/i
${name} & Partner,
untuk berkenan hadir dalam momen bahagia kami.

Hari istimewa kami akan menjadi lebih bermakna dengan kehadiran dan doa restu dari Anda.

Untuk informasi lengkap mengenai acara, silakan kunjungi undangan digital kami melalui link berikut:
${link}

Merupakan suatu kehormatan dan kebahagiaan bagi kami apabila Bapak/Ibu/Saudara/i dapat hadir dan turut merayakan hari spesial ini bersama kami.

Kami mohon maaf karena undangan disampaikan melalui pesan ini.
Atas pengertian dan perhatiannya, kami ucapkan terima kasih.

Semoga kita semua senantiasa diberikan kesehatan dan dapat bertemu di hari bahagia tersebut.

Wassalamualaikum Warahmatullahi Wabarakatuh`;
}

function sendWA(id, name, phone, link) {

  if (!phone) {
    alert("Nomor WA kosong!");
    return;
  }

  const message = generateMessage(name, link);
  const url = `https://wa.me/${phone}?text=${encodeURIComponent(message)}`;

  window.open(url, "_blank");

  // mark as sent
  window.location.href = "?sent=" + id;
}

</script>

</body>
</html>