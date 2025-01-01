<?php
session_start();
require_once 'config/db.php';

// Mengecek apakah pengguna sudah login
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Menampilkan data pengguna
$username = $_SESSION['username'] ?? 'Pengguna';
// Mengambil data kriteria
$sql = "SELECT * FROM kriteria"; 
$result = $conn->query($sql);
if ($result->num_rows > 0) {
    $kriteria = [];
    while($row = $result->fetch_assoc()) {
        $kriteria[] = $row['nama']; // misalnya nama kriteria ada di kolom 'nama_kriteria'
    }
}
// Mengambil data alternatif
$query = "SELECT * FROM alternatif"; 
$result = $conn->query($query);

// Mengambil data alternatif
$queryAlternatif = "SELECT * FROM alternatif"; 
$resultAlternatif = $conn->query($queryAlternatif);

// Mengambil data kriteria
$queryKriteria = "SELECT * FROM kriteria";
$resultKriteria = $conn->query($queryKriteria);

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
                <a href="data_kriteria.php">
                    <i class='bx bx-data'></i>
                    <span class="link_name">Data Kriteria</span>
            </li>
            <li>
                <a href="penilaian.php">
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
    <div>
        <div class="add-atf">
            <h2 class="font-bold text-xl">Penilaian Data Tour Leader</h2>
            <h4 class="font-medium mt-4">Data Penilaian</h4>
            <table class="table" style="width: 100%; border-collapse: collapse; border: 1px solid #ddd;">
                <thead>
                    <tr style="background-color: #f4f4f4;">
                        <th style="border: 1px solid #ddd; padding: 8px; text-align: center;">No</th>
                        <th style="border: 1px solid #ddd; padding: 8px; text-align: left;">Nama/Alternatif</th>
                        <?php foreach ($kriteria as $kri): ?>
                        <th style="border: 1px solid #ddd; padding: 8px; text-align: center;">
                            <?= htmlspecialchars($kri, ENT_QUOTES, 'UTF-8'); ?></th>
                        <?php endforeach; ?>
                        <th>Action</th>
                    </tr>

                </thead>
                <tbody>
                    <?php
        // Menampilkan data alternatif di tbody
        if ($resultAlternatif->num_rows > 0) {
            $no = 1;
            while ($alternatif = $resultAlternatif->fetch_assoc()) {
                echo "<tr>";
                echo "<td>" . $no++ . "</td>";
                echo "<td>" . htmlspecialchars($alternatif['nama'], ENT_QUOTES, 'UTF-8') . "</td>";
                // Reset hasil kriteria dan tampilkan radio button untuk setiap kriteria
                $resultKriteria->data_seek(0); // Reset hasil kriteria
                while ($kriteria = $resultKriteria->fetch_assoc()) {
                    // Menampilkan radio button untuk setiap kriteria
                    echo "<td>";
                    for ($i = 1; $i <= 5; $i++) {
                        echo "<input type='checkbox' name='alternatif_" . $alternatif['id'] . "_kriteria_" . $kriteria['id'] . "' value='$i'>$i ";
                    }
                    echo "</td>";
                }
                echo "</tr>";
            }
        } else {
            echo "<tr><td colspan='100%' style='text-align:center;'>Data Alternatif Tidak Ditemukan</td></tr>";
        }
        ?>
                </tbody>
            </table>

</body>

</html>