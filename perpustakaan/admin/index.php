<?php
session_start();
include '../koneksi.php';

// Cek Login
if (!isset($_SESSION['username']) || $_SESSION['role'] != 'admin') {
    header("Location: ../login.php");
    exit;
}

// Mengambil data statistik
$jml_buku = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT COUNT(*) AS total FROM buku"))['total'];
$jml_petugas = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT COUNT(*) AS total FROM users WHERE role='petugas'"))['total'];
$jml_anggota = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT COUNT(*) AS total FROM peminjaman"))['total'];
$jml_dipinjam = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT COUNT(*) AS total FROM peminjaman WHERE status='dipinjam'"))['total'];
?>

<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>Dashboard Admin - Perpustakaan</title>
<link rel="stylesheet" href="admin.css">
</head>
<body>

<div class="sidebar">
    <h2>PERPUSTAKAAN</h2>
    <a href="index.php" class="active">Dashboard</a>
    <a href="buku.php">Kelola Buku</a>
    <a href="petugas.php">Kelola Petugas</a>
    <a href="anggota.php">Kelola Anggota</a>
    <a href="peminjaman.php">Konfirmasi Peminjaman</a>
    <a href="pengembalian.php">Konfirmasi Pengembalian</a>
    <a href="../logout.php" class="logout">Logout</a>
</div>

<div class="main-content">
    <div class="header">
        <h1>Dashboard Admin</h1>
    </div>

    <div class="stats">
        <div class="card">
            <h3>Jumlah Buku</h3>
            <p><?= $jml_buku; ?></p>
        </div>

        <div class="card">
            <h3>Jumlah Petugas</h3>
            <p><?= $jml_petugas; ?></p>
        </div>

        <div class="card">
            <h3>Jumlah Anggota</h3>
            <p><?= $jml_anggota; ?></p>
        </div>

        <div class="card">
            <h3>Buku Sedang Dipinjam</h3>
            <p><?= $jml_dipinjam; ?></p>
        </div>
    </div>

    <div class="welcome-box">
        <h2>Selamat Datang, <strong><?= $_SESSION['username']; ?></strong> 👋</h2>
        <p>Silakan gunakan menu untuk mengelola data perpustakaan.</p>
    </div>
</div>

</body>
</html>
