<?php
session_start();
include 'koneksi.php'; // menghubungkan ke database

if (isset($_POST['login'])) {
    $username = mysqli_real_escape_string($koneksi, $_POST['username']);
    $password = md5(mysqli_real_escape_string($koneksi, $_POST['password']));

    $query = "SELECT * FROM users WHERE username='$username' AND password='$password'";
    $result = mysqli_query($koneksi, $query);

    if (mysqli_num_rows($result) > 0) {
        $data = mysqli_fetch_assoc($result);

        // Simpan data ke session
        $_SESSION['id_user'] = $data['id_user'];
        $_SESSION['username'] = $data['username'];
        $_SESSION['role'] = $data['role'];

        // Arahkan ke halaman sesuai peran
        if ($data['role'] == 'admin') {
            header("Location: admin/index.php");
        } elseif ($data['role'] == 'petugas') {
            header("Location: petugas/index.php");
        } elseif ($data['role'] == 'peminjam') {
            header("Location: peminjam/index.php");
        } else {
            echo "<script>alert('Peran pengguna tidak dikenali!');</script>";
        }
    } else {
        echo "<script>alert('Username atau Password salah!');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Login | Sistem Informasi Perpustakaan</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="login-container">
        <h2>Login Sistem Perpustakaan</h2>
        <form method="POST" action="">
            <label>Username</label>
            <input type="text" name="username" placeholder="Masukkan username" required>

            <label>Password</label>
            <input type="password" name="password" placeholder="Masukkan password" required>

            <button type="submit" name="login">Login</button>
        </form>
        <p>Belum punya akun? <a href="register.php">Daftar di sini</a></p>
    </div>
</body>
</html>
