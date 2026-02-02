<?php
session_start();
include '../koneksi.php';

// Cek login admin / petugas
if (!isset($_SESSION['username']) || 
   ($_SESSION['role'] != 'admin' && $_SESSION['role'] != 'petugas')) {
    header("Location: ../login.php");
    exit;
}

// Konfirmasi pengembalian
if (isset($_GET['kembali'])) {
    $id = $_GET['kembali'];

    // Ambil data peminjaman
    $pinjam = mysqli_fetch_assoc(mysqli_query($conn,
        "SELECT id_buku FROM peminjaman 
         WHERE id_peminjaman = '$id' AND status = 'dipinjam'"
    ));

    if ($pinjam) {
        // Update status peminjaman
        mysqli_query($conn,
            "UPDATE peminjaman 
             SET status = 'kembali',
                 tanggal_kembali = NOW(),
                 id_petugas = '$_SESSION[id_user]'
             WHERE id_peminjaman = '$id'"
        );

        // Tambah stok buku
        mysqli_query($conn,
            "UPDATE buku 
             SET stok = stok + 1 
             WHERE id_buku = '$pinjam[id_buku]'"
        );
    }

    header("Location: pengembalian.php");
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Pengembalian Buku</title>
    <link rel="stylesheet" href="admin.css">
</head>
<body>

<h2>📦 Data Pengembalian Buku</h2>

<table border="1" cellpadding="10" cellspacing="0" width="100%">
    <tr>
        <th>No</th>
        <th>Nama Anggota</th>
        <th>Judul Buku</th>
        <th>Tgl Pinjam</th>
        <th>Status</th>
        <th>Petugas</th>
        <th>Aksi</th>
    </tr>

    <?php
    $no = 1;
    $data = mysqli_query($conn,
        "SELECT p.*, 
                u.nama AS nama_anggota,
                b.judul,
                pet.nama AS nama_petugas
         FROM peminjaman p
         JOIN anggota a ON p.id_anggota = a.id_anggota
         JOIN users u ON a.id_user = u.id_user
         JOIN buku b ON p.id_buku = b.id_buku
         LEFT JOIN users pet ON p.id_petugas = pet.id_user
         WHERE p.status = 'dipinjam'
         ORDER BY p.tanggal_pinjam DESC"
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
            <a href="?kembali=<?= $row['id_peminjaman'] ?>"
               onclick="return confirm('Konfirmasi pengembalian buku?')">
               🔄 Kembalikan
            </a>
        </td>
    </tr>
    <?php } ?>
</table>

</body>
</html>
