<?php
include('config/db.php');
session_start();

// Mengecek apakah pengguna sudah login
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Cek apakah form disubmit
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nama = $_POST['nama'];
    $bobot = $_POST['bobot'];  // Pastikan ini menangkap nilai desimal
    
    // Query untuk menambahkan data kriteria
    $sql = "INSERT INTO kriteria (nama, bobot) VALUES ('$nama', '$bobot')";

    if ($conn->query($sql) === TRUE) {
        echo "Data berhasil ditambahkan";
        header("Location: data_kriteria.php"); // Redirect ke halaman data kriteria
        exit();
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}

?>