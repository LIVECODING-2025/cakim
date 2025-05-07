<?php
// Mulai session dan koneksi ke database
session_start();
include 'koneksi.php';

$conn = new mysqli($host, $user, $pass, $db);
if ($conn->connect_error) {
  die("Koneksi gagal: " . $conn->connect_error);
}

// Pastikan user sudah login sebelum melihat detail event
if (!isset($_SESSION['user_id'])) {
  echo "Harap login terlebih dahulu.";
  exit;
}

// Ambil ID dari URL
$id = isset($_GET['id']) ? intval($_GET['id']) : 0;

// Ambil data event berdasarkan id_event dan pastikan hanya yang disetujui
$sql = "SELECT * FROM event WHERE id_event = $id AND status = 'disetujui'";
$result = $conn->query($sql);

if ($result->num_rows == 0) {
  echo "<h3>Event tidak ditemukan atau belum disetujui.</h3>";
  exit;
}

$event = $result->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Detail Event | Serievent.id</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" />
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" />
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
  <style>
    body { background: #e9f1fb; }
    .navbar { background: linear-gradient(90deg, #00b2ff, #007aff); }
    .navbar-brand { font-weight: bold; font-style: italic; color: white; }
    .navbar-nav .nav-link { color: white; margin-left: 15px; }
    .main-content {
      background: #d6eaff; margin-top: 30px; padding: 30px;
      border-radius: 15px; box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
    }
    .event-image img {
      width: 100%; max-height: 400px; object-fit: contain;
      background: #a6d1ff; border-radius: 10px; padding: 10px;
    }
    h1 { font-size: 28px; font-weight: bold; color: #007aff; }
    .btn-primary, .btn-secondary {
      padding: 10px 20px; border-radius: 8px; font-weight: 600;
    }
    .btn-primary { background-color: #4c67ff; border: none; }
    .btn-primary:hover { background-color: #3b52cc; }
    .btn-secondary { background-color: #d6d6d6; border: none; color: #333; }
    .btn-secondary:hover { background-color: #bbbbbb; }
  </style>
</head>
<body>

<!-- Navbar -->
<nav class="navbar navbar-expand-lg sticky-top shadow-sm">
  <div class="container">
    <a class="navbar-brand d-flex align-items-center" href="javascript:history.back()">
      <i class="bi bi-arrow-left-circle me-2" style="font-size: 1.2rem; color: white;"></i>
      <span>Serievent.id</span>
    </a>
    <div class="collapse navbar-collapse justify-content-end" id="navbarNav">
      <div class="navbar-nav">
        <a class="nav-link" href="about.html">About</a>
        <a class="nav-link" href="account.html">Account</a>
      </div>
    </div>
  </div>
</nav>

<!-- Content -->
<div class="container">
  <div class="main-content text-center">
    <h1><?= htmlspecialchars($event['nama_event']) ?></h1>

    <div class="event-image mt-4 mb-3">
      <img src="admin/poster/<?= htmlspecialchars($event['poster'] ?? 'default.jpg') ?>" alt="<?= htmlspecialchars($event['nama_event']) ?>" class="img-fluid" />
    </div>

    <div class="text-start mt-3">
      <h5><strong>Deskripsi:</strong></h5>
      <p><?= nl2br(htmlspecialchars($event['deskripsi'])) ?></p>

      <h5 class="mt-4"><strong>Detail Acara:</strong></h5>
      <div class="detail-info">
        <p><strong>Tanggal:</strong> <?= date("d M Y", strtotime($event['tanggal'])) ?></p>
        <p><strong>Waktu:</strong> <?= htmlspecialchars($event['waktu']) ?> WIB</p>
        <p><strong>Lokasi:</strong> <?= !empty($event['lokasi']) && trim($event['lokasi']) !== '-' ? htmlspecialchars($event['lokasi']) : 'Lokasi belum ditentukan' ?></p>
        <p><strong>Kuota:</strong> <?= htmlspecialchars($event['kuota']) ?> peserta</p>
      </div>
      <!-- Tombol Daftar -->
      <div class="text-center mt-4">
        <a href="daftar.php?id=<?= $event['id_event'] ?>" class="btn btn-primary">
          <i class="bi bi-check-circle me-1"></i> Daftar Sekarang
        </a>
      </div>
    </div>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
