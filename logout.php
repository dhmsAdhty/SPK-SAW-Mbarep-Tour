<?php
session_start();

// Validasi CSRF Token
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
        die('Akses ditolak: Token tidak valid.');
    }

    // Hapus semua sesi
    session_unset();
    session_destroy();

    // Redirect ke halaman login
    header("Location: login.php");
    exit();
}
?>
