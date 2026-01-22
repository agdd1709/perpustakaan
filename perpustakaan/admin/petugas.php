<?php
session_start();
include '../koneksi.php';

// Cek Login
if (!isset($_SESSION['username']) || $_SESSION['role'] != 'admin') {
    header("Location: ../login.php");
    exit;
}

// Tambah Petugas
if (isset($_POST['tambah'])) {
    $nama = $_POST['nama'];
    $username = $_POST['username'];
    $password = md5($_POST['password']); // menggunakan MD5
    $role = "petugas";

    $query = "INSERT INTO users (nama, username, password, role) VALUES 
              ('$nama','$username','$password','$role')";

    mysqli_query($koneksi, $query);
    header("Location: petugas.php");
}

// Hapus Petugas
if (isset($_GET['hapus'])) {
    $id_user = $_GET['hapus'];
    mysqli_query($koneksi, "DELETE FROM users WHERE id_user='$id_user'");
    header("Location: petugas.php");
}

// Edit Petugas
if (isset($_POST['edit'])) {
    $id_user = $_POST['id_user'];
    $nama = $_POST['nama'];
    $username = $_POST['username'];

    if ($_POST['password'] != "") {
        $password = md5($_POST['password']);
        $update = "UPDATE users SET nama='$nama', username='$username', password='$password' WHERE id_user='$id_user'";
    } else {
        $update = "UPDATE users SET nama='$nama', username='$username' WHERE id_user='$id_user'";
    }

    mysqli_query($koneksi, $update);
    header("Location: petugas.php");
}

// Ambil Data Petugas
$data = mysqli_query($koneksi, "SELECT * FROM users WHERE role='petugas' ORDER BY id_user DESC");
?>

<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>Kelola Petugas</title>
<link rel="stylesheet" href="admin.css">
</head>
<body>

<div class="sidebar">
    <h2>PERPUSTAKAAN</h2>
    <a href="index.php">Dashboard</a>
    <a href="buku.php">Kelola Buku</a>
    <a href="petugas.php" class="active">Kelola Petugas</a>
    <a href="anggota.php">Kelola Anggota</a>
    <a href="peminjaman.php">Konfirmasi Peminjaman</a>
    <a href="pengembalian.php">Konfirmasi Pengembalian</a>
    <a href="../logout.php" class="logout">Logout</a>
</div>

<div class="main-content">
    <div class="header">
        <h1>Kelola Petugas</h1>
    </div>

    <div class="content">
        <button class="btn" onclick="document.getElementById('formTambah').style.display='block'">+ Tambah Petugas</button>

        <table class="table">
            <tr>
                <th>No</th>
                <th>Nama</th>
                <th>Username</th>
                <th>Aksi</th>
            </tr>

            <?php 
            $no = 1;
            while ($row = mysqli_fetch_assoc($data)) { ?>
            <tr>
                <td><?= $no++; ?></td>
                <td><?= $row['nama']; ?></td>
                <td><?= $row['username']; ?></td>
                <td>
                    <button class="btn"
                        onclick="editData(
                            '<?= $row['id_user']; ?>',
                            '<?= $row['nama']; ?>',
                            '<?= $row['username']; ?>'
                        )">Edit</button>

                    <a href="petugas.php? hapus=<?= $row['id_user']; ?>" 
                       class="btn" 
                       style="background:red;"
                       onclick="return confirm('Yakin hapus petugas ini?')">Hapus</a>
                </td>
            </tr>
            <?php } ?>
        </table>
    </div>
</div>

<!-- Modal Tambah -->
<div id="formTambah" class="modal" style="display:none;">
    <form method="post" class="form-box">
        <h2>Tambah Petugas</h2>
        <input type="text" name="nama" placeholder="Nama Petugas" required>
        <input type="text" name="username" placeholder="Username" required>
        <input type="password" name="password" placeholder="Password" required>

        <button type="submit" name="tambah" class="btn">Simpan</button>
        <button type="button" class="btn" style="background:gray;"
            onclick="document.getElementById('formTambah').style.display='none'">Batal</button>
    </form>
</div>

<!-- Modal Edit -->
<div id="formEdit" class="modal" style="display:none;">
    <form method="post" class="form-box">
        <h2>Edit Petugas</h2>
        <input type="hidden" name="id" id="id_edit">

        <input type="text" name="nama" id="nama_edit" placeholder="Nama" required>
        <input type="text" name="username" id="username_edit" placeholder="Username" required>
        <input type="password" name="password" id="password_edit" placeholder="Password baru (optional)">

        <button type="submit" name="edit" class="btn">Update</button>
        <button type="button" class="btn" style="background:gray;"
            onclick="document.getElementById('formEdit').style.display='none'">Batal</button>
    </form>
</div>

<script>
function editData(id, nama, username) {
    document.getElementById('formEdit').style.display = 'block';

    document.getElementById('id_edit').value = id;
    document.getElementById('nama_edit').value = nama;
    document.getElementById('username_edit').value = username;
}
</script>

<style>
.modal {
    position: fixed;
    top: 0; left: 0;
    width: 100%;
    height: 100%;
    background: rgba(0,0,0,0.6);
    display: flex;
    justify-content: center;
    align-items: center;
}
.form-box {
    background: #fff;
    padding: 25px;
    border-radius: 8px;
    width: 350px;
    display: flex;
    flex-direction: column;
    gap: 10px;
}
.form-box input {
    padding: 10px;
    border-radius: 5px;
    border: 1px solid #aaa;
}
</style>

</body>
</html>
