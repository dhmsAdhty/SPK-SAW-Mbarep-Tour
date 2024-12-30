<?php
require 'config/db.php'; // Menyertakan konfigurasi koneksi database

// Menjalankan proses jika form disubmit
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];
    $email = $_POST['email'];

    // Validasi jika password dan konfirmasi password tidak cocok
    if ($password !== $confirm_password) {
        echo "Password tidak cocok!";
    } else {
        // Mengecek apakah username sudah ada di database
        $sql = "SELECT * FROM user WHERE username = '$username'";
        $result = $conn->query($sql);
        
        if ($result->num_rows > 0) {
            echo "Username sudah terdaftar!";
        } else {
            // Meng-hash password sebelum disimpan
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);

            // Menyimpan data user baru ke database
            $sql = "INSERT INTO user (username, password, email) VALUES ('$username', '$hashed_password', '$email')";
            if ($conn->query($sql) === TRUE) {
                echo "Registrasi berhasil! <a href='login.php'>Login disini</a>";
            } else {
                echo "Terjadi kesalahan: " . $conn->error;
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <script src="https://kit.fontawesome.com/3cbb0f1695.js" crossorigin="anonymous"></script>
    <title>Register - Mbarep Tour</title>
</head>
<body>
    <div class="card">
        <div class="logo">
            <img src="/asset/image/logo.png" alt="logo">
        </div>
        <h3>REGISTER SPK MBAREP TOUR</h3>
        <form method="POST">
            <!-- Username -->
            <p>Username :</p>
            <div class="form-group">
                <i class="icon">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="size-6">
                        <path fill-rule="evenodd" d="M7.5 6a4.5 4.5 0 1 1 9 0 4.5 4.5 0 0 1-9 0ZM3.751 20.105a8.25 8.25 0 0 1 16.498 0 .75.75 0 0 1-.437.695A18.683 18.683 0 0 1 12 22.5c-2.786 0-5.433-.608-7.812-1.7a.75.75 0 0 1-.437-.695Z" clip-rule="evenodd" />
                    </svg>
                </i>
                <input type="text" name="username" placeholder="Masukkan Username" required>
            </div>

            <!-- Email -->
            <p>Email :</p>
            <div class="form-group">
                <i class="icon">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="size-6">
                        <path fill-rule="evenodd" d="M2.25 4.5a1.5 1.5 0 0 0-1.5 1.5v13.5a1.5 1.5 0 0 0 1.5 1.5h19.5a1.5 1.5 0 0 0 1.5-1.5V6a1.5 1.5 0 0 0-1.5-1.5H2.25Zm1.5 1.5h18v11.25h-18V6Zm2.625 2.625a.75.75 0 0 1 1.065 1.057l-3 3a.75.75 0 0 1-1.065-1.057l3-3Zm7.15 0a.75.75 0 0 1 1.065 1.057l-3 3a.75.75 0 0 1-1.065-1.057l3-3Z" clip-rule="evenodd"/>
                    </svg>
                </i>
                <input type="email" name="email" placeholder="Masukkan Email" required>
            </div>

            <!-- Password -->
            <p>Password :</p>
            <div class="form-group">
                <i class="icon">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="size-6">
                        <path fill-rule="evenodd" d="M15.75 1.5a6.75 6.75 0 0 0-6.651 7.906c.067.39-.032.717-.221.906l-6.5 6.499a3 3 0 0 0-.878 2.121v2.818c0 .414.336.75.75.75H6a.75.75 0 0 0 .75-.75v-1.5h1.5A.75.75 0 0 0 9 19.5V18h1.5a.75.75 0 0 0 .53-.22l2.658-2.658c.19-.189.517-.288.906-.22A6.75 6.75 0 1 0 15.75 1.5Zm0 3a.75.75 0 0 0 0 1.5A2.25 2.25 0 0 1 18 8.25a.75.75 0 0 0 1.5 0 3.75 3.75 0 0 0-3.75-3.75Z" clip-rule="evenodd" />
                    </svg>
                </i>
                <input type="password" name="password" placeholder="Masukkan Password" required>
            </div>

            <!-- Confirm Password -->
            <p>Konfirmasi Password :</p>
            <div class="form-group">
                <i class="icon">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="size-6">
                        <path fill-rule="evenodd" d="M15.75 1.5a6.75 6.75 0 0 0-6.651 7.906c.067.39-.032.717-.221.906l-6.5 6.499a3 3 0 0 0-.878 2.121v2.818c0 .414.336.75.75.75H6a.75.75 0 0 0 .75-.75v-1.5h1.5A.75.75 0 0 0 9 19.5V18h1.5a.75.75 0 0 0 .53-.22l2.658-2.658c.19-.189.517-.288.906-.22A6.75 6.75 0 1 0 15.75 1.5Zm0 3a.75.75 0 0 0 0 1.5A2.25 2.25 0 0 1 18 8.25a.75.75 0 0 0 1.5 0 3.75 3.75 0 0 0-3.75-3.75Z" clip-rule="evenodd" />
                    </svg>
                </i>
                <input type="password" name="confirm_password" placeholder="Konfirmasi Password" required>
            </div>

            <!-- Submit Button -->
            <div class="line"></div>
            <div class="button">
                <a href="login.php" class="login">LOGIN</a>
                <button type="submit" class="register">DAFTAR</button>
            </div>
        </form>
    </div>
</body>
</html>
