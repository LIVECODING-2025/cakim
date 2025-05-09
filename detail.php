<?php
session_start();
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

include 'koneksi.php';

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $query = mysqli_query($conn, "SELECT * FROM event WHERE id_event = '$id'");
    $data = mysqli_fetch_assoc($query);

    if (!$data) {
        echo "Event tidak ditemukan!";
        exit();
    }

    // Hitung jumlah peserta yang sudah mendaftar
    $event_id = $data['id_event'];
    $query_pendaftar = "SELECT COUNT(*) AS jumlah_pendaftar FROM pendaftaran WHERE id_event = $event_id";
    $result_pendaftar = $conn->query($query_pendaftar);
    $pendaftar = $result_pendaftar->fetch_assoc()['jumlah_pendaftar'];

    // Perbarui kuota yang tersedia
    $kuota_sisa = $data['kuota'] - $pendaftar;
    $percent_filled = ($pendaftar / $data['kuota']) * 100;

    // Tentukan kelas warna berdasarkan kuota
    if ($percent_filled >= 100) {
        $status_class = 'full';
    } elseif ($percent_filled >= 75) {
        $status_class = 'low';
    } elseif ($percent_filled >= 50) {
        $status_class = 'medium';
    } else {
        $status_class = 'high';
    }
} else {
    echo "ID tidak ditemukan!";
    exit();
}
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
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap" rel="stylesheet">
  <style>
    body {
      background: #f0f4fb;
      font-family: 'Poppins', sans-serif;
      color: #333;
    }

    .navbar {
      background: linear-gradient(to right, #00b2ff, #007aff);
    }

    .navbar-brand {
      font-weight: 700;
      font-style: italic;
      color: white;
    }

    .navbar-nav .nav-link {
      color: white !important;
      margin-left: 15px;
      font-weight: 500;
    }

    .main-content {
      background: white;
      margin-top: 40px;
      padding: 35px;
      border-radius: 18px;
      box-shadow: 0 8px 30px rgba(0, 0, 0, 0.08);
    }

    .event-image img {
      max-width: 100%;
      max-height: 300px;
      height: auto;
      object-fit: contain;
      border-radius: 10px;
      display: block;
      margin: 0 auto;
      background-color: #f4f9ff;
      padding: 8px;
    }

    h1 {
      font-size: 30px;
      font-weight: 700;
      color: #007aff;
    }

    h5 {
      margin-top: 25px;
      font-weight: 600;
    }

    .detail-info p {
      font-size: 16px;
      margin-bottom: 5px;
    }

    .kuota-container {
      margin-top: 20px;
    }

    .kuota-info .progress {
      height: 20px;
      background-color: #e0eafc;
      border-radius: 10px;
    }

    /* Warna progress bar berdasarkan status kuota */
    .kuota-info.full .progress-bar {
      background-color: #dc3545; /* Merah jika penuh */
    }

    .kuota-info.low .progress-bar {
      background-color: #dc3545; /* Merah jika hampir penuh */
    }

    .kuota-info.medium .progress-bar {
      background-color: #ffc107; /* Kuning jika sedang */
    }

    .kuota-info.high .progress-bar {
      background-color: #28a745; /* Hijau jika masih banyak */
    }

    .progress-bar {
      font-weight: bold;
    }

    .kuota-number {
      margin-top: 5px;
      font-size: 15px;
      font-weight: 600;
      color: #444;
    }

    .status {
      margin-top: 8px;
      font-size: 14px;
      font-weight: 600;
      color: #007aff;
    }

    .btn-primary {
      background-color: #007aff;
      border: none;
      font-weight: 600;
      padding: 12px 24px;
      border-radius: 10px;
      transition: all 0.3s ease;
    }

    .btn-primary:hover {
      background-color: #005fd1;
    }

    .btn-secondary {
      background-color: #ccc;
      border: none;
      color: #555;
      font-weight: 600;
      padding: 12px 24px;
      border-radius: 10px;
    }

    .badge-status {
      font-size: 13px;
      padding: 5px 10px;
      border-radius: 12px;
      margin-left: 10px;
      color: white;
    }

    .badge-status.full { background-color: #dc3545; }
    .badge-status.limited { background-color: #ffc107; color: #000; }
    .badge-status.available { background-color: #28a745; }
  </style>
</head>
<body>

<!-- Navbar -->
<nav class="navbar navbar-expand-lg sticky-top">
  <div class="container">
    <a class="navbar-brand d-flex align-items-center" href="javascript:history.back()">
      <i class="bi bi-arrow-left-circle me-2" style="font-size: 1.2rem; color: white;"></i>
      <span>Serievent.id</span>
    </a>
    <div class="collapse navbar-collapse justify-content-end" id="navbarNav">
      <div class="navbar-nav">
        <a class="nav-link" href="about.php">About</a>
        <a class="nav-link" href="profile.php">Profile</a>
        <a class="nav-link" href="logout.php">Logout</a>
      </div>
    </div>
  </div>
</nav>

<!-- Content -->
<div class="container">
  <div class="main-content text-center">
    <h1><?= htmlspecialchars($data['nama_event']) ?></h1>

    <div class="event-image mt-4 mb-3">
      <img src="admin/poster/<?= htmlspecialchars($data['poster'] ?? 'default.jpg') ?>" 
           alt="<?= htmlspecialchars($data['nama_event']) ?>" 
           class="img-fluid" />
    </div>

    <div class="text-start mt-3">
      <h5><strong>Deskripsi:</strong></h5>
      <p><?= nl2br(htmlspecialchars($data['deskripsi'])) ?></p>

      <h5 class="mt-4"><strong>Detail Acara:</strong></h5>
      <div class="detail-info">
        <p><strong>Tanggal:</strong> <?= date("d F Y", strtotime($data['tanggal'])) ?></p>
        <p><strong>Waktu:</strong> <?= htmlspecialchars($data['waktu']) ?></p>
        <p><strong>Lokasi:</strong> <?= htmlspecialchars($data['lokasi']) ?></p>
        <p><strong>Kuota:</strong> <?= htmlspecialchars($data['kuota']) ?></p>
      </div>

      <!-- Tampilan Kuota -->
      <div class="kuota-container">
        <div class="kuota-info <?= $status_class ?>">
          <div class="progress">
            <div class="progress-bar" role="progressbar" style="width: <?= $percent_filled ?>%;" aria-valuenow="<?= $percent_filled ?>" aria-valuemin="0" aria-valuemax="100"></div>
          </div>
          <div class="kuota-number"><?= $pendaftar ?> / <?= $data['kuota'] ?></div>
        </div>

        <?php
          $badge_class = ($percent_filled >= 100) ? 'full' : (($percent_filled >= 75) ? 'limited' : 'available');
          $status_text = ($percent_filled >= 100) ? 'Kuota Penuh' : 'Tersedia';
        ?>
        <span class="badge-status <?= $badge_class ?>"><?= $status_text ?></span>
      </div>
    </div>

    <!-- Tombol Daftar -->
    <?php if ($kuota_sisa > 0): ?>
      <div class="d-flex justify-content-center gap-3 mt-4">
        <a href="daftar.php?id_event=<?= $data['id_event'] ?>" class="btn btn-primary">Daftar Sekarang</a>
      </div>
    <?php else: ?>
      <div class="d-flex justify-content-center gap-3 mt-4">
        <button class="btn btn-secondary" disabled>Kuota Habis</button>
      </div>
    <?php endif; ?>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
