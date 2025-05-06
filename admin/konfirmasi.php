<?php
session_start();
include '../koneksi.php';

// Ambil data event yang statusnya 'menunggu'
$query = "SELECT * FROM event WHERE status = 'menunggu'";
$result = mysqli_query($conn, $query); // Gunakan $conn sesuai dengan variabel koneksi Anda
?>


<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Serievent.id - Konfirmasi Event</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" />
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" />
  <style>
    body {
      background: #e9f1fb;
      overflow-x: hidden;
    }
    .navbar {
      background: linear-gradient(90deg, #00b2ff, #007aff);
      position: sticky;
      top: 0;
      z-index: 1030;
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
      padding: 10px 15px;
      border-radius: 10px;
      color: #007aff;
      font-weight: 500;
      text-decoration: none;
    }
    .nav-link-btn.active,
    .nav-link-btn:hover {
      background: #007aff;
      color: white;
      font-weight: 600;
      box-shadow: 0 4px 10px rgba(0, 122, 255, 0.4);
    }
    .main-content {
      background: white;
      box-shadow: 0 4px 10px rgba(0, 0, 0, 0.05);
      padding: 1.5rem;
      border-radius: 10px;
    }
    .section-title h2 {
      color: #007aff;
      font-weight: bold;
    }
    .poster-img {
      width: 100px;
      height: auto;
      object-fit: contain;
    }
    td.action-buttons {
      white-space: nowrap;
      text-align: center;
    }
    .btn-approve {
      background-color: #28a745;
      color: white;
      margin-right: 5px;
    }
    .btn-reject {
      background-color: #dc3545;
      color: white;
    }
    .btn-approve:hover,
    .btn-reject:hover {
      opacity: 0.85;
    }
    @media (max-width: 768px) {
      .sidebar {
        position: static;
        height: auto;
        top: 0;
      }
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
        <a class="nav-link" href="#">About</a>
        <a class="nav-link" href="#">Account</a>
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
    <a href="workshop.php" class="nav-link-btn"><i class="fas fa-tools me-2"></i> Workshop</a>
    <a href="upload.php" class="nav-link-btn"><i class="fas fa-cloud-upload-alt me-2"></i> Upload Event</a>
    <a href="tabel.php" class="nav-link-btn"><i class="fas fa-table me-2"></i> Tabel Event</a>
    <a href="konfirmasi.php" class="nav-link-btn active"><i class="fas fa-check-square me-2"></i> Konfirmasi Event</a>
    </div>
    </div>

    <!-- Main Content -->
    <div class="col-md-9">
      <div class="main-content">
        <div class="section-title mb-4">
          <h2>Konfirmasi Event</h2>
        </div>
        <div class="table-responsive">
          <table class="table table-bordered text-center align-middle">
            <thead class="table-primary">
              <tr>
                <th>No</th>
                <th>Poster</th>
                <th>Nama Event</th>
                <th>Tanggal</th>
                <th>Waktu</th>
                <th>Lokasi</th>
                <th>Kategori</th>
                <th>Kuota</th>
                <th>Deskripsi</th>
                <th>Aksi</th>
              </tr>
            </thead>
            <tbody>
              <?php if (mysqli_num_rows($result) > 0): ?>
                <?php $no = 1; ?>
                <?php while ($row = mysqli_fetch_assoc($result)): ?>
                  <tr>
                    <td><?= $no++ ?></td>
                    <td><img src="poster/<?= htmlspecialchars($row['poster']) ?>" class="poster-img" alt="Poster Event" /></td>
                    <td><?= htmlspecialchars($row['nama_event']) ?></td>
                    <td><?= date('d M Y', strtotime($row['tanggal'])) ?></td>
                    <td><?= $row['waktu'] ?></td>
                    <td><?= htmlspecialchars($row['lokasi']) ?></td>
                    <td><?= ucfirst($row['kategori']) ?></td>
                    <td><?= $row['kuota'] ?></td>
                    <td><?= htmlspecialchars($row['deskripsi']) ?></td>
                    <td class="action-buttons">
                      <a href="update_status.php?id=<?= $row['id_event'] ?>&status=disetujui" class="btn btn-sm btn-approve"><i class="fas fa-check"></i> Setujui</a>
                      <a href="update_status.php?id=<?= $row['id_event'] ?>&status=ditolak" class="btn btn-sm btn-reject"><i class="fas fa-times"></i> Tolak</a>
                    </td>
                  </tr>
                <?php endwhile; ?>
              <?php else: ?>
                <tr>
                  <td colspan="10">Tidak ada event yang menunggu konfirmasi.</td>
                </tr>
              <?php endif; ?>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
</div>

<!-- Script Bootstrap -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
