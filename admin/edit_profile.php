<?php
session_start();
if (!isset($_SESSION['username']) || !isset($_SESSION['id_admin'])) {
  header("Location: login.php");
  exit();
}

include 'koneksi.php';
$id_admin = $_SESSION['id_admin'];

$query = "SELECT * FROM admin WHERE id_admin = $id_admin";
$result = mysqli_query($conn, $query);
$admin = mysqli_fetch_assoc($result);

if (!$admin) {
    die("Data pengguna tidak ditemukan.");
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $no_telepon = mysqli_real_escape_string($conn, $_POST['no_telepon']);
    $nama_organisasi = mysqli_real_escape_string($conn, $_POST['nama_organisasi']);

    // Upload foto jika ada
    if (!empty($_FILES['foto']['name'])) {
        $fotoName = $_FILES['foto']['name'];
        $fotoTmp  = $_FILES['foto']['tmp_name'];
        $ext      = pathinfo($fotoName, PATHINFO_EXTENSION);
        $newName  = 'admin' . $id_admin . '.' . $ext;
        $uploadPath = '../profil/' . $newName;

        if (move_uploaded_file($fotoTmp, $uploadPath)) {
            $queryUpdate = "UPDATE admin SET username='$username', no_telepon='$no_telepon', nama_organisasi='$nama_organisasi', foto='$newName' WHERE id_admin=$id_admin";
        } else {
            echo "Upload foto gagal.";
            exit();
        }
    } else {
        $queryUpdate = "UPDATE admin SET username='$username', no_telepon='$no_telepon', nama_organisasi='$nama_organisasi' WHERE id_admin=$id_admin";
    }

    if (mysqli_query($conn, $queryUpdate)) {
        header("Location: profile.php");
        exit();
    } else {
        echo "Gagal memperbarui data: " . mysqli_error($conn);
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Edit Profil - Serievent.id</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
  <style>
    body {
      background-color: #f4f7fc;
      font-family: 'Poppins', sans-serif;
    }
    .profile-card {
      background: #ffffff;
      border-radius: 15px;
      padding: 40px;
      box-shadow: 0 15px 30px rgba(0, 0, 0, 0.1);
      margin-top: 60px;
    }
    .avatar {
      width: 120px;
      height: 120px;
      object-fit: cover;
      border-radius: 50%;
      border: 4px solid #007bff;
    }
    .navbar {
      background: linear-gradient(90deg, #007bff, #00b2ff);
    }
    .navbar-brand {
      font-weight: bold;
      font-size: 1.4rem;
      color: white;
    }
    .form-label {
      font-weight: 600;
      color: #007bff;
    }
    .form-control {
      border-radius: 10px;
    }
    .form-control:focus {
      border-color: #007bff;
      box-shadow: 0 0 5px rgba(0, 123, 255, 0.5);
    }
    .btn-custom {
      background: linear-gradient(to right, #00c6ff, #0072ff);
      color: white;
      border: none;
      padding: 12px 25px;
      border-radius: 10px;
      font-weight: 600;
    }
    .btn-custom:hover {
      background: linear-gradient(to right, #0072ff, #0056c7);
    }
    .cancel-btn {
      background-color: #f1f3f5;
      color: #007bff;
      border-radius: 10px;
      padding: 10px 25px;
      text-decoration: none;
    }
    .cancel-btn:hover {
      background-color: #e0e0e0;
    }
    .form-row {
      margin-bottom: 1.2rem;
    }
  </style>
</head>
<body>

<!-- Navbar -->
<nav class="navbar navbar-expand-lg navbar-light">
  <div class="container">
    <a class="navbar-brand" href="profile.php">
      <i class="bi bi-arrow-left-circle me-2" style="font-size: 1.5rem;"></i> Kembali ke Profil
    </a>
  </div>
</nav>

<!-- Form Edit Profile -->
<div class="container">
  <div class="row justify-content-center">
    <div class="col-lg-6 col-md-8">
      <div class="profile-card text-center">
        <!-- Foto Profil -->
        <img src="profil/<?= $admin['foto'] ?? 'default.png' ?>" alt="Foto Profil" class="avatar mb-4">

        <!-- Judul -->
        <h4 class="fw-bold text-primary mb-4">Edit Profil</h4>

        <!-- Form -->
        <form method="POST" enctype="multipart/form-data">
          <div class="form-row row">
            <div class="col-md-4">
              <label class="form-label">Username</label>
            </div>
            <div class="col-md-8">
              <input type="text" name="username" value="<?= htmlspecialchars($admin['username']) ?>" class="form-control" required>
            </div>
          </div>

          <div class="form-row row">
            <div class="col-md-4">
              <label class="form-label">No. Telepon</label>
            </div>
            <div class="col-md-8">
              <input type="text" name="no_telepon" value="<?= htmlspecialchars($admin['no_telepon']) ?>" class="form-control" required>
            </div>
          </div>

          <div class="form-row row">
            <div class="col-md-4">
              <label class="form-label">Nama Organisasi</label>
            </div>
            <div class="col-md-8">
              <input type="text" name="nama_organisasi" value="<?= htmlspecialchars($admin['nama_organisasi']) ?>" class="form-control" required>
            </div>
          </div>

          <div class="form-row row">
            <div class="col-md-4">
              <label class="form-label">Foto Profil</label>
            </div>
            <div class="col-md-8">
              <input type="file" name="foto" class="form-control">
            </div>
          </div>

          <div class="d-flex justify-content-center gap-3 mt-4">
            <a href="profile.php" class="cancel-btn">Batal</a>
            <button type="submit" class="btn-custom">Simpan</button>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
