<?php
session_start();
if (!isset($_SESSION['username'])) {
  header("Location: login.php");
  exit();
}

include 'koneksi.php';
// Koneksi ke database
$host = 'localhost';
$user = 'root';
$password = '';
$database = 'seri_event';

$conn = new mysqli($host, $user, $password, $database);

// Cek koneksi
if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}

// Query untuk mengambil data workshop
$query = "SELECT * FROM event WHERE kategori = 'Workshop' AND status = 'disetujui'";
$result = $conn->query($query);
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Serievent.id - Workshop</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
  <style>
    body { background: #e9f1fb; overflow-x: hidden; }
    .navbar { background: linear-gradient(90deg, #00b2ff, #007aff); }
    .navbar-brand { font-weight: bold; font-style: italic; color: white; }
    .navbar-nav .nav-link { color: white; margin-left: 15px; }
    .sidebar {
      background: #e1ebfa; height: calc(100vh - 70px);
      position: sticky; top: 70px;
      overflow-y: auto; padding: 1rem;
      border-radius: 10px;
    }
    .nav-link-btn {
      padding: 10px; border-radius: 10px;
      color: #007aff; font-weight: 500; text-decoration: none;
    }
    .nav-link-btn.active, .nav-link-btn:hover {
      background: #007aff; color: white; font-weight: 600;
    }
    .main-content {
      background: white; padding: 1.5rem; border-radius: 10px;
    }
    .card {
      border: none; background: #f9f9f9;
      border-radius: 15px; transition: 0.3s;
      height: 100%; display: flex; flex-direction: column;
      box-shadow: 0 4px 10px rgba(0,0,0,0.1);
    }
    .card:hover {
      box-shadow: 0 10px 20px rgba(0,0,0,0.15);
      transform: translateY(-5px);
    }
    .card img {
      width: 100%; height: 200px;
      object-fit: contain; /* Gambar tidak terpotong */
      border-radius: 10px; background-color: #e0ecff;
    }
    .card-body {
      padding: 1.5rem 1rem;
    }
    .card-title {
      font-size: 1.25rem; font-weight: 600; color: #007aff;
    }
    .card-text {
      font-size: 0.9rem; color: #6c757d;
    }
    .kuota-container {
      margin-top: 10px;
    }
    .progress-bar {
      background-color: #007aff;
    }
    .btn-primary {
      background-color: #007aff; border: none;
      font-weight: 500; border-radius: 8px;
    }
    .btn-primary:hover {
      background-color: #005bb5;
    }
    .section-title h2 {
      color: #007aff; font-weight: bold;
    }
    .kuota-info {
      display: flex;
      justify-content: space-between;
      align-items: center;
      font-weight: 600;
    }
    .kuota-info .progress {
      width: 70%;
      height: 10px;
    }
    .kuota-info .kuota-number {
      font-size: 1.1rem;
      color: #007aff;
    }
    /* Warna berdasarkan kuota */
    .kuota-info.low .progress-bar {
      background-color: #dc3545; /* Merah jika sisa kuota sedikit */
    }
    .kuota-info.medium .progress-bar {
      background-color: #ffc107; /* Kuning jika sisa kuota sedang */
    }
    .kuota-info.high .progress-bar {
      background-color: #28a745; /* Hijau jika sisa kuota banyak */
    }
    .kuota-info .status {
      font-size: 0.9rem;
      color: #6c757d;
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
        <a class="nav-link" href="profile.php">Profile</a>
        <a class="nav-link" href="logout.php">Logout</a>
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
        <a href="dashboard.php" class="nav-link-btn "><i class="fas fa-home me-2"></i> Dashboard</a>
        <a href="event.php" class="nav-link-btn"><i class="fas fa-calendar-alt me-2"></i> Event</a>
        <a href="seminar.php" class="nav-link-btn"><i class="fas fa-chalkboard-teacher me-2"></i> Seminar</a>
        <a href="workshop.php" class="nav-link-btn active"><i class="fas fa-tools me-2"></i> Workshop</a>
      </div>
    </div>

    <!-- Main Content -->
    <div class="col-md-9">
      <div class="main-content p-4 rounded">
        <div class="d-flex align-items-center mb-4 section-title">
          <i class="fas fa-tools me-2"></i>
          <h2 class="mb-0">Workshop</h2>
        </div>

        <div class="row g-4">
          <?php if ($result->num_rows > 0): ?>
            <?php while ($event = $result->fetch_assoc()): 
              // Hitung jumlah peserta yang sudah mendaftar
              $event_id = $event['id_event'];
              $query_pendaftar = "SELECT COUNT(*) AS jumlah_pendaftar FROM pendaftaran WHERE id_event = $event_id";
              $result_pendaftar = $conn->query($query_pendaftar);
              $pendaftar = $result_pendaftar->fetch_assoc()['jumlah_pendaftar'];
              
              // Perbarui kuota yang tersedia
              $kuota_sisa = $event['kuota'] - $pendaftar;
              $percent_filled = ($pendaftar / $event['kuota']) * 100;
              $status_class = ($percent_filled >= 75) ? 'low' : (($percent_filled >= 50) ? 'medium' : 'high');
            ?>
              <div class="col-md-4">
                <div class="card p-3 text-center">
                  <img src="admin/poster/<?= htmlspecialchars($event['poster'] ?? 'default.jpg') ?>" alt="<?= htmlspecialchars($event['nama_event']) ?>">
                  <div class="card-body">
                    <h5 class="card-title"><?= htmlspecialchars($event['nama_event']) ?></h5>
                    <p class="card-text">Tanggal: <?= date('d M Y', strtotime($event['tanggal'])) ?></p>

                    <!-- Tampilan Kuota dengan Progress Bar dan Angka Kuota -->
                    <div class="kuota-container">
                      <div class="kuota-info <?= $status_class ?>">
                        <div class="progress">
                          <div class="progress-bar" role="progressbar" style="width: <?= $percent_filled ?>%;" aria-valuenow="<?= $percent_filled ?>" aria-valuemin="0" aria-valuemax="100"></div>
                        </div>
                        <div class="kuota-number"><?= $pendaftar ?> / <?= $event['kuota'] ?></div>
                      </div>
                      <div class="status"><?= ($percent_filled >= 100) ? 'Kuota Penuh' : 'Tersisa Kuota' ?></div>
                    </div>

                    <a href="detail.php?id=<?= $event['id_event'] ?>" class="btn btn-primary btn-sm">Lihat Detail</a>
                  </div>
                </div>
              </div>
            <?php endwhile; ?>
          <?php else: ?>
            <div class="col-12">
              <div class="alert alert-info text-center">
                Belum ada workshop yang tersedia.
              </div>
            </div>
          <?php endif; ?>
        </div>
      </div>
    </div>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
