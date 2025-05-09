<?php
session_start();
if (!isset($_SESSION['id_user'])) {
    header("Location: login.php");
    exit();
}

include 'koneksi.php';

// Ambil data dari form pendaftaran
if (isset($_POST['nama'], $_POST['email'], $_POST['whatsapp'], $_POST['instansi'], $_POST['id_event'])) {
    $id_user = $_SESSION['id_user'];
    $nama = $_POST['nama'];
    $email = $_POST['email'];
    $whatsapp = $_POST['whatsapp'];
    $instansi = $_POST['instansi'];
    $id_event = $_POST['id_event'];

    // Cek apakah user sudah mendaftar untuk event ini
    $cek_query = "SELECT * FROM pendaftaran WHERE id_user = ? AND id_event = ?";
    $stmt = $conn->prepare($cek_query);
    $stmt->bind_param("ii", $id_user, $id_event);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // User sudah terdaftar
        echo "<script>alert('Anda sudah mendaftar di event ini!'); window.location.href='dashboard.php';</script>";
    } else {
        // Simpan data pendaftaran
        $insert_query = "INSERT INTO pendaftaran (id_user, nama, email, whatsapp, instansi, id_event, tanggal_daftar) 
                         VALUES (?, ?, ?, ?, ?, ?, NOW())";
        $stmt = $conn->prepare($insert_query);
        $stmt->bind_param("issssi", $id_user, $nama, $email, $whatsapp, $instansi, $id_event);

        if ($stmt->execute()) {
            // Kurangi kuota setelah pendaftaran berhasil
            $update_kuota_query = "UPDATE event SET kuota = kuota - 1 WHERE id_event = ?";
            $stmt_update = $conn->prepare($update_kuota_query);
            $stmt_update->bind_param("i", $id_event);

            if ($stmt_update->execute()) {
                echo "<script>alert('Pendaftaran berhasil! Kuota event berhasil dikurangi.'); window.location.href='dashboard.php';</script>";
            } else {
                echo "<script>alert('Terjadi kesalahan saat memperbarui kuota event.'); window.history.back();</script>";
            }
            $stmt_update->close();
        } else {
            echo "<script>alert('Terjadi kesalahan saat mendaftar.'); window.history.back();</script>";
        }
    }
    $stmt->close();
    $conn->close();
} else {
    echo "<script>alert('Data tidak lengkap.'); window.history.back();</script>";
}
?>
