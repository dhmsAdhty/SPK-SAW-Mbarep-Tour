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
        $error_message = "Password tidak cocok!";
    } else {
        // Mengecek apakah username sudah ada di database
        $sql = "SELECT * FROM user WHERE username = '$username'";
        $result = $conn->query($sql);
        
        if ($result->num_rows > 0) {
            $error_message = "Username sudah terdaftar!";
        } else {
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);
            $sql = "INSERT INTO user (username, password, email) VALUES ('$username', '$hashed_password', '$email')";
            if ($conn->query($sql) === TRUE) {
                $success_message = "Registrasi berhasil! Silakan <a href='login.php'>login di sini</a>";
            } else {
                $error_message = "Terjadi kesalahan: " . $conn->error;
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
    <title>Register - Mbarep Tour</title>
</head>

<body>
    <div class="card">
        <h3>REGISTER SPK MBAREP TOUR</h3>

        <!-- Pesan Sukses atau Error -->
        <?php if (isset($error_message)) { ?>
        <div class="alert alert-error">
            <?php echo $error_message; ?>
        </div>
        <?php } elseif (isset($success_message)) { ?>
        <div class="alert alert-success">
            <?php echo $success_message; ?>
        </div>
        <?php } ?>

        <!-- Form Registrasi -->
        <form method="POST">
            <p>Username :</p>
            <div class="form-group">
                <input type="text" name="username" placeholder="Masukkan Username" required>
            </div>

            <p>Email :</p>
            <div class="form-group">
                <input type="email" name="email" placeholder="Masukkan Email" required>
            </div>

            <p>Password :</p>
            <div class="form-group">
                <input type="password" name="password" placeholder="Masukkan Password" required>
            </div>

            <p>Konfirmasi Password :</p>
            <div class="form-group">
                <input type="password" name="confirm_password" placeholder="Konfirmasi Password" required>
            </div>

            <!-- Tombol Submit -->
            <div class="line"></div>
            <div class="button">
                <a href="login.php" class="login">LOGIN</a>
                <button type="submit" class="register">DAFTAR</button>
            </div>
        </form>
    </div>

    <!-- Style untuk Notifikasi -->
    <style>
    .alert {
        padding: 10px;
        margin: 10px 0;
        border-radius: 5px;
        font-size: 14px;
        text-align: center;
    }

    .alert-error {
        background-color: #f8d7da;
        color: #721c24;
        border: 1px solid #f5c6cb;
    }

    .alert-success {
        background-color: #d4edda;
        color: #155724;
        border: 1px solid #c3e6cb;
    }
    </style>
</body>

</html>