<?php
session_start();
include('config/db.php');

// Mengecek apakah pengguna sudah login
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Menampilkan data pengguna
$username = $_SESSION['username'] ?? 'Pengguna';


// Ambil jumlah data dari masing-masing tabel
$query_alternatif = "SELECT COUNT(*) AS total FROM alternatif";
$query_kriteria = "SELECT COUNT(*) AS total FROM kriteria";
// $query_hasil = "SELECT COUNT(*) AS total FROM hasil";
// $query_user = "SELECT COUNT(*) AS total FROM user";

$result_alternatif = $conn->query($query_alternatif);
$result_kriteria = $conn->query($query_kriteria);
// $result_hasil = $conn->query($query_hasil);
// $result_user = $conn->query($query_user);

$alternatif_count = $result_alternatif->fetch_assoc()['total'];
$kriteria_count = $result_kriteria->fetch_assoc()['total'];
// $hasil_count = $result_hasil->fetch_assoc()['total'];
// $user_count = $result_user->fetch_assoc()['total'];
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>

    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="main.css">
    <title>Dashboard</title>
    <?php
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}
?>
</head>

<body>
    <div class="sidebar">
        <div class="logo-details">
            <img class="logo" src="asset\image\image 1.png" alt="logo">
            <span class="nama-logo">MBAREP <br> TOUR</span>
        </div>

        <ul class="navlink">
            <li>
                <a href="dashboard.php" class="active">
                    <i class='bx bx-grid-alt'></i>
                    <span class="link_name">Dashboard</span>
            </li>
            <li>
                <a href="data_alternatif.php" class="active">
                    <i class='bx bx-data'></i>
                    <span class="link_name">Data Alternatif</span>
            </li>
            <li>
                <a href="data_kriteria.php" class="active">
                    <i class='bx bx-data'></i>
                    <span class="link_name">Data Kriteria</span>
            </li>
            <li>
                <a href="Penilaian.php" class="active">
                    <i class='bx bx-notepad'></i>
                    <span class="link_name">Penilaian Alternatif</span>
            </li>
            <li>
                <a href="keputusan.php" class="active">
                    <i class='bx bx-notepad'></i>
                    <span class="link_name">Hasil Keputusan</span>
            </li>
            <li>
                <form action="logout.php" method="POST">
                    <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
                    <button type="submit" class="logout-btn">
                        <i class='bx-log-out'></i>
                        Logout
                    </button>
                </form>
            </li>
        </ul>
    </div>
    <div class="utama">
        <h1 class="text-2xl font-bold text-left mt-10">Selamat Datang, <?php echo $username; ?></h1>
        <h2 class="text-lg text-left mt-2">Dashboard</h2>
    </div>
    <div class="card-container">
        <div class="card blue">
            <h3 class="card-title">
                <a href="data_alternatif.php">Data Alternatif</a>
            </h3>
            <p><?php echo $alternatif_count; ?></p>
        </div>
        <div class="card yellow">
            <h3 class="card-title">Data Kriteria</h3>
            <p><?php echo $kriteria_count; ?></p>
        </div>
    </div>

    <!-- 
    <section class="home-menu">
        <div class="content">
            <i class='bx bx-menu'></i>
            <span class="text">Menu</span>
        </div>
    </section> -->
</body>

</html>