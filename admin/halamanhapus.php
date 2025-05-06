<?php
include 'koneksi.php'; // Pastikan koneksi ke database

// Pastikan id yang diterima adalah angka
$id_event = intval($_GET['id_event']);

// Ambil data event berdasarkan ID
$query = $koneksi->query ("SELECT * FROM event WHERE id_event = '$id_event'");
$row = $query->fetch_assoc();

if ($row) {
    $poster = $row['poster'];

    // Periksa apakah file poster ada sebelum menghapusnya
    if (file_exists("poster/$poster")) {
        unlink("poster/$poster");
    }

    // Query untuk menghapus event
    $delete_query = $koneksi->query("DELETE FROM event WHERE id_event = '$id_event'");

    if ($delete_query) {
        echo "<script>alert('event terhapus');</script>";
    } else {
        echo "<script>alert('Gagal menghapus event');</script>";
    }
} else {
    echo "<script>alert('Event tidak ditemukan');</script>";
}

// Redirect setelah memberikan pesan
echo "<script>location='tabel.php';</script>";
?>
