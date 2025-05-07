<?php
include 'koneksi.php'; // Pastikan koneksi.php berfungsi dan ada

// Ambil id_event yang dipilih sebelumnya, bisa menggunakan GET
$id_event_terpilih = isset($_GET['id_event']) ? $_GET['id_event'] : null;

// Jika id_event terpilih ada, ambil informasi eventnya
$event = null;
if ($id_event_terpilih) {
    // Query untuk mengambil informasi event berdasarkan id_event
    $sql = "SELECT id_event, nama_event FROM event WHERE id_event = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id_event_terpilih);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $event = $result->fetch_assoc();
    } else {
        echo "Event tidak ditemukan."; // Tampilkan pesan jika event tidak ditemukan
    }
    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Daftar Event - Serievent.id</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
    <style>
        body {
            background-color: #e6f2ff;
            font-family: 'Poppins', sans-serif;
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
        .form-card {
            background: linear-gradient(135deg, #f0f7ff, #e1e8f1);
            border-radius: 20px;
            padding: 40px;
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.08);
            margin-top: 40px;
        }
        .form-title {
            color: #0072ff;
            font-weight: 700;
            margin-bottom: 30px;
            text-align: center;
        }
        .btn-custom {
            background: linear-gradient(to right, #00c6ff, #0072ff);
            color: white;
            font-weight: 600;
            border: none;
            padding: 12px 30px;
            border-radius: 12px;
            transition: 0.3s;
        }
        .btn-custom:hover {
            background: linear-gradient(to right, #0072ff, #0056c7);
        }
    </style>
</head>
<body>

<!-- Navbar -->
<nav class="navbar navbar-expand-lg">
    <div class="container">
        <a class="navbar-brand d-flex align-items-center" href="dashboard.php">
            <i class="bi bi-arrow-left-circle me-2" style="font-size: 1.2rem; color: white;"></i>
            <span>Serievent.id</span>
        </a>
    </div>
</nav>

<!-- Content -->
<div class="container">
    <div class="row justify-content-center">
        <div class="col-lg-6 col-md-8 col-sm-10">
            <div class="form-card">
                <h2 class="form-title">Formulir Pendaftaran</h2>
                <form action="proses_daftar.php" method="POST">
                    <div class="mb-3">
                        <label for="fullName" class="form-label">Nama Lengkap</label>
                        <input type="text" name="nama" class="form-control" id="fullName" placeholder="Masukkan nama lengkap" required>
                    </div>
                    <div class="mb-3">
                        <label for="email" class="form-label">Alamat Email</label>
                        <input type="email" name="email" class="form-control" id="email" placeholder="Masukkan email aktif" required>
                    </div>
                    <div class="mb-3">
                        <label for="phone" class="form-label">Nomor WhatsApp</label>
                        <input type="tel" name="whatsapp" class="form-control" id="phone" placeholder="Masukkan nomor WA" required>
                    </div>
                    <div class="mb-3">
                        <label for="eventChoice" class="form-label">Pilih Event</label>
                        <?php if ($event): ?>
                            <!-- Menampilkan nama event sesuai id_event -->
                            <input type="text" class="form-control" value="<?= htmlspecialchars($event['nama_event']) ?>" disabled>
                            <input type="hidden" name="id_event" value="<?= $event['id_event'] ?>"> <!-- id_event tetap terkirim ke server -->
                        <?php else: ?>
                            <input type="text" class="form-control" placeholder="Event tidak ditemukan" disabled>
                        <?php endif; ?>
                    </div>
                    <div class="mb-3">
                        <label for="institution" class="form-label">Asal Instansi / Universitas</label>
                        <input type="text" name="instansi" class="form-control" id="institution" placeholder="Contoh: Universitas Indonesia" required>
                    </div>
                    <div class="text-center mt-4">
                        <button type="submit" class="btn btn-custom">Daftar Sekarang</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
