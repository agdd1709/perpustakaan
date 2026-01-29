<?php
session_start();
include '../koneksi.php';

// Cek login admin / petugas
if (!isset($_SESSION['username']) || 
   ($_SESSION['role'] != 'admin' && $_SESSION['role'] != 'petugas')) {
    header("Location: ../login.php");
    exit;
}

// Konfirmasi peminjaman
if (isset($_GET['konfirmasi'])) {
    $id = $_GET['konfirmasi'];

    mysqli_query($koneksi, 
        "UPDATE peminjaman 
         SET status = 'dipinjam', id_petugas = '$_SESSION[id_user]' 
         WHERE id_peminjaman = '$id'"
    );

    // Kurangi stok buku
    $buku = mysqli_fetch_assoc(mysqli_query($koneksi,
        "SELECT id_buku FROM peminjaman WHERE id_peminjaman = '$id'"
    ));

    mysqli_query($koneksi,
        "UPDATE buku SET stok = stok - 1 WHERE id_buku = '$buku[id_buku]'"
    );

    header("Location: peminjaman.php");
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Data Peminjaman</title>
    <link rel="stylesheet" href="admin.css">
</head>
<body>

<h2>📚 Data Peminjaman Buku</h2>

<table border="1" cellpadding="10" cellspacing="0" width="100%">
    <tr>
        <th>No</th>
        <th>Nama Anggota</th>
        <th>Judul Buku</th>
        <th>Tanggal Pinjam</th>
        <th>Status</th>
        <th>Petugas</th>
        <th>Aksi</th>
    </tr>

    <?php
    $no = 1;
    $data = mysqli_query($koneksi,
        "SELECT p.*, 
                u.nama AS nama_anggota,
                b.judul,
                pet.nama AS nama_petugas
         FROM peminjaman p
         JOIN anggota a ON p.id_anggota = a.id_anggota
         JOIN users u ON a.id_user = u.id_user
         JOIN buku b ON p.id_buku = b.id_buku
         LEFT JOIN users pet ON p.id_petugas = pet.id_user
         ORDER BY p.id_peminjaman DESC"
    );

    while ($row = mysqli_fetch_assoc($data)) {
    ?>
    <tr>
        <td><?= $no++ ?></td>
        <td><?= $row['nama_anggota'] ?></td>
        <td><?= $row['judul'] ?></td>
        <td><?= $row['tanggal_pinjam'] ?></td>
        <td><?= $row['status'] ?></td>
        <td><?= $row['nama_petugas'] ?? '-' ?></td>
        <td>
            <?php if ($row['status'] == 'menunggu'): ?>
                <a href="?konfirmasi=<?= $row['id_peminjaman'] ?>"
                   onclick="return confirm('Konfirmasi peminjaman?')">
                   ✔ Konfirmasi
                </a>
            <?php else: ?>
                ✔ Selesai
            <?php endif; ?>
        </td>
    </tr>
    <?php } ?>
</table>

</body>
</html>
