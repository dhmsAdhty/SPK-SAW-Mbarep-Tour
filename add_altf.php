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
    <script src="https://cdn.tailwindcss.com"></script>
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
                <a href="#">
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
    <!-- 
    <section class="home-menu">
        <div class="content">
            <i class='bx bx-menu'></i>
            <span class="text">Menu</span>
        </div>
    </section> -->
    <section>
        <div class="dta-1">
            <p class="font-bold text-2xl text-[#1e3964];">Selamat Datang, <?php echo $username; ?>!</p>
        </div>
    </section>
    <div class="add-atf">
        <h2 class="font-bold text-xl">Tambahkan Data Alternatif</h2>

        <div class="form-group">
            <label for="nama">Nama Kandidat</label>
            <input type="text" id="nama" placeholder="Masukkan Nama Kandidat">

        </div>
        <button class="btn btn-secondary text-sm">
            <a href="data_alternatif.php">Kembali</a>
        </button>
        <button class="btn-save text-sm">Simpan</button>
    </div>
</body>

</html>