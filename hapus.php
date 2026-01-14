<?php
// hapus.php
session_start();
include 'koneksi.php';

if (!isset($_SESSION['login'])) {
    header("Location: login.php");
    exit;
}

$jenis = $_GET['jenis'];

if ($jenis == 'prodi') {
    $id = $_GET['id'];
    
    // Cek apakah ada mahasiswa yang menggunakan program studi ini
    $cek = mysqli_query($conn, "SELECT COUNT(*) as jumlah FROM db_akademik_mahasiswa WHERE program_studi_id = $id");
    $data_cek = mysqli_fetch_assoc($cek);
    
    if ($data_cek['jumlah'] > 0) {
        $_SESSION['pesan'] = "Program studi tidak dapat dihapus karena masih ada mahasiswa yang terdaftar!";
        $_SESSION['tipe_pesan'] = "danger";
    } else {
        $query = "DELETE FROM db_akademik_program_studi WHERE id = $id";
        
        if (mysqli_query($conn, $query)) {
            $_SESSION['pesan'] = "Data program studi berhasil dihapus!";
            $_SESSION['tipe_pesan'] = "success";
        } else {
            $_SESSION['pesan'] = "Gagal menghapus data: " . mysqli_error($conn);
            $_SESSION['tipe_pesan'] = "danger";
        }
    }
    
} elseif ($jenis == 'mahasiswa') {
    $nim = $_GET['nim'];
    $query = "DELETE FROM mahasiswa WHERE nim = '$nim'";
    
    if (mysqli_query($conn, $query)) {
        $_SESSION['pesan'] = "Data mahasiswa berhasil dihapus!";
        $_SESSION['tipe_pesan'] = "success";
    } else {
        $_SESSION['pesan'] = "Gagal menghapus data: " . mysqli_error($conn);
        $_SESSION['tipe_pesan'] = "danger";
    }
}

header("Location: index.php");
exit;
?>