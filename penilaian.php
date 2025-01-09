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
$sqlKriteria = "SELECT id, nama FROM kriteria";
$resultKriteria = $conn->query($sqlKriteria);
$kriteria = [];

if ($resultKriteria->num_rows > 0) {
    while ($row = $resultKriteria->fetch_assoc()) {
        $kriteria[$row['id']] = $row['nama'];
    }
}

// Mengambil data alternatif
$queryAlternatif = "SELECT * FROM alternatif";
$resultAlternatif = $conn->query($queryAlternatif);

// Proses penilaian alternatif
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['nilai'])) {
    $nilai = $_POST['nilai'];

    // Memasukkan nilai ke dalam database
    foreach ($nilai as $id_alternatif => $kriteriaData) {
        foreach ($kriteriaData as $id_kriteria => $nilaiTerpilih) {
            $sql = "INSERT INTO nilai (id_alternatif, id_kriteria, nilai) VALUES (?, ?, ?)";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("iii", $id_alternatif, $id_kriteria, $nilaiTerpilih);
            $stmt->execute();
        }
    }

    // Setelah form disubmit, refresh halaman
    header("Location: penilaian.php");
    exit();
}

// Ambil nilai dari tabel nilai
$nilaiData = [];
$sqlNilai = "SELECT n.id_alternatif, n.id_kriteria, n.nilai, a.nama AS alternatif_nama 
             FROM nilai n
             JOIN alternatif a ON n.id_alternatif = a.id
             ORDER BY n.id_alternatif, n.id_kriteria";
$resultNilai = $conn->query($sqlNilai);

if ($resultNilai->num_rows > 0) {
    while ($row = $resultNilai->fetch_assoc()) {
        $nilaiData[$row['id_alternatif']][$row['id_kriteria']] = $row['nilai'];
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css" rel="stylesheet">
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
    <!-- Sidebar -->
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
                </a>
            </li>
            <li>
                <a href="data_alternatif.php">
                    <i class='bx bx-data'></i>
                    <span class="link_name">Data Alternatif</span>
                </a>
            </li>
            <li>
                <a href="data_kriteria.php">
                    <i class='bx bx-data'></i>
                    <span class="link_name">Data Kriteria</span>
                </a>
            </li>
            <li>
                <a href="penilaian.php">
                    <i class='bx bx-notepad'></i>
                    <span class="link_name">Penilaian Alternatif</span>
                </a>
            </li>
            <li>
                <a href="keputusan.php">
                    <i class='bx bx-notepad'></i>
                    <span class="link_name">Hasil Keputusan</span>
                </a>
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
        <h2 class="font-bold text-xl">Penilaian Data Tour Leader</h2>
        <h4 class="font-medium mt-4">Data Penilaian</h4>

        <form action="penilaian.php" method="POST">
            <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token']; ?>">
            <table class="table" style="width: 100%; border-collapse: collapse; border: 1px solid #ddd;">
                <thead>
                    <tr style="background-color: #f4f4f4;">
                        <th style="border: 1px solid #ddd; padding: 8px; text-align: center;">No</th>
                        <th style="border: 1px solid #ddd; padding: 8px; text-align: left;">Nama/Alternatif</th>
                        <?php foreach ($kriteria as $kri): ?>
                        <th style="border: 1px solid #ddd; padding: 8px; text-align: center;">
                            <?= htmlspecialchars($kri, ENT_QUOTES, 'UTF-8'); ?>
                        </th>
                        <?php endforeach; ?>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    if ($resultAlternatif->num_rows > 0) {
                        $no = 1;
                        while ($alternatif = $resultAlternatif->fetch_assoc()) {
                            echo "<tr>";
                            echo "<td>" . $no++ . "</td>";
                            echo "<td>" . htmlspecialchars($alternatif['nama'], ENT_QUOTES, 'UTF-8') . "</td>";

                            foreach ($kriteria as $id_kriteria => $kri) {
                                echo "<td>";
                                for ($i = 1; $i <= 5; $i++) {
                                    echo "<label><input type='checkbox' name='nilai[" . $alternatif['id'] . "][" . $id_kriteria . "]' value='$i'>$i</label> ";
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

            <button class="btn-save text-center text-sm" type="submit"
                style="margin-top: 20px; padding: 10px 20px; background-color: #1e3964;; color: white; border: none; cursor: pointer;">
                Submit Nilai
            </button>
        </form>


        <!-- Tabel Sudah di isi -->
        <div style="margin-top: 40px;">
            <h2>Tabel Nilai Yang Sudah Terisi</h2>
            <table class="table" style="border-collapse: collapse; width: 100%;">
                <thead>
                    <tr style="background-color: #f4f4f4;">
                        <th style="border: 1px solid #ddd; padding: 8px; text-align: center;">No</th>
                        <th style="border: 1px solid #ddd; padding: 8px; text-align: left;">Nama/Alternatif</th>
                        <?php foreach ($kriteria as $id_kriteria => $kri): ?>
                        <th style="border: 1px solid #ddd; padding: 8px; text-align: center;">
                            <?= htmlspecialchars($kri, ENT_QUOTES, 'UTF-8'); ?>
                        </th>
                        <?php endforeach; ?>
                        <th style="border: 1px solid #ddd; padding: 8px; text-align: center;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($nilaiData)): ?>
                    <?php $no = 1; ?>
                    <?php foreach ($nilaiData as $id_alternatif => $nilaiKriteria): ?>
                    <?php
                        // Mendapatkan nama alternatif
                        $sqlAlt = "SELECT nama FROM alternatif WHERE id = ?";
                        $stmt = $conn->prepare($sqlAlt);
                        $stmt->bind_param("i", $id_alternatif);
                        $stmt->execute();
                        $resultAlt = $stmt->get_result();
                        $alt = $resultAlt->fetch_assoc();

                        // Menyaring nilai yang terisi dan menghitung nilai maksimal dan minimal
                        $nilaiTerisi = array_filter($nilaiKriteria, function($nilai) {
                            return isset($nilai) && $nilai !== '';
                        });

                        $nilaiMaksimal = max($nilaiTerisi);  
                        $nilaiMinimal = min($nilaiTerisi); 
                    ?>
                    <tr>
                        <td style="border: 1px solid #ddd; padding: 8px; text-align: center;"><?= $no++; ?></td>
                        <td style="border: 1px solid #ddd; padding: 8px;">
                            <?= htmlspecialchars($alt['nama'], ENT_QUOTES, 'UTF-8'); ?>
                        </td>
                        <?php foreach ($kriteria as $id_kriteria => $kri): ?>
                        <td style="border: 1px solid #ddd; padding: 8px; text-align: center;">
                            <?= isset($nilaiKriteria[$id_kriteria]) ? htmlspecialchars($nilaiKriteria[$id_kriteria], ENT_QUOTES, 'UTF-8') : '-'; ?>
                        </td>
                        <?php endforeach; ?>

                        <td style="border: 1px solid #ddd; padding: 8px; text-align: center;">
                            <form action="delete_nilai.php" method="POST" style="display:inline;">
                                <input type="hidden" name="id_alternatif" value="<?= $id_alternatif; ?>">
                                <button type="submit"
                                    style="background-color: red; color: white; border: none; padding: 5px 10px; cursor: pointer;">Hapus</button>
                            </form>
                        </td>
                    </tr>
                    <?php endforeach; ?>

                    <!-- Baris untuk nilai minimal dan maksimal di bawah tabel -->
                    <tr>
                        <td style="border: 1px solid #ddd; padding: 8px; text-align: center;"></td>
                        <!-- Kolom kosong di awal -->
                        <td colspan="count($kriteria)"
                            style="border: 1px solid #ddd; padding: 8px; text-align: center;">
                            Nilai Maksimal
                        </td>
                        <?php foreach ($kriteria as $id_kriteria => $kri): ?>
                        <td style="border: 1px solid #ddd; padding: 8px; text-align: center;">
                            <?= htmlspecialchars(5, ENT_QUOTES, 'UTF-8'); ?>
                        </td>
                        <?php endforeach; ?>
                    </tr>
                    <tr>
                        <td style="border: 1px solid #ddd; padding: 8px; text-align: center;"></td>
                        <!-- Kolom kosong di awal -->
                        <td colspan="count($kriteria)"
                            style="border: 1px solid #ddd; padding: 8px; text-align: center;">
                            Nilai Minimal
                        </td>
                        <?php foreach ($kriteria as $id_kriteria => $kri): ?>
                        <td style="border: 1px solid #ddd; padding: 8px; text-align: center;">
                            <?= htmlspecialchars(1, ENT_QUOTES, 'UTF-8'); ?>
                        </td>
                        <?php endforeach; ?>
                    </tr>
                    <?php else: ?>
                    <tr>
                        <td colspan="6" style="border: 1px solid #ddd; padding: 8px; text-align: center;">
                            Tidak ada data nilai
                        </td>
                    </tr>
                    <?php endif; ?>

                </tbody>
            </table>
        </div>


    </div>
</body>

</html>