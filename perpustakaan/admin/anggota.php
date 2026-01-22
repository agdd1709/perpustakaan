<?php
session_start();
include '../koneksi.php';

// Cek Login
if (!isset($_SESSION['username']) || $_SESSION['role'] != 'admin') {
    header("Location: ../login.php");
    exit;
}

// Tambah Anggota
if (isset($_POST['tambah'])) {
    $nama = $_POST['nama'];
    $username = $_POST['username'];
    $password = md5($_POST['password']);
    $alamat = $_POST['alamat'];
    $no_hp = $_POST['no_hp'];
    $tanggal_daftar = date("Y-m-d");

    // Tambah ke tabel users
    mysqli_query($koneksi, "INSERT INTO users VALUES(
        NULL, '$nama', '$username', '$password', 'anggota', NOW()
    )");

    // Ambil id_user terakhir
    $id_user = mysqli_insert_id($koneksi);

    // Tambah ke tabel anggota
    mysqli_query($koneksi, "INSERT INTO anggota VALUES(
        NULL, '$id_user', '$alamat', '$no_hp', '$tanggal_daftar'
    )");

    header("Location: anggota.php");
    exit;
}

// Hapus Anggota
if (isset($_GET['hapus'])) {
    $id_anggota = $_GET['hapus'];

    // ambil id_user terkait
    $get = mysqli_fetch_assoc(mysqli_query(
        $koneksi, "SELECT id_user FROM anggota WHERE id_anggota='$id_anggota'"
    ));
    $id_user = $get['id_user'];

    // hapus dari anggota
    mysqli_query($koneksi, "DELETE FROM anggota WHERE id_anggota='$id_anggota'");

    // hapus juga user-nya
    mysqli_query($koneksi, "DELETE FROM users WHERE id_user='$id_user'");

    header("Location: anggota.php");
    exit;
}

?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>Kelola Anggota</title>
<link rel="stylesheet" href="admin.css">
</head>
<body>

<div class="sidebar">
    <h2>PERPUSTAKAAN</h2>
    <a href="dashboard.php">Dashboard</a>
    <a href="buku.php">Kelola Buku</a>
    <a href="petugas.php">Kelola Petugas</a>
    <a href="anggota.php" class="active">Kelola Anggota</a>
    <a href="peminjaman.php">Konfirmasi Peminjaman</a>
    <a href="pengembalian.php">Konfirmasi Pengembalian</a>
    <a href="../logout.php" class="logout">Logout</a>
</div>

<div class="main-content">
    <div class="header">
        <h1>Kelola Anggota</h1>
    </div>

    <div class="form-box">
        <h2>Tambah Anggota Baru</h2>
        <form method="POST">
            <input type="text" name="nama" placeholder="Nama Lengkap" required>
            <input type="text" name="username" placeholder="Username" required>
            <input type="password" name="password" placeholder="Password" required>
            <input type="text" name="alamat" placeholder="Alamat" required>
            <input type="text" name="no_hp" placeholder="No HP" required>
            <button type="submit" name="tambah">Tambah Anggota</button>
        </form>
    </div>

    <div class="table-box">
        <h2>Data Anggota</h2>

        <table>
            <tr>
                <th>ID</th>
                <th>Nama</th>
                <th>Username</th>
                <th>Alamat</th>
                <th>No HP</th>
                <th>Tanggal Daftar</th>
                <th>Aksi</th>
            </tr>

            <?php
            $data = mysqli_query($koneksi, 
                "SELECT a.*, u.nama, u.username, u.role 
                FROM anggota a 
                JOIN users u ON a.id_user = u.id_user 
                WHERE u.role = 'anggota'
                ORDER BY a.id_anggota DESC"
);


            while ($row = mysqli_fetch_assoc($data)) {
            ?>
            <tr>
                <td><?= $row['id_anggota'] ?></td>
                <td><?= $row['nama'] ?></td>
                <td><?= $row['username'] ?></td>
                <td><?= $row['alamat'] ?></td>
                <td><?= $row['no_hp'] ?></td>
                <td><?= $row['tanggal_daftar'] ?></td>
                <td>
                    <a href="edit_anggota.php?id=<?= $row['id_anggota'] ?>" class="btn-edit">Edit</a>
                    <a href="anggota.php?hapus=<?= $row['id_anggota'] ?>" class="btn-hapus"
                        onclick="return confirm('Yakin ingin menghapus anggota ini?');">Hapus</a>
                </td>
            </tr>
            <?php } ?>
        </table>
    </div>

</div>

</body>
</html>
