<?php
$host = "localhost";
$user = "root";
$password = "";
$database = "seri_event";

// Koneksi ke database
$conn = new mysqli($host, $user, $password, $database);
if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}

$message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = htmlspecialchars($_POST["username"]);
    $email = htmlspecialchars($_POST["email"]);
    $phone = htmlspecialchars($_POST["phone"]);
    $password = password_hash($_POST["password"], PASSWORD_DEFAULT); // Enkripsi password

    // Cek apakah username atau email sudah ada
    $check = $conn->prepare("SELECT id_user FROM user WHERE username = ? OR email = ?");
    $check->bind_param("ss", $username, $email);
    $check->execute();
    $check->store_result();

    if ($check->num_rows > 0) {
        $message = "Username atau email sudah digunakan.";
    } else {
        // Simpan user baru
        $stmt = $conn->prepare("INSERT INTO user (username, email, phone, password) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("ssss", $username, $email, $phone, $password);

        if ($stmt->execute()) {
            $message = "Registrasi berhasil! Silakan login.";
        } else {
            $message = "Registrasi gagal: " . $stmt->error;
        }

        $stmt->close();
    }

    $check->close();
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Registrasi - Serievent.id</title>
  <h4 class="text-center mb-4">Registrasi User</h4>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    body {
      background-color: #e6f2ff;
      height: 100vh;
      display: flex;
      justify-content: center;
      align-items: center;
    }
    .card {
      padding: 2rem;
      border-radius: 15px;
      background-color: #b3d9ff;
    }
    .title {
      font-weight: bold;
      text-align: center;
      margin-bottom: 1rem;
      font-style: italic;
    }
  </style>
</head>
<body>

<div class="container">
  <div class="card mx-auto" style="max-width: 400px;">
    <h2 class="title">Serievent.id</h2>
    <h4 class="text-center mb-4">Registrasi</h4>

    <?php if ($message): ?>
      <div class="alert alert-info text-center"><?= $message ?></div>
    <?php endif; ?>

    <form method="POST" action="">
      <div class="mb-3">
        <input type="text" name="username" class="form-control" placeholder="Masukkan Username" required>
      </div>
      <div class="mb-3">
        <input type="email" name="email" class="form-control" placeholder="Masukkan Email Aktif" required>
      </div>
      <div class="mb-3">
        <input type="text" name="phone" class="form-control" placeholder="Masukkan Nomor Telepon" required>
      </div>
      <div class="mb-3">
        <input type="password" name="password" class="form-control" placeholder="Masukkan Password" required>
      </div>
      <div class="d-grid">
        <button type="submit" class="btn btn-primary">Daftar</button>
      </div>
      <div class="text-center mt-3">
        <a href="login.php" class="text-decoration-none">Sudah punya akun? Login</a>
      </div>
    </form>
  </div>
</div>

</body>
</html>
