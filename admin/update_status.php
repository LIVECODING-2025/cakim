<?php
session_start();
include '../koneksi.php';

if (isset($_GET['id']) && isset($_GET['status'])) {
    $id_event = intval($_GET['id']);
    $status = $_GET['status'];

    // Validasi status yang bisa diterima
    if ($status == 'disetujui' || $status == 'ditolak') {
        // Update status event
        $query = "UPDATE event SET status = '$status' WHERE id_event = $id_event";

        if ($conn->query($query)) {
            $_SESSION['pesan'] = [
                'type' => 'berhasil',
                'isi' => 'Status event berhasil diperbarui!'
            ];
        } else {
            $_SESSION['pesan'] = [
                'type' => 'gagal',
                'isi' => 'Gagal memperbarui status event: ' . $conn->error
            ];
        }
    } else {
        $_SESSION['pesan'] = [
            'type' => 'gagal',
            'isi' => 'Status tidak valid.'
        ];
    }
} else {
    $_SESSION['pesan'] = [
        'type' => 'gagal',
        'isi' => 'Data tidak lengkap.'
    ];
}

header("Location: konfirmasi.php");
exit;
