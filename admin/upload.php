<?php
include 'koneksi.php';
session_start();
if (!isset($_SESSION['username'])) {
  header("Location: login.php");
  exit();
}

$id_admin = $_SESSION['id_admin'];
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Serievent.id - Upload Event</title>
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
      min-height: 500px;
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
      max-height: 150px;
      object-fit: contain;
    }

    td.action-buttons {
      white-space: nowrap;
      text-align: center;
    }

    .action-buttons .btn-edit,
    .action-buttons .btn-delete {
      border: none;
      border-radius: 6px;
      padding: 6px 10px;
      color: white;
    }

    .btn-edit {
      background-color: #007aff;
    }

    .btn-delete {
      background-color: #ff4d4f;
    }

    .btn-edit:hover,
    .btn-delete:hover {
      opacity: 0.85;
    }

    @media (max-width: 768px) {
      .sidebar {
        position: static;
        height: auto;
        top: 0;
      }

      .action-buttons .btn-edit,
      .action-buttons .btn-delete {
        padding: 4px 8px;
      }
    }
  </style>
</head>
<body>

<!-- Navbar -->
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
    <!-- Sidebar -->
<div class="col-12 col-md-3">
  <div class="sidebar d-flex flex-column gap-2">
    <a href="upload.php" class="nav-link-btn active d-flex align-items-center">
      <i class="fas fa-cloud-upload-alt me-2"></i> Upload Event
    </a>
    <a href="tabel.php" class="nav-link-btn d-flex align-items-center">
      <i class="fas fa-table me-2"></i> Tabel Event
    </a>
  </div>
</div>

    <!-- Main Content -->
    <div class="col-md-9">
      <div class="bg-white p-4 rounded shadow">
        <h2 class="text-primary mb-4">Tambah Event Baru</h2>

        <?php
        if (isset($_SESSION['pesan'])) {
            $type = $_SESSION['pesan']['type'] === 'berhasil' ? 'success' : 'danger';
            $isi = $_SESSION['pesan']['isi'];
            echo "<div class='alert alert-$type alert-dismissible fade show' role='alert'>
                    $isi
                    <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
                  </div>";
            unset($_SESSION['pesan']);
        }
        ?>

        <form action="proses_upload.php" method="POST" enctype="multipart/form-data">
          <div class="mb-3">
            <label class="form-label">Nama Event</label>
            <input type="text" name="nama_event" class="form-control" required>
          </div>
          <div class="mb-3">
            <label class="form-label">Tanggal</label>
            <input type="date" name="tanggal" class="form-control" required>
          </div>
          <div class="mb-3">
            <label class="form-label">Waktu</label>
            <input type="time" name="waktu" class="form-control" required>
          </div>
          <div class="mb-3">
            <label class="form-label">Lokasi</label>
            <input type="text" name="lokasi" class="form-control" required>
          </div>
          <div class="mb-3">
            <label class="form-label">Kategori</label>
            <select name="kategori" class="form-select" required>
              <option value="">-- Pilih kategori --</option>
              <option value="Seminar">Seminar</option>
              <option value="Workshop">Workshop</option>
            </select>
          </div>
          <div class="mb-3">
            <label class="form-label">Kuota</label>
            <input type="number" name="kuota" class="form-control" required min="1">
          </div>
          <div class="mb-3">
            <label class="form-label">Deskripsi</label>
            <textarea name="deskripsi" class="form-control" rows="3" required></textarea>
          </div>
          <div class="mb-3">
            <label class="form-label">Upload Poster</label>
            <input type="file" name="poster" class="form-control" accept="image/*">
          </div>
          <input type="hidden" name="id_admin" value="<?= $id_admin?>">
          <button type="submit" name="submit" class="btn btn-primary">Simpan Event</button>
        </form>

      </div>
    </div>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
