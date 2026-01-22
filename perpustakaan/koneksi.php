<?php
$host = "localhost";     // Nama host server database
$user = "root";          // Username MySQL (default: root)
$pass = "";              // Password MySQL (kosongkan jika tidak ada)
$db   = "db_perpustakaan"; // Nama database kamu

// Membuat koneksi
$koneksi = mysqli_connect($host, $user, $pass, $db);

// Mengecek koneksi
if (!$koneksi) {
    die("Koneksi database gagal: " . mysqli_connect_error());
} else {
    // echo "Koneksi berhasil"; // aktifkan ini jika ingin tes koneksi
}
?>
