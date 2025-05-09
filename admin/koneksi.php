<?php
$koneksi = mysqli_connect("localhost", "root", "", "seri_event");

// Cek koneksi
if (!$koneksi) {
    echo "Koneksi database gagal: " . mysqli_connect_error();
    exit;
}
?>
