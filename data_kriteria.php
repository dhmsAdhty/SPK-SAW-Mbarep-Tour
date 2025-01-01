<?php
session_start();
include 'config/db.php'; // Menyertakan file koneksi database

// Mengecek apakah pengguna sudah login
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Menampilkan data pengguna
$username = $_SESSION['username'] ?? 'Pengguna';

// Mengambil data alternatif
$query = "SELECT * FROM kriteria"; 
$result = $conn->query($query);

// Pagination settings
$limit = 5; // Number of records per page
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($page - 1) * $limit;

// Query data with LIMIT and OFFSET
$query = "SELECT * FROM kriteria LIMIT $limit OFFSET $offset";
$result = $conn->query($query);

// Total records for pagination
$total_query = "SELECT COUNT(*) as total FROM kriteria";
$total_result = $conn->query($total_query);
$total_records = $total_result->fetch_assoc()['total'];
$total_pages = ceil($total_records / $limit);

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
    <div>
        <div class="add-atf">
            <h2 class="font-bold text-xl">Tambahkan Data Kriteria</h2>
            <form action="create-kt.php" method="POST" class="form-container">
                <div class="form-group">
                    <label for="nama">Nama Kriteria</label>
                    <input type="text" id="nama" name="nama" placeholder="Masukkan Kriteria" required>
                </div>
                <div class="form-group">
                    <label for="bobot">Bobot</label>
                    <input type="number" id="bobot" name="bobot" placeholder="Masukkan Bobot" step="0.01">
                </div>
                <button type="submit" class="btn-save text-center text-sm">Simpan</button>
            </form>

            <h2 class="font-bold text-xl mt-4">Data Kriteria</h2>
            <table class="table">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Nama</th>
                        <th>Bobot</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                    if ($result->num_rows > 0): 
                         $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
                         $perPage = 5; 
                         $startNo = ($page - 1) * $perPage + 1; 
                         $no = $startNo;
                            ?>
                    <?php while($row = $result->fetch_assoc()): ?>
                    <tr>
                        <td data-label="No"><?= $no++; ?></td>
                        <td data-label="Kriteria"><?= $row['nama']; ?></td>
                        <td data-label="Bobot"><?= $row['bobot']; ?></td>
                        <td data-label="Action">
                            <a href="edit-kt.php?id=<?= $row['id']; ?>" title="Edit">
                                <i class='bx bx-edit'></i>
                            </a> |
                            <a href="delete-kt.php?id=<?= $row['id']; ?>" title="Delete"
                                onclick="return confirm('Yakin ingin menghapus data ini?')">
                                <i class='bx bx-trash'></i>
                            </a>
                        </td>
                    </tr>
                    <?php endwhile; ?>
                    <?php else: ?>
                    <tr>
                        <td colspan="3">Tidak ada data Kriteria</td>
                    </tr>
                    <?php endif; ?>
                </tbody>

            </table>

            <!-- Pagination -->
            <div class="flex items-center justify-between border-t border-gray-200 bg-white px-4 py-3 sm:px-6">
                <div class="flex flex-1 justify-between sm:hidden">
                    <a href="?page=<?= max(1, $page - 1) ?>"
                        class="relative inline-flex items-center rounded-md border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50">Previous</a>
                    <a href="?page=<?= min($total_pages, $page + 1) ?>"
                        class="relative ml-3 inline-flex items-center rounded-md border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50">Next</a>
                </div>
                <div class="hidden sm:flex sm:flex-1 sm:items-center sm:justify-between">
                    <div>
                        <p class="text-sm text-gray-700">
                            Showing
                            <span class="font-medium"><?= ($offset + 1) ?></span>
                            to
                            <span class="font-medium"><?= min($offset + $limit, $total_records) ?></span>
                            of
                            <span class="font-medium"><?= $total_records ?></span>
                            results
                        </p>
                    </div>
                    <div>
                        <nav class="isolate inline-flex -space-x-px rounded-md shadow-sm" aria-label="Pagination">
                            <?php if ($page > 1): ?>
                            <a href="?page=<?= $page - 1 ?>"
                                class="relative inline-flex items-center rounded-l-md px-2 py-2 text-gray-400 ring-1 ring-inset ring-gray-300 hover:bg-gray-50 focus:z-20 focus:outline-offset-0">
                                <span class="sr-only">Previous</span>
                                <svg class="size-5" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                    <path fill-rule="evenodd"
                                        d="M11.78 5.22a.75.75 0 0 1 0 1.06L8.06 10l3.72 3.72a.75.75 0 1 1-1.06 1.06l-4.25-4.25a.75.75 0 0 1 0-1.06l4.25-4.25a.75.75 0 0 1 1.06 0Z"
                                        clip-rule="evenodd" />
                                </svg>
                            </a>
                            <?php endif; ?>

                            <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                            <a href="?page=<?= $i ?>"
                                class="relative inline-flex items-center px-4 py-2 text-sm font-semibold <?= $i == $page ? 'bg-indigo-600 text-white' : 'text-gray-900 ring-1 ring-inset ring-gray-300 hover:bg-gray-50' ?> focus:z-20 focus:outline-offset-0"><?= $i ?></a>
                            <?php endfor; ?>

                            <?php if ($page < $total_pages): ?>
                            <a href="?page=<?= $page + 1 ?>"
                                class="relative inline-flex items-center rounded-r-md px-2 py-2 text-gray-400 ring-1 ring-inset ring-gray-300 hover:bg-gray-50 focus:z-20 focus:outline-offset-0">
                                <span class="sr-only">Next</span>
                                <svg class="size-5" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                    <path fill-rule="evenodd"
                                        d="M8.22 5.22a.75.75 0 0 1 1.06 0l4.25 4.25a.75.75 0 0 1 0 1.06l-4.25 4.25a.75.75 0 0 1-1.06-1.06L11.94 10 8.22 6.28a.75.75 0 0 1 0-1.06Z"
                                        clip-rule="evenodd" />
                                </svg>
                            </a>
                            <?php endif; ?>
                        </nav>
                    </div>
                </div>
            </div>
        </div>
</body>

</html>