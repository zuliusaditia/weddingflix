<?php
session_start();

if (isset($_POST['username'])) {

  $user = $_POST['username'];
  $pass = $_POST['password'];

  // hardcode dulu (nanti bisa DB)
  if ($user === "admin" && $pass === "wedflix123") {
    $_SESSION['login'] = true;
    header("Location: dashboard.php");
    exit;
  } else {
    $error = "Login gagal";
  }
}
?>

<!DOCTYPE html>
<html>
<head>
  <title>Login Admin</title>
  <style>
    body { background:#0b0b0f; color:#fff; font-family:sans-serif; display:flex; justify-content:center; align-items:center; height:100vh;}
    form { background:#111; padding:30px; border-radius:10px;}
    input { display:block; margin-bottom:10px; padding:10px; width:200px;}
    button { padding:10px; background:#e50914; color:#fff; border:none;}
  </style>
</head>
<body>

<form method="POST">
  <h2>Admin Login</h2>
  <?php if(isset($error)) echo "<p>$error</p>"; ?>
  <input type="text" name="username" placeholder="Username" required>
  <input type="password" name="password" placeholder="Password" required>
  <button>Login</button>
</form>

</body>
</html>