<?php
include "koneksi.php";
$nim = $_GET['nim'];

mysqli_query($koneksi, "DELETE FROM mahasiswa WHERE nim='$nim'");

echo "<script>alert('Data berhasil dihapus'); window.location='index.php';</script>";
?>
