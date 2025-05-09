<?php
session_start();
include '../koneksi.php';

if (isset($_POST['submit'])) {
    // Ambil data dari form
    $nama_event = mysqli_real_escape_string($conn, $_POST['nama_event']);
    $tanggal = $_POST['tanggal'];
    $waktu = $_POST['waktu'];
    $lokasi = mysqli_real_escape_string($conn, $_POST['lokasi']);
    $kategori = $_POST['kategori'];
    $kuota = intval($_POST['kuota']);
    $deskripsi = mysqli_real_escape_string($conn, $_POST['deskripsi']);
    $id_admin = $_POST['id_admin'];

    // Upload file poster
    $poster = '';
    if ($_FILES['poster']['name']) {
        $folder = 'poster/';
        if (!is_dir($folder)) {
            mkdir($folder, 0777, true);
        }

        $file_tmp = $_FILES['poster']['tmp_name'];
        $file_name = time() . '_' . basename($_FILES['poster']['name']);
        $target = $folder . $file_name;

        if (move_uploaded_file($file_tmp, $target)) {
            $poster = $file_name;
        } else {
            $_SESSION['pesan'] = [
                'type' => 'gagal',
                'isi' => 'Gagal mengunggah poster.'
            ];
            header("Location: upload.php");
            exit;
        }
    }

    // Simpan ke database dengan status 'menunggu'
    $query = "INSERT INTO event (nama_event, tanggal, waktu, lokasi, kategori, kuota, deskripsi, poster, status, id_admin) 
                VALUES ('$nama_event', '$tanggal', '$waktu', '$lokasi', '$kategori', $kuota, '$deskripsi', '$poster', 'menunggu', '$id_admin')";

    if (mysqli_query($conn, $query)) {
        $_SESSION['pesan'] = [
            'type' => 'berhasil',
            'isi' => 'Event berhasil disimpan dan menunggu konfirmasi!'
        ];
    } else {
        $_SESSION['pesan'] = [
            'type' => 'gagal',
            'isi' => 'Gagal menyimpan event: ' . mysqli_error($conn)
        ];
    }

    header("Location: upload.php");
    exit;
} else {
    // Jika akses langsung tanpa submit
    $_SESSION['pesan'] = [
        'type' => 'gagal',
        'isi' => 'Akses tidak valid.'
    ];
    header("Location: upload.php");
    exit;
}
