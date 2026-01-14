<?php
// proses_profil.php
session_start();
include 'koneksi.php';

if (!isset($_SESSION['login'])) {
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION['user_id'];

// Ambil data dari form
$nama_lengkap = mysqli_real_escape_string($conn, trim($_POST['nama_lengkap']));
$email = mysqli_real_escape_string($conn, trim($_POST['email']));
$password_lama = isset($_POST['password_lama']) ? trim($_POST['password_lama']) : '';
$password_baru = isset($_POST['password_baru']) ? trim($_POST['password_baru']) : '';
$konfirmasi_password = isset($_POST['konfirmasi_password']) ? trim($_POST['konfirmasi_password']) : '';

// Validasi: Nama lengkap tidak boleh kosong
if (empty($nama_lengkap)) {
    $_SESSION['pesan_profil'] = "Nama lengkap tidak boleh kosong!";
    $_SESSION['tipe_pesan_profil'] = "danger";
    header("Location: profil.php");
    exit;
}

// Validasi: Email tidak boleh kosong dan harus valid
if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $_SESSION['pesan_profil'] = "Alamat email tidak valid!";
    $_SESSION['tipe_pesan_profil'] = "danger";
    header("Location: profil.php");
    exit;
}

// Ambil data pengguna saat ini
$query_user = "SELECT * FROM pengguna WHERE id = $user_id";
$result_user = mysqli_query($conn, $query_user);
$user_data = mysqli_fetch_assoc($result_user);

// Cek apakah email sudah digunakan pengguna lain
if ($email != $user_data['email']) {
    $cek_email = mysqli_query($conn, "SELECT * FROM pengguna WHERE email = '$email' AND id != $user_id");
    if (mysqli_num_rows($cek_email) > 0) {
        $_SESSION['pesan_profil'] = "Email sudah digunakan oleh pengguna lain!";
        $_SESSION['tipe_pesan_profil'] = "danger";
        header("Location: profil.php");
        exit;
    }
}

// Jika ingin mengubah password
$update_password = false;
if (!empty($password_baru) || !empty($konfirmasi_password)) {
    // Validasi: Password lama harus diisi
    if (empty($password_lama)) {
        $_SESSION['pesan_profil'] = "Masukkan kata sandi lama untuk mengubah kata sandi!";
        $_SESSION['tipe_pesan_profil'] = "danger";
        header("Location: profil.php");
        exit;
    }

    // Validasi: Password lama harus benar
    if (!password_verify($password_lama, $user_data['password'])) {
        $_SESSION['pesan_profil'] = "Kata sandi lama tidak sesuai!";
        $_SESSION['tipe_pesan_profil'] = "danger";
        header("Location: profil.php");
        exit;
    }

    // Validasi: Password baru dan konfirmasi harus sama
    if ($password_baru !== $konfirmasi_password) {
        $_SESSION['pesan_profil'] = "Kata sandi baru dan konfirmasi tidak cocok!";
        $_SESSION['tipe_pesan_profil'] = "danger";
        header("Location: profil.php");
        exit;
    }

    // Validasi: Password baru minimal 6 karakter
    if (strlen($password_baru) < 6) {
        $_SESSION['pesan_profil'] = "Kata sandi baru minimal 6 karakter!";
        $_SESSION['tipe_pesan_profil'] = "danger";
        header("Location: profil.php");
        exit;
    }

    $update_password = true;
}

// Proses update data
if ($update_password) {
    // Update dengan password baru
    $password_hash = password_hash($password_baru, PASSWORD_DEFAULT);
    $query = "UPDATE pengguna SET 
              nama_lengkap = '$nama_lengkap',
              email = '$email',
              password = '$password_hash'
              WHERE id = $user_id";
} else {
    // Update tanpa mengubah password
    $query = "UPDATE pengguna SET 
              nama_lengkap = '$nama_lengkap',
              email = '$email'
              WHERE id = $user_id";
}

if (mysqli_query($conn, $query)) {
    // Update session dengan data terbaru
    $_SESSION['nama_lengkap'] = $nama_lengkap;
    $_SESSION['email'] = $email;

    if ($update_password) {
        $_SESSION['pesan_profil'] = "Profil dan kata sandi berhasil diperbarui!";
    } else {
        $_SESSION['pesan_profil'] = "Profil berhasil diperbarui!";
    }
    $_SESSION['tipe_pesan_profil'] = "success";
} else {
    $_SESSION['pesan_profil'] = "Gagal memperbarui profil: " . mysqli_error($conn);
    $_SESSION['tipe_pesan_profil'] = "danger";
}

header("Location: profil.php");
exit;
?>