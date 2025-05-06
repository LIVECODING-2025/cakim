<?php
session_start();
include 'koneksi.php';

if (!isset($_GET['id_event'])) {
    $_SESSION['pesan'] = ['type' => 'gagal', 'isi' => 'ID event tidak ditemukan.'];
    header("Location: tabel.php");
    exit;
}

$id_event = intval($_GET['id_event']);
$query = mysqli_query($koneksi, "SELECT * FROM event WHERE id_event = $id_event");
$data = mysqli_fetch_assoc($query);

if (!$data) {
    $_SESSION['pesan'] = ['type' => 'gagal', 'isi' => 'Data event tidak ditemukan.'];
    header("Location: tabel.php");
    exit;
}

if (isset($_POST['submit'])) {
    $nama_event = mysqli_real_escape_string($koneksi, $_POST['nama_event']);
    $tanggal = $_POST['tanggal'];
    $waktu = $_POST['waktu'];
    $lokasi = mysqli_real_escape_string($koneksi, $_POST['lokasi']);
    $kategori = $_POST['kategori'];
    $kuota = intval($_POST['kuota']);
    $deskripsi = mysqli_real_escape_string($koneksi, $_POST['deskripsi']);

    $poster = $data['poster'];
    if ($_FILES['poster']['name']) {
        $folder = 'poster/';
        $file_tmp = $_FILES['poster']['tmp_name'];
        $file_name = time() . '_' . basename($_FILES['poster']['name']);
        $target = $folder . $file_name;

        if (move_uploaded_file($file_tmp, $target)) {
            if (!empty($data['poster']) && file_exists("poster/" . $data['poster'])) {
                unlink("poster/" . $data['poster']);
            }
            $poster = $file_name;
        } else {
            $_SESSION['pesan'] = ['type' => 'gagal', 'isi' => 'Gagal mengunggah poster baru.'];
            header("Location: edit_event.php?id_event=$id_event");
            exit;
        }
    }

    $update = mysqli_query($koneksi, "UPDATE event SET 
        nama_event = '$nama_event',
        tanggal = '$tanggal',
        waktu = '$waktu',
        lokasi = '$lokasi',
        kategori = '$kategori',
        kuota = $kuota,
        deskripsi = '$deskripsi',
        poster = '$poster'
        WHERE id_event = $id_event");

    if ($update) {
        $_SESSION['pesan'] = ['type' => 'berhasil', 'isi' => 'Data event berhasil diperbarui.'];
    } else {
        $_SESSION['pesan'] = ['type' => 'gagal', 'isi' => 'Gagal memperbarui data event.'];
    }

    header("Location: tabel.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Edit Event - Serievent.id</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" />
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" />
  <style>
    body { background: #e9f1fb; overflow-x: hidden; }
    .navbar { background: linear-gradient(90deg, #00b2ff, #007aff); position: sticky; top: 0; z-index: 1030; }
    .navbar-brand { font-weight: bold; font-style: italic; color: white; }
    .navbar-nav .nav-link { color: white; margin-left: 15px; }
    .nav-link-btn { padding: 10px 15px; border-radius: 10px; color: #007aff; font-weight: 500; text-decoration: none; }
    .nav-link-btn.active, .nav-link-btn:hover { background: #007aff; color: white; font-weight: 600; box-shadow: 0 4px 10px rgba(0, 122, 255, 0.4); }
    .main-content { background: white; min-height: 500px; box-shadow: 0 4px 10px rgba(0, 0, 0, 0.05); padding: 1.5rem; border-radius: 10px; }
    .section-title h2 { color: #007aff; font-weight: bold; }
    .poster-img { width: 100%; max-height: 150px; object-fit: contain; }
  </style>
</head>
<body>
<nav class="navbar navbar-expand-lg">
  <div class="container">
    <a class="navbar-brand" href="#">Serievent.id</a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse justify-content-end" id="navbarNav">
      <div class="navbar-nav">
        <a class="nav-link text-white" href="#">About</a>
        <a class="nav-link text-white" href="#">Account</a>
      </div>
    </div>
  </div>
</nav>

<div class="container-fluid my-4">
  <div class="row g-4">
    <div class="col-md-3">
      <div class="p-3 bg-light rounded d-flex flex-column gap-2" style="height: calc(100vh - 70px); position: sticky; top: 70px;">
        <a href="dashboard.html" class="nav-link-btn d-flex align-items-center"><i class="fas fa-home me-2"></i> Dashboard</a>
        <a href="#" class="nav-link-btn d-flex align-items-center"><i class="fas fa-calendar-alt me-2"></i> Event</a>
        <a href="seminar.html" class="nav-link-btn d-flex align-items-center"><i class="fas fa-chalkboard-teacher me-2"></i> Seminar</a>
        <a href="workshop.html" class="nav-link-btn d-flex align-items-center"><i class="fas fa-tools me-2"></i> Workshop</a>
        <a href="upload.php" class="nav-link-btn d-flex align-items-center"><i class="fas fa-cloud-upload-alt me-2"></i> Upload Event</a>
        <a href="edit_event.php" class="nav-link-btn active d-flex align-items-center">
        <i class="fas fa-edit me-2"></i> Edit Event</a>
        <a href="tabel.php" class="nav-link-btn d-flex align-items-center"><i class="fas fa-table me-2"></i> Tabel Event</a>
        <a href="konfirmasi.php" class="nav-link-btn d-flex align-items-center"><i class="fas fa-check-square me-2"></i> Konfirmasi Event</a>
      </div>
    </div>

    <div class="col-md-9">
      <div class="bg-white p-4 rounded shadow">
        <h2 class="text-primary mb-4">Edit Event</h2>

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

        <form method="POST" enctype="multipart/form-data">
          <div class="mb-3">
            <label class="form-label">Nama Event</label>
            <input type="text" name="nama_event" class="form-control" value="<?= htmlspecialchars($data['nama_event']) ?>" required>
          </div>
          <div class="mb-3">
            <label class="form-label">Tanggal</label>
            <input type="date" name="tanggal" class="form-control" value="<?= $data['tanggal'] ?>" required>
          </div>
          <div class="mb-3">
            <label class="form-label">Waktu</label>
            <input type="time" name="waktu" class="form-control" value="<?= $data['waktu'] ?>" required>
          </div>
          <div class="mb-3">
            <label class="form-label">Lokasi</label>
            <input type="text" name="lokasi" class="form-control" value="<?= htmlspecialchars($data['lokasi']) ?>" required>
          </div>
          <div class="mb-3">
            <label class="form-label">Kategori</label>
            <select name="kategori" class="form-select" required>
              <option value="Seminar" <?= $data['kategori'] == 'Seminar' ? 'selected' : '' ?>>Seminar</option>
              <option value="Workshop" <?= $data['kategori'] == 'Workshop' ? 'selected' : '' ?>>Workshop</option>
            </select>
          </div>
          <div class="mb-3">
            <label class="form-label">Kuota</label>
            <input type="number" name="kuota" class="form-control" value="<?= $data['kuota'] ?>" required min="1">
          </div>
          <div class="mb-3">
            <label class="form-label">Deskripsi</label>
            <textarea name="deskripsi" class="form-control" rows="4" required><?= htmlspecialchars($data['deskripsi']) ?></textarea>
          </div>
          <div class="mb-3">
            <label class="form-label">Poster Saat Ini:</label><br>
            <?php if ($data['poster']): ?>
              <img src="poster/<?= $data['poster'] ?>" class="poster-img mb-2">
            <?php else: ?>
              <p><i>Tidak ada poster</i></p>
            <?php endif; ?>
            <input type="file" name="poster" class="form-control mt-2" accept="image/*">
          </div>
          <button type="submit" name="submit" class="btn btn-primary">Update Event</button>
          <a href="tabel.php" class="btn btn-secondary">Batal</a>
        </form>

      </div>
    </div>
  </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
