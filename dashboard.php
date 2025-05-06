<?php
session_start();
include 'koneksi.php';


$conn = new mysqli($host, $user, $pass, $db);
if ($conn->connect_error) {
  die("Koneksi gagal: " . $conn->connect_error);
}

// Query total event
$totalEventQuery = "SELECT COUNT(*) as total FROM event WHERE status = 'disetujui'";
$totalEvent = $conn->query($totalEventQuery)->fetch_assoc()['total'];

// Query total seminar
$seminarQuery = "SELECT COUNT(*) as total FROM event WHERE kategori = 'seminar' AND status = 'disetujui'";
$totalSeminar = $conn->query($seminarQuery)->fetch_assoc()['total'];

// Query total workshop
$workshopQuery = "SELECT COUNT(*) as total FROM event WHERE kategori = 'workshop' AND status = 'disetujui'";
$totalWorkshop = $conn->query($workshopQuery)->fetch_assoc()['total'];

// Query 3 event terbaru
$query = "SELECT * FROM event WHERE status = 'disetujui' ORDER BY created_at DESC LIMIT 3";
$result = $conn->query($query);
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <title>Dashboard</title>
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" />
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" />
  <style>
    body {
      background: #e9f1fb;
      overflow-x: hidden;
    }
    .navbar {
      background: linear-gradient(90deg, #00b2ff, #007aff);
    }
    .navbar-brand {
      font-weight: bold;
      font-style: italic;
      color: white;
    }
    .navbar-nav .nav-link {
      color: white;
      margin-left: 15px;
    }
    .sidebar {
      background: #e1ebfa;
      height: calc(100vh - 70px);
      position: sticky;
      top: 70px;
      overflow-y: auto;
      padding: 1rem;
      border-radius: 10px;
    }

    .nav-link-btn {
      padding: 10px;
      border-radius: 10px;
      color: #007aff;
      font-weight: 500;
      text-decoration: none; /* Menghilangkan garis bawah */
    }
    .nav-link-btn.active, .nav-link-btn:hover {
      background: #007aff;
      color: white;
      font-weight: 600;
    }
    .main-content {
      background: white;
      padding: 1.5rem;
      border-radius: 10px;
    }
    .card-custom {
      border-radius: 15px;
      background: #f0f6ff;
      border: none;
      padding: 20px;
      text-align: center;
      transition: 0.3s;
    }
    .card-custom:hover {
      box-shadow: 0 6px 12px rgba(0,0,0,0.15);
    }
    .section-title h2 {
      color: #007aff;
      font-weight: bold;
    }
    .banner {
      width: 100%;
      height: 200px;
      background: url('img/banner.jpg') no-repeat center center;
      background-size: cover;
      border-radius: 10px;
      margin-bottom: 30px;
    }
    .alert-custom {
      background: #d1e7ff;
      color: #084298;
      font-weight: 500;
      border-radius: 8px;
      padding: 15px;
    }
    .badge {
      position: absolute;
      top: 10px;
      left: 10px;
      background: #007aff;
      color: white;
      padding: 5px 10px;
      font-size: 14px;
      border-radius: 15px;
      animation: pulse 2s infinite;
    }
    @keyframes pulse {
      0% { transform: scale(1); }
      50% { transform: scale(1.1); }
      100% { transform: scale(1); }
    }
    .card-custom img {
      width: 100%;
      height: auto;
      max-height: 200px;
      object-fit: contain;
      border-radius: 10px;
      background-color: #e0ecff;
    }
  </style>
</head>
<body>

<!-- Navbar -->
<nav class="navbar navbar-expand-lg">
  <div class="container">
    <a class="navbar-brand" href="#">Serievent.id</a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse justify-content-end" id="navbarNav">
      <div class="navbar-nav">
        <a class="nav-link" href="about.html">About</a>
        <a class="nav-link" href="account.html">Account</a>
      </div>
    </div>
  </div>
</nav>

<!-- Content -->
<div class="container-fluid my-4">
  <div class="row g-4">
    <!-- Sidebar -->
    <div class="col-md-3">
      <div class="sidebar d-flex flex-column gap-2">
        <a href="dashboard.php" class="nav-link-btn active"><i class="fas fa-home me-2"></i> Dashboard</a>
        <a href="event.html" class="nav-link-btn"><i class="fas fa-calendar-alt me-2"></i> Event</a>
        <a href="seminar.html" class="nav-link-btn"><i class="fas fa-chalkboard-teacher me-2"></i> Seminar</a>
        <a href="workshop.html" class="nav-link-btn"><i class="fas fa-tools me-2"></i> Workshop</a>
      </div>
    </div>

    <!-- Main Content -->
    <div class="col-md-9">
      <div class="main-content">
        <div class="mb-4">
          <h2 class="fw-bold text-primary">Selamat Datang di Serievent.id!</h2>
          <p class="text-muted">Temukan dan ikuti berbagai seminar dan workshop terbaik dari kami!</p>
        </div>

        <div class="banner"></div>

        <div class="row g-4 mb-4">
          <div class="col-md-4">
            <div class="card-custom">
              <h5>Total Event</h5>
              <h2><?= $totalEvent ?></h2>
            </div>
          </div>
          <div class="col-md-4">
            <div class="card-custom">
              <h5>Seminar</h5>
              <h2><?= $totalSeminar ?></h2>
            </div>
          </div>
          <div class="col-md-4">
            <div class="card-custom">
              <h5>Workshop</h5>
              <h2><?= $totalWorkshop ?></h2>
            </div>
          </div>
        </div>

        <div class="alert alert-custom mb-4">
          🎉 Ada event baru minggu ini! Jangan sampai ketinggalan untuk mendaftar sekarang juga!
        </div>

        <!-- Event Terbaru -->
        <div class="section-title">
          <h2>Event Terbaru</h2>
        </div>
        <div class="row g-4 mb-5">
          <?php while ($row = $result->fetch_assoc()): ?>
            <div class="col-md-4">
              <div class="card-custom text-center p-3 position-relative">
                <span class="badge">Baru</span>
                <img src="foto/<?= htmlspecialchars($row['poster'] ?? 'default.jpg') ?>" alt="<?= htmlspecialchars($row['nama_event']) ?>">
                <h5 class="mt-3"><?= htmlspecialchars($row['nama_event']) ?></h5>
                <p class="text-muted"><?= ucfirst($row['kategori']) ?> - <?= date("d M Y", strtotime($row['tanggal'])) ?></p>
                <a href="#" class="btn btn-primary btn-sm mt-2">Lihat Detail</a>
              </div>
            </div>
          <?php endwhile; ?>
        </div>
      </div>
    </div>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

