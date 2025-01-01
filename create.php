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
    
    // Query untuk menambahkan data alternatif
    $sql = "INSERT INTO alternatif (nama) VALUES ('$nama')";
    
    if ($conn->query($sql) === TRUE) {
        echo "Data berhasil ditambahkan";
        header("Location: data_alternatif.php"); // Redirect ke halaman data alternatif
        exit();
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}
?>