<?php
include 'koneksi.php';
session_start();

// Ambil semua data event dari database
$query = "SELECT * FROM event ORDER BY id_event DESC";
$result = mysqli_query($koneksi, $query);
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Serievent.id - Tabel Event</title>
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
      padding: 1rem;
      border-radius: 10px;
      height: 100%;
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
      width: 100%;
      height: 150px;
      object-fit: cover;
      border-radius: 6px;
    }
    td.action-buttons {
      text-align: center;
      vertical-align: middle;
      white-space: nowrap;
    }
    td.action-buttons .btn {
      width: 32px;
      height: 32px;
      display: inline-flex;
      align-items: center;
      justify-content: center;
      padding: 0;
      font-size: 14px;
      border-radius: 6px;
    }

    @media (max-width: 768px) {
      .sidebar {
        position: static;
        height: auto;
        margin-bottom: 1rem;
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
    <div class="col-12 col-md-3">
      <div class="sidebar d-flex flex-column gap-2">
        <a href="dashboard.html" class="nav-link-btn d-flex align-items-center">
          <i class="fas fa-home me-2"></i> Dashboard
        </a>
        <a href="#" class="nav-link-btn d-flex align-items-center">
          <i class="fas fa-calendar-alt me-2"></i> Event
        </a>
        <a href="seminar.html" class="nav-link-btn d-flex align-items-center">
          <i class="fas fa-chalkboard-teacher me-2"></i> Seminar
        </a>
        <a href="workshop.html" class="nav-link-btn d-flex align-items-center">
          <i class="fas fa-tools me-2"></i> Workshop
        </a>
        <a href="upload.php" class="nav-link-btn d-flex align-items-center">
          <i class="fas fa-cloud-upload-alt me-2"></i> Upload Event
        </a>
        <a href="tabel.php" class="nav-link-btn active d-flex align-items-center">
          <i class="fas fa-table me-2"></i> Tabel Event
        </a>
        <a href="konfirmasi.html" class="nav-link-btn d-flex align-items-center">
          <i class="fas fa-check-square me-2"></i> Konfirmasi Event
        </a>
      </div>
    </div>

    <!-- Main Content -->
    <div class="col-12 col-md-9">
      <div class="main-content">
        <div class="section-title mb-4">
          <h2>Tabel Event</h2>
        </div>

        <div class="table-responsive">
          <table class="table table-bordered table-striped align-middle">
            <thead class="table-primary text-center">
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
            <tbody class="text-center">
              <?php
              $no = 1;
              while ($row = mysqli_fetch_assoc($result)) :
              ?>
                <tr>
                  <td><?= $no++ ?></td>
                  <td>
                    <?php if ($row['poster']) : ?>
                      <img src="poster/<?= htmlspecialchars($row['poster']) ?>" class="poster-img" alt="Poster">
                    <?php else : ?>
                      <span>Tidak ada</span>
                    <?php endif; ?>
                  </td>
                  <td><?= htmlspecialchars($row['nama_event']) ?></td>
                  <td><?= htmlspecialchars($row['tanggal']) ?></td>
                  <td><?= htmlspecialchars($row['waktu']) ?></td>
                  <td><?= htmlspecialchars($row['lokasi']) ?></td>
                  <td><?= htmlspecialchars($row['kategori']) ?></td>
                  <td><?= (int)$row['kuota'] ?></td>
                  <td><?= htmlspecialchars($row['deskripsi']) ?></td>
                  <td class="action-buttons">
                    <a href="edit_event.php?id_event=<?= $row['id_event'] ?>" class="btn btn-primary" title="Edit">
                      <i class="fas fa-pen"></i>
                    </a>
                    <a href="hapus_event.php?id_event=<?= $row['id_event'] ?>" class="btn btn-danger" title="Hapus" onclick="return confirm('Yakin ingin menghapus event ini?')">
                      <i class="fas fa-trash"></i>
                    </a>
                  </td>
                </tr>
              <?php endwhile; ?>
              <?php if (mysqli_num_rows($result) == 0) : ?>
                <tr>
                  <td colspan="10">Belum ada event yang ditambahkan.</td>
                </tr>
              <?php endif; ?>
            </tbody>
          </table>
        </div>

      </div>
    </div>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
