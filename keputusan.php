<?php

// Koneksi ke database
$dsn = 'mysql:host=localhost;dbname=spk-saw';
$username = 'dhimas';
$password = ')E]2q4aCyuRLGHL!';

try {
    $pdo = new PDO($dsn, $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Ambil data kriteria
    $queryKriteria = "SELECT * FROM kriteria";
    $stmtKriteria = $pdo->prepare($queryKriteria);
    $stmtKriteria->execute();
    $kriteria = $stmtKriteria->fetchAll(PDO::FETCH_ASSOC);

    // Ambil data alternatif dan nilai
    $query = "
        SELECT a.nama AS nama_alternatif, k.nama AS kriteria, n.nilai
        FROM alternatif a
        JOIN nilai n ON a.id = n.id_alternatif
        JOIN kriteria k ON n.id_kriteria = k.id
    ";
    $stmt = $pdo->prepare($query);
    $stmt->execute();
    $data = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Normalisasi data
    $nilaiMaksimal = 5; // Nilai maksimum untuk normalisasi
    $normalisasi = [];

    // Menyusun data ke dalam array normalisasi berdasarkan alternatif dan kriteria
    foreach ($data as $row) {
        $normalisasi[$row['nama_alternatif']][$row['kriteria']] = $row['nilai'] / $nilaiMaksimal;
    }

    // Debugging: Menampilkan data normalisasi untuk verifikasi
    // echo '<pre>'; print_r($normalisasi); echo '</pre>';

} catch (PDOException $e) {
    die("Koneksi gagal: " . $e->getMessage());
}
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="main.css">
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
        <div>
            <h2 class="text-xl font-bold">Tabel Hasil Normalisasi</h2>
            <p>
                <strong>Normalisasi</strong> adalah proses mengubah nilai data ke dalam skala yang sama, biasanya
                antara 0 hingga 1. <br>Menggunakan rumus nilai normalisasi = nilai asli / nilai maksimal.
            </p>
            <table class="table" style="border-collapse: collapse; width: 100%;">
                <thead>
                    <tr style="background-color: #f4f4f4;">
                        <th style="border: 1px solid #ddd; padding: 8px; text-align: center;">No</th>
                        <th style="border: 1px solid #ddd; padding: 8px; text-align: left;">Nama/Alternatif</th>
                        <?php foreach ($kriteria as $id_kriteria => $kri): ?>
                        <th style="border: 1px solid #ddd; padding: 8px; text-align: center;">
                            <?= htmlspecialchars($kri['nama'], ENT_QUOTES, 'UTF-8'); ?>
                        </th>
                        <?php endforeach; ?>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($normalisasi)): ?>
                    <?php $no = 1; ?>
                    <?php foreach ($normalisasi as $nama_alternatif => $nilaiKriteria): ?>
                    <tr>
                        <td style="border: 1px solid #ddd; padding: 8px; text-align: center;"><?= $no++; ?></td>
                        <td style="border: 1px solid #ddd; padding: 8px;">
                            <?= htmlspecialchars($nama_alternatif, ENT_QUOTES, 'UTF-8'); ?>
                        </td>
                        <?php foreach ($kriteria as $kri): ?>
                        <td style="border: 1px solid #ddd; padding: 8px; text-align: center;">
                            <?= isset($nilaiKriteria[$kri['nama']]) ? number_format($nilaiKriteria[$kri['nama']], 3) : '-'; ?>
                        </td>
                        <?php endforeach; ?>

                    </tr>
                    <?php endforeach; ?>

                    <?php endif; ?>
                </tbody>
            </table>
        </div>

        <!-- Tabel Nilai Preferensi ranking -->
        <div>
            <h2 class="text-xl font-bold mt-8">Tabel Nilai Preferensi Ranking</h2>
            <p>
                <strong>Nilai preferensi</strong> adalah hasil perkalian antara nilai normalisasi dengan bobot kriteria.
                Nilai preferensi kemudian dijumlahkan untuk mendapatkan total preferensi.
            </p>
            <table class="table" style="border-collapse: collapse; width: 100%;">
                <thead>
                    <tr style="background-color: #f4f4f4;">
                        <th style="border: 1px solid #ddd; padding: 8px; text-align: center;">No</th>
                        <th style="border: 1px solid #ddd; padding: 8px; text-align: left;">Nama/Alternatif</th>
                        <?php foreach ($kriteria as $id_kriteria => $kri):?>
                        <th style="border: 1px solid #ddd; padding: 8px; text-align: center;">
                            <?= htmlspecialchars($kri['nama'], ENT_QUOTES, 'UTF-8'); ?>
                        </th>
                        <?php endforeach;?>
                        <th style="border: 1px solid #ddd; padding: 8px; text-align: center;">Total</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($normalisasi)): ?>
                    <?php 
        // Membuat array baru untuk menyimpan nama alternatif dan total preferensi
        $alternatifWithTotal = [];

        // Menghitung total preferensi untuk setiap alternatif
        foreach ($normalisasi as $nama_alternatif => &$nilaiKriteria) {
            $total = 0;
            foreach ($kriteria as $kri) {
                $nilai = isset($nilaiKriteria[$kri['nama']]) ? $nilaiKriteria[$kri['nama']] : 0;
                $bobot = isset($kri['bobot']) ? $kri['bobot'] : 0;
                // Hitung preferensi dan jumlahkan untuk total
                $preferensi = $nilai * $bobot;
                $total += $preferensi;
            }
            // Menyimpan nama alternatif dan total preferensi
            $alternatifWithTotal[] = ['nama' => $nama_alternatif, 'total' => $total];
        }

        // Urutkan berdasarkan total preferensi tertinggi
        usort($alternatifWithTotal, function($a, $b) {
            return $b['total'] <=> $a['total']; // Urutkan dari yang terbesar
        });
    ?>
                    <?php $no = 1; ?>
                    <?php foreach ($alternatifWithTotal as $alternatif): ?>
                    <tr>
                        <td style="border: 1px solid #ddd; padding: 8px; text-align: center;"><?= $no++; ?></td>
                        <td style="border: 1px solid #ddd; padding: 8px;">
                            <?= htmlspecialchars($alternatif['nama'], ENT_QUOTES, 'UTF-8'); ?>
                        </td>
                        <?php 
            // Ambil data kriteria untuk alternatif yang sedang diproses
            $nilaiKriteria = $normalisasi[$alternatif['nama']];
            $total = $alternatif['total'];
        ?>
                        <?php foreach ($kriteria as $kri): ?>
                        <td style="border: 1px solid #ddd; padding: 8px; text-align: center;">
                            <?php
            $nilai = isset($nilaiKriteria[$kri['nama']]) ? $nilaiKriteria[$kri['nama']] : 0;
            $bobot = isset($kri['bobot']) ? $kri['bobot'] : 0;
            // Hitung preferensi
            $preferensi = $nilai * $bobot;
            ?>
                            <?= number_format($preferensi, 3); ?>
                        </td>
                        <?php endforeach; ?>
                        <td style="border: 1px solid #ddd; padding: 8px; text-align: center;">
                            <?= number_format($total, 3); ?>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>


        </div>
    </div>
    </div>
</body>

</html>