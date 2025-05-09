<?php
session_start();

// Koneksi database
$host = "localhost";
$user = "root";
$password = "";
$database = "seri_event";

$conn = new mysqli($host, $user, $password, $database);
if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}

$message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = htmlspecialchars($_POST["username"]);
    $passwordInput = $_POST["password"];

    // Ambil data user berdasarkan username
    $stmt = $conn->prepare("SELECT id_user, username, password FROM user WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
        $user = $result->fetch_assoc();

        // Verifikasi password
        if (password_verify($passwordInput, $user["password"])) {
            // Login sukses, simpan session
            $_SESSION["username"] = $user["username"];
            $_SESSION["id_user"] = $user["id_user"];

            header("Location: dashboard.php");
            exit();
        } else {
            $message = "Password salah.";
        }
    } else {
        $message = "Username tidak ditemukan.";
    }

    $stmt->close();
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Login - Serievent.id</title>
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
    <h4 class="text-center mb-4">Login User</h4>

    <?php if ($message): ?>
      <div class="alert alert-danger text-center"><?= $message ?></div>
    <?php endif; ?>

    <form method="POST" action="">
      <div class="mb-3">
        <input type="text" name="username" class="form-control" placeholder="Masukkan Username" required>
      </div>
      <div class="mb-3">
        <input type="password" name="password" class="form-control" placeholder="Masukkan Password" required>
      </div>
      <div class="d-grid">
        <button type="submit" class="btn btn-primary">Login</button>
      </div>
      <div class="text-center mt-3">
        <a href="registrasi.php" class="text-decoration-none">Tidak punya akun? Daftar</a>
      </div>
    </form>
  </div>
</div>

</body>
</html>
