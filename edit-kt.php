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

// Query untuk mendapatkan data berdasarkan ID
$sql = "SELECT * FROM kriteria WHERE id = $id";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
} else {
    echo "Data tidak ditemukan!";
    exit();
}

// Cek apakah form disubmit untuk update
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nama = $_POST['nama'];

    // Query untuk mengupdate data kriteria
    $sql = "UPDATE kriteria SET nama = '$nama' WHERE id = $id";

    if ($conn->query($sql) === TRUE) {
        echo "Data berhasil diperbarui";
        header("Location: data_kriteria.php"); // Redirect ke halaman data alternatif
        exit();
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <link rel="stylesheet" href="main.css">
    <script src="https://cdn.tailwindcss.com"></script>
    <title>Edit Data Alternatif</title>
</head>

<body>
    <div class="sidebar">
        <div class="logo-details">
            <img class="logo" src="asset\image\image 1.png" alt="logo">
            <span class="nama-logo">MBAREP <br> TOUR</span>
        </div>

        <ul class="navlink">
            <li>
                <a href="dashboard.php">
                    <i class='bx bx-grid-alt'></i>
                    <span class="link_name">Dashboard</span>
            </li>
            <li>
                <a href="data_alternatif.php" ">
                    <i class='bx bx-data'></i>
                    <span class=" link_name">Data Alternatif</span>
            </li>
            <li>
                <a href="data_kriteria.php">
                    <i class='bx bx-data'></i>
                    <span class="link_name">Data Kriteria</span>
            </li>
            <li>
                <a href="#">
                    <i class='bx bx-notepad'></i>
                    <span class="link_name">Penilaian Alternatif</span>
            </li>
            <li>
                <a href="#">
                    <i class='bx bx-notepad'></i>
                    <span class="link_name">Hasil Keputusan</span>
            </li>
            <li>
                <form action="logout.php" method="POST">
                    <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
                    <button type="submit" class="logout-btn">
                        <i class='bx bx-log-out'></i>
                        Logout
                    </button>
                </form>
            </li>
        </ul>
    </div>

    <div class="add-atf">
        <h2 class="font-bold text-xl">Edit Data Kriteria</h2>
        <form action="edit.php?id=<?= $id; ?>" method="POST">
            <div class="form-group">
                <label for="nama">Nama Kriteria</label>
                <input type="text" id="nama" name="nama" value="<?= $row['nama']; ?>" required>
            </div>
            <button type="submit" class="btn-save text-sm">Update</button>
        </form>
    </div>
</body>

</html>