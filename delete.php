<?php
include('config/db.php');
session_start();

// Mengecek apakah pengguna sudah login
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Cek apakah ada ID di URL
if (!isset($_GET['id'])) {
    echo "ID tidak ditemukan!";
    exit();
}

$id = $_GET['id'];

// Query untuk menghapus data berdasarkan ID
$sql = "DELETE FROM alternatif WHERE id = $id";

if ($conn->query($sql) === TRUE) {
    echo "Data berhasil dihapus";
    header("Location: data_alternatif.php"); // Redirect ke halaman data alternatif
    exit();
} else {
    echo "Error: " . $sql . "<br>" . $conn->error;
}
?>