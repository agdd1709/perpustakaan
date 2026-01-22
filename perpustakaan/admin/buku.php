<?php
session_start();
include '../koneksi.php';

// Cek Login
if (!isset($_SESSION['username']) || $_SESSION['role'] != 'admin') {
    header("Location: ../login.php");
    exit;
}

// Tambah Buku
if (isset($_POST['tambah'])) {
    $judul = $_POST['judul'];
    $pengarang = $_POST['pengarang'];
    $penerbit = $_POST['penerbit'];
    $tahun_terbit = $_POST['tahun_terbit'];
    $stok = $_POST['stok'];

    $query = "INSERT INTO buku (judul, pengarang, penerbit, tahun_terbit, stok) VALUES ('$judul','$pengarang','$penerbit','$tahun_terbit','$stok')";
    mysqli_query($koneksi, $query);
    header("Location: buku.php");
}

// Hapus Buku
if (isset($_GET['hapus'])) {
    $id = $_GET['hapus'];
    mysqli_query($koneksi, "DELETE FROM buku WHERE id_buku='$id'");
    header("Location: buku.php");
}

// Edit Buku
if (isset($_POST['edit'])) {
    $id = $_POST['id_buku'];
    $judul = $_POST['judul'];
    $pengarang = $_POST['pengarang'];
    $penerbit = $_POST['penerbit'];
    $tahun_terbit = $_POST['tahun_terbit'];
    $stok = $_POST['stok'];

    $query = "UPDATE buku SET judul='$judul', pengarang='$pengarang', penerbit='$penerbit', tahun_terbit='$tahun_terbit', stok='$stok' WHERE id_buku='$id'";
    mysqli_query($koneksi, $query);
    header("Location: buku.php");
}

// Ambil Data Buku
$data_buku = mysqli_query($koneksi, "SELECT * FROM buku ORDER BY id_buku DESC");
?>

<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>Data Buku - Admin Perpustakaan</title>
<link rel="stylesheet" href="admin.css">
</head>
<body>

<div class="sidebar">
    <h2>PERPUSTAKAAN</h2>
    <a href="dashboard.php">Dashboard</a>
    <a href="buku.php" class="active">Kelola Buku</a>
    <a href="petugas.php">Kelola Petugas</a>
    <a href="anggota.php">Kelola Anggota</a>
    <a href="peminjaman.php">Konfirmasi Peminjaman</a>
    <a href="pengembalian.php">Konfirmasi Pengembalian</a>
    <a href="../logout.php" class="logout">Logout</a>
</div>

<div class="main-content">
    <div class="header">
        <h1>Kelola Data Buku</h1>
    </div>

    <div class="content">
        <button class="btn" onclick="document.getElementById('formTambah').style.display='block'">+ Tambah Buku</button>

        <table class="table">
            <tr>
                <th>No</th>
                <th>Judul Buku</th>
                <th>Pengarang</th>
                <th>Penerbit</th>
                <th>tahun_terbit</th>
                <th>Stok</th>
                <th>Aksi</th>
            </tr>

            <?php 
            $no = 1;
            while ($row = mysqli_fetch_assoc($data_buku)) { ?>
            <tr>
                <td><?= $no++; ?></td>
                <td><?= $row['judul']; ?></td>
                <td><?= $row['pengarang']; ?></td>
                <td><?= $row['penerbit']; ?></td>
                <td><?= $row['tahun_terbit']; ?></td>
                <td><?= $row['stok']; ?></td>
                <td>
                    <button class="btn" onclick="editData(<?= $row['id_buku']; ?>,'<?= $row['judul']; ?>','<?= $row['pengarang']; ?>','<?= $row['penerbit']; ?>','<?= $row['tahun_terbit']; ?>','<?= $row['stok']; ?>')">Edit</button>
                    <a href="buku.php?hapus=<?= $row['id_buku']; ?>" class="btn" style="background:red;" onclick="return confirm('Yakin ingin hapus buku ini?')">Hapus</a>
                </td>
            </tr>
            <?php } ?>
        </table>
    </div>
</div>

<!-- Modal Tambah Buku -->
<div id="formTambah" class="modal" style="display:none;">
    <form method="post" class="form-box">
        <h2>Tambah Buku</h2>
        <input type="text" name="judul" placeholder="Judul Buku" required>
        <input type="text" name="pengarang" placeholder="Pengarang" required>
        <input type="text" name="penerbit" placeholder="Penerbit" required>
        <input type="number" name="tahun_terbit" placeholder="tahun_Terbit" required>
        <input type="number" name="stok" placeholder="Stok" required>
        <button type="submit" name="tambah" class="btn">Simpan</button>
        <button type="button" class="btn" style="background:gray;" onclick="document.getElementById('formTambah').style.display='none'">Batal</button>
    </form>
</div>

<!-- Modal Edit Buku -->
<div id="formEdit" class="modal" style="display:none;">
    <form method="post" class="form-box">
        <h2>Edit Buku</h2>
        <input type="hidden" name="id_buku" id="id_buku">
        <input type="text" name="judul" id="judul" placeholder="Judul Buku" required>
        <input type="text" name="pengarang" id="pengarang" placeholder="Pengarang" required>
        <input type="text" name="penerbit" id="penerbit" placeholder="Penerbit" required>
        <input type="number" name="tahun_terbit" id="tahun_terbit" placeholder="Tahun Terbit" required>
        <input type="number" name="stok" id="stok" placeholder="Stok" required>
        <button type="submit" name="edit" class="btn">Simpan Perubahan</button>
        <button type="button" class="btn" style="background:gray;" onclick="document.getElementById('formEdit').style.display='none'">Batal</button>
    </form>
</div>

<script>
function editData(id, judul, pengarang, penerbit, tahun_terbit, stok) {
    document.getElementById('formEdit').style.display = 'block';
    document.getElementById('id_buku').value = id;
    document.getElementById('judul').value = judul;
    document.getElementById('pengarang').value = pengarang;
    document.getElementById('penerbit').value = penerbit;
    document.getElementById('tahun_terbit').value = tahun_terbit;
    document.getElementById('stok').value = stok;
}
</script>

<style>
/* Modal Popup */
.modal {
    position: fixed;
    top: 0; left: 0;
    width: 100%; height: 100%;
    background: rgba(0,0,0,0.6);
    display: flex; justify-content: center; align-items: center;
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
    padding: 8px;
    border: 1px solid #ccc;
    border-radius: 6px;
}
</style>

</body>
</html>
