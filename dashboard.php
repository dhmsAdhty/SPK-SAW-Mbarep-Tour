<?php
session_start();

// Mengecek apakah pengguna sudah login
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Menampilkan data pengguna
$username = $_SESSION['username'] ?? 'Pengguna';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <link rel="stylesheet" href="main.css">
    <title>Dashboard</title>
</head>
<body>
    <div class="sidebar">
        <div class="logo-details">
            <img class="logo" src="asset\image\image 1.png" alt="logo">
            <span class="nama-logo">MBAREP <br> TOUR</span>
        </div>

        <ul class="navlink">
            <li>
                <a href="#" class="active">
                    <i class='bx bx-grid-alt'></i>
                    <span class="link_name">Dashboard</span>
            </li>
            <li>
                <a href="#" class="active">
                <i class='bx bx-data'></i>
                    <span class="link_name">Data Alternatif</span>
            </li>
            <li>
                <a href="#" class="active">
                <i class='bx bx-data'></i>
                    <span class="link_name">Data Kriteria</span>
            </li>
            <li>
                <a href="#" class="active">
                <i class='bx bx-notepad'></i>
                    <span class="link_name">Penilaian Alternatif</span>
            </li>
            <li>
                <a href="#" class="active">
                <i class='bx bx-notepad'></i>
                    <span class="link_name">Hasil Keputusan</span>
            </li>
        </ul>
    </div>
</body>
</html>
