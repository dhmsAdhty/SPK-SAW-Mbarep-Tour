<?php
// Pastikan session sudah dimulai jika diperlukan
session_start();
include('config/db.php'); // Termasuk file konfigurasi untuk koneksi ke database

// Pastikan ada ID alternatif yang diterima dari form
if (isset($_POST['id_alternatif'])) {
    $id_alternatif = $_POST['id_alternatif'];

    // Cek apakah data valid dan hapus data terkait
    if ($id_alternatif) {
        // Query untuk menghapus data
        $sql = "DELETE FROM nilai WHERE id_alternatif = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $id_alternatif);
        if ($stmt->execute()) {
            // Redirect setelah data berhasil dihapus
            header("Location: penilaian.php"); // Ganti dengan halaman yang sesuai
            exit;
        } else {
            // Menangani kesalahan
            echo "Gagal menghapus data.";
        }
    }
}
?>