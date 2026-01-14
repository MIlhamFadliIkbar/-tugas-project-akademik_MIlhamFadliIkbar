<?php
// profil.php
session_start();
include 'koneksi.php';

if (!isset($_SESSION['login'])) {
    header("Location: login.php");
    exit;
}

// Ambil data pengguna saat ini
$user_id = $_SESSION['user_id'];
$query = "SELECT * FROM pengguna WHERE id = $user_id";
$result = mysqli_query($conn, $query);
$user = mysqli_fetch_assoc($result);
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Profil - Sistem Akademik</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary">
        <div class="container">
            <a class="navbar-brand" href="index.php">Sistem Akademik</a>
            <div class="navbar-nav ms-auto">
                <span class="navbar-text text-white me-3">
                    Selamat datang, <?= $_SESSION['nama_lengkap'] ?>
                </span>
                <a href="logout.php" class="btn btn-light btn-sm">Logout</a>
            </div>
        </div>
    </nav>

    <div class="container mt-4">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h4>Edit Profil Pengguna</h4>
                        <a href="index.php" class="btn btn-secondary btn-sm">Kembali ke Dashboard</a>
                    </div>
                    <div class="card-body">
                        <?php if (isset($_SESSION['pesan_profil'])): ?>
                            <div class="alert alert-<?= $_SESSION['tipe_pesan_profil'] ?> alert-dismissible fade show">
                                <?= $_SESSION['pesan_profil'] ?>
                                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                            </div>
                            <?php 
                            unset($_SESSION['pesan_profil']);
                            unset($_SESSION['tipe_pesan_profil']);
                            ?>
                        <?php endif; ?>

                        <div class="alert alert-info">
                            <strong>Informasi:</strong> Anda dapat mengubah nama, kata sandi, dan alamat email. Pastikan fitur berjalan dengan baik serta menerapkan validasi dan keamanan data.
                        </div>

                        <form method="POST" action="proses_profil.php" id="formProfil">
                            <div class="mb-3">
                                <label for="nama_lengkap" class="form-label">Nama Lengkap <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="nama_lengkap" name="nama_lengkap" value="<?= htmlspecialchars($user['nama_lengkap']) ?>" required>
                                <div class="form-text">Masukkan nama lengkap Anda</div>
                            </div>

                            <div class="mb-3">
                                <label for="email" class="form-label">Alamat Email <span class="text-danger">*</span></label>
                                <input type="email" class="form-control" id="email" name="email" value="<?= htmlspecialchars($user['email']) ?>" required>
                                <div class="form-text">Email tidak dapat diubah jika sudah terdaftar di sistem</div>
                            </div>

                            <hr class="my-4">

                            <h5 class="mb-3">Ubah Kata Sandi</h5>
                            <div class="alert alert-warning">
                                <small><strong>Catatan:</strong> Kosongkan jika tidak ingin mengubah kata sandi</small>
                            </div>

                            <div class="mb-3">
                                <label for="password_lama" class="form-label">Kata Sandi Lama</label>
                                <input type="password" class="form-control" id="password_lama" name="password_lama">
                                <div class="form-text">Wajib diisi jika ingin mengubah kata sandi</div>
                            </div>

                            <div class="mb-3">
                                <label for="password_baru" class="form-label">Kata Sandi Baru</label>
                                <input type="password" class="form-control" id="password_baru" name="password_baru" minlength="6">
                                <div class="form-text">Minimal 6 karakter</div>
                            </div>

                            <div class="mb-3">
                                <label for="konfirmasi_password" class="form-label">Konfirmasi Kata Sandi Baru</label>
                                <input type="password" class="form-control" id="konfirmasi_password" name="konfirmasi_password" minlength="6">
                                <div class="form-text">Masukkan ulang kata sandi baru</div>
                            </div>

                            <div class="d-grid gap-2">
                                <button type="submit" class="btn btn-primary btn-lg">
                                    <i class="bi bi-save"></i> Simpan Perubahan
                                </button>
                            </div>
                        </form>
                    </div>
                </div>

                <div class="card mt-3">
                    <div class="card-body">
                        <h5 class="card-title">Informasi Akun</h5>
                        <table class="table table-borderless">
                            <tr>
                                <td width="200"><strong>ID Pengguna:</strong></td>
                                <td><?= $user['id'] ?></td>
                            </tr>
                            <tr>
                                <td><strong>Email Terdaftar:</strong></td>
                                <td><?= $user['email'] ?></td>
                            </tr>
                            <tr>
                                <td><strong>Tanggal Registrasi:</strong></td>
                                <td><?= date('d F Y H:i', strtotime($user['created_at'])) ?> WIB</td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Validasi form sebelum submit
        document.getElementById('formProfil').addEventListener('submit', function(e) {
            const passwordLama = document.getElementById('password_lama').value;
            const passwordBaru = document.getElementById('password_baru').value;
            const konfirmasiPassword = document.getElementById('konfirmasi_password').value;

            // Jika ingin mengubah password
            if (passwordBaru || konfirmasiPassword) {
                if (!passwordLama) {
                    e.preventDefault();
                    alert('Masukkan kata sandi lama untuk mengubah kata sandi!');
                    return false;
                }

                if (passwordBaru !== konfirmasiPassword) {
                    e.preventDefault();
                    alert('Kata sandi baru dan konfirmasi tidak cocok!');
                    return false;
                }

                if (passwordBaru.length < 6) {
                    e.preventDefault();
                    alert('Kata sandi baru minimal 6 karakter!');
                    return false;
                }
            }

            return true;
        });
    </script>
</body>
</html>