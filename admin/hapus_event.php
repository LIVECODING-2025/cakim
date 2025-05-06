<?php
session_start();
include 'koneksi.php';

if (!isset($_GET['id_event'])) {
    $_SESSION['pesan'] = [
        'type' => 'gagal',
        'isi' => 'ID event tidak ditemukan.'
    ];
    header("Location: tabel.php");
    exit;
}

$id_event = intval($_GET['id_event']);

// Ambil data poster dulu
$query = mysqli_query($koneksi, "SELECT poster FROM event WHERE id_event = $id_event");
$data = mysqli_fetch_assoc($query);

if (!$data) {
    $_SESSION['pesan'] = [
        'type' => 'gagal',
        'isi' => 'Data event tidak ditemukan.'
    ];
    header("Location: tabel.php");
    exit;
}

// Hapus poster jika ada
if (!empty($data['poster']) && file_exists("poster/" . $data['poster'])) {
    unlink("poster/" . $data['poster']);
}

// Hapus data dari database
$hapus = mysqli_query($koneksi, "DELETE FROM event WHERE id_event = $id_event");

if ($hapus) {
    $_SESSION['pesan'] = [
        'type' => 'berhasil',
        'isi' => 'Event berhasil dihapus.'
    ];
} else {
    $_SESSION['pesan'] = [
        'type' => 'gagal',
        'isi' => 'Gagal menghapus event.'
    ];
}

header("Location: tabel.php");
exit;
?>
