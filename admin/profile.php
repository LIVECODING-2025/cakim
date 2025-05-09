<?php
session_start();
if (!isset($_SESSION['username']) || !isset($_SESSION['id_admin'])) {
  header("Location: login.php");
  exit();
}

include 'koneksi.php';
$id_admin = $_SESSION['id_admin'];

$stmt = $conn->prepare("SELECT * FROM admin WHERE id_admin = ?");
$stmt->bind_param("i", $id_admin);
$stmt->execute();
$result = $stmt->get_result();
$admin = $result->fetch_assoc();

if (!$admin) {
    die("Data pengguna tidak ditemukan.");
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Profil Saya - Serievent.id</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
  <style>
    body {
      background: linear-gradient(to right, #e0f7fa, #f1f8ff);
      font-family: 'Poppins', sans-serif;
    }
    .navbar { background: linear-gradient(90deg, #00b2ff, #007aff); }
    .navbar-brand { font-weight: bold; font-style: italic; color: white; }
    .navbar-nav .nav-link { color: white; margin-left: 15px; }
    
    .nav-link.active {
      font-weight: bold;
      text-decoration: underline;
    }
    .profile-card {
      background: #ffffff;
      border-radius: 20px;
      padding: 50px 30px;
      box-shadow: 0 10px 40px rgba(0, 0, 0, 0.1);
      margin-top: 80px;
    }
    .avatar {
      width: 130px;
      height: 130px;
      border-radius: 50%;
      object-fit: cover;
      border: 4px solid #2575fc;
      margin-bottom: 20px;
    }
    .info-label {
      font-weight: 600;
      color: #2575fc;
    }
    .info-value {
      color: #333;
    }
    .btn-custom {
      background: linear-gradient(to right, #2575fc, #6a11cb);
      color: white;
      border: none;
      padding: 10px 25px;
      border-radius: 12px;
      font-weight: 500;
      transition: 0.3s ease;
    }
    .btn-custom:hover {
      opacity: 0.9;
    }
    .btn-outline-danger {
      border-radius: 12px;
    }
  </style>
</head>
<body>

<!-- Navbar -->
<nav class="navbar navbar-expand-lg sticky-top">
  <div class="container">
    <a class="navbar-brand d-flex align-items-center" href="dashboard.php">
      <i class="bi bi-arrow-left-circle me-2"></i> Serievent.id
    </a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse justify-content-end" id="navbarNav">
      <ul class="navbar-nav">
        <li class="nav-item"><a class="nav-link" href="#">About</a></li>
        <li class="nav-item"><a class="nav-link active" href="profile.php">Profile</a></li>
      </ul>
    </div>
  </div>
</nav>

<!-- Profile Card -->
<div class="container">
  <div class="row justify-content-center">
    <div class="col-lg-6 col-md-8">
      <div class="profile-card text-center">
        <!-- Menampilkan Foto Profil -->
        <img src="profil/<?= $admin['foto'] ?? 'default.png' ?>" alt="Foto Profil" class="avatar mb-4">
        
        <h4 class="fw-bold text-primary mb-4">Profil Pengguna</h4>

        <div class="text-start px-3">
          <div class="row mb-3">
          <div class="row mb-3">
  <div class="col-5 text-end info-label">Username:</div>
  <div class="col-7 info-value"><?= htmlspecialchars($admin['username']) ?></div>
</div>
<div class="row mb-3">
  <div class="col-5 text-end info-label">No. Telepon:</div>
  <div class="col-7 info-value"><?= htmlspecialchars($admin['no_telepon']) ?></div>
</div>
<div class="row mb-3">
  <div class="col-5 text-end info-label">Organisasi:</div>
  <div class="col-7 info-value"><?= htmlspecialchars($admin['nama_organisasi']) ?></div>
</div>

        </div>

        <div class="d-flex justify-content-center gap-3 mt-4">
          <a href="edit_profile.php?id=<?= $id_admin ?>" class="btn btn-custom">
            <i class="bi bi-pencil me-1"></i> Edit Profil
          </a>
          <a href="logout.php" class="btn btn-outline-danger">
            <i class="bi bi-box-arrow-right me-1"></i> Keluar
          </a>
        </div>
      </div>
    </div>
  </div>
</div>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
