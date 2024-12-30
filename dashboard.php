<?php
session_start();

// Mengecek apakah pengguna sudah login
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php"); // Jika belum login, arahkan ke halaman login
    exit();
}

// Menampilkan data pengguna
echo "Selamat datang, " . $_SESSION['username'];
?>

<!-- Tampilan dashboard -->
<h1>Dashboard</h1>
<!-- Konten lainnya di dashboard -->
