<?php
// index.php
session_start();
include 'koneksi.php';

if (!isset($_SESSION['login'])) {
    header("Location: login.php");
    exit;
}

// Hitung total program studi
$query_prodi = "SELECT COUNT(*) as total FROM program_studi";
$result_prodi = mysqli_query($conn, $query_prodi);
$total_prodi = mysqli_fetch_assoc($result_prodi)['total'];

// Hitung total mahasiswa
$query_mhs = "SELECT COUNT(*) as total FROM mahasiswa";
$result_mhs = mysqli_query($conn, $query_mhs);
$total_mhs = mysqli_fetch_assoc($result_mhs)['total'];

// Ambil data program studi
$data_prodi = mysqli_query($conn, "SELECT * FROM program_studi ORDER BY id ASC");

// Ambil data mahasiswa dengan join program studi
$data_mhs = mysqli_query($conn, "SELECT m.*, p.nama_prodi FROM mahasiswa m LEFT JOIN program_studi p ON m.program_studi_id = p.id ORDER BY m.nim ASC");
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Sistem Akademik</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary">
        <div class="container">
            <a class="navbar-brand" href="#">Sistem Akademik</a>
            <div class="navbar-nav ms-auto">
                <span class="navbar-text text-white me-3">
                    Selamat datang, <?= $_SESSION['nama_lengkap'] ?>
                </span>
                <a href="logout.php" class="btn btn-light btn-sm">Logout</a>
            </div>
        </div>
    </nav>

    <div class="container mt-4">
        <h2 class="mb-4">Dashboard</h2>
        
        <div class="row mb-4">
            <div class="col-md-6">
                <div class="card text-white bg-success">
                    <div class="card-body">
                        <h5 class="card-title">Total Program Studi</h5>
                        <h2><?= $total_prodi ?></h2>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card text-white bg-info">
                    <div class="card-body">
                        <h5 class="card-title">Total Mahasiswa</h5>
                        <h2><?= $total_mhs ?></h2>
                    </div>
                </div>
            </div>
        </div>

        <ul class="nav nav-tabs" id="myTab" role="tablist">
            <li class="nav-item" role="presentation">
                <button class="nav-link active" id="prodi-tab" data-bs-toggle="tab" data-bs-target="#prodi" type="button">Program Studi</button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="mahasiswa-tab" data-bs-toggle="tab" data-bs-target="#mahasiswa" type="button">Mahasiswa</button>
            </li>
        </ul>

        <div class="tab-content" id="myTabContent">
            <div class="tab-pane fade show active" id="prodi">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5>Data Program Studi</h5>
                        <a href="input.php?jenis=prodi" class="btn btn-primary btn-sm">Tambah Program Studi</a>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-striped table-hover">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Nama Program Studi</th>
                                        <th>Jenjang</th>
                                        <th>Akreditasi</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php while ($row = mysqli_fetch_assoc($data_prodi)): ?>
                                    <tr>
                                        <td><?= $row['id'] ?></td>
                                        <td><?= $row['nama_prodi'] ?></td>
                                        <td><?= $row['jenjang'] ?></td>
                                        <td><?= $row['akreditasi'] ?></td>
                                        <td>
                                            <a href="input.php?jenis=prodi&id=<?= $row['id'] ?>" class="btn btn-warning btn-sm">Edit</a>
                                            <a href="hapus.php?jenis=prodi&id=<?= $row['id'] ?>" class="btn btn-danger btn-sm" onclick="return confirm('Yakin ingin menghapus?')">Hapus</a>
                                        </td>
                                    </tr>
                                    <?php endwhile; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <div class="tab-pane fade" id="mahasiswa">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5>Data Mahasiswa</h5>
                        <a href="input.php?jenis=mahasiswa" class="btn btn-primary btn-sm">Tambah Mahasiswa</a>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-striped table-hover">
                                <thead>
                                    <tr>
                                        <th>NIM</th>
                                        <th>Nama</th>
                                        <th>Tanggal Lahir</th>
                                        <th>Alamat</th>
                                        <th>Program Studi</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php while ($row = mysqli_fetch_assoc($data_mhs)): ?>
                                    <tr>
                                        <td><?= $row['nim'] ?></td>
                                        <td><?= $row['nama_mhs'] ?></td>
                                        <td><?= date('d/m/Y', strtotime($row['tgl_lahir'])) ?></td>
                                        <td><?= substr($row['alamat'], 0, 30) ?>...</td>
                                        <td><?= $row['nama_prodi'] ?></td>
                                        <td>
                                            <a href="input.php?jenis=mahasiswa&nim=<?= $row['nim'] ?>" class="btn btn-warning btn-sm">Edit</a>
                                            <a href="hapus.php?jenis=mahasiswa&nim=<?= $row['nim'] ?>" class="btn btn-danger btn-sm" onclick="return confirm('Yakin ingin menghapus?')">Hapus</a>
                                        </td>
                                    </tr>
                                    <?php endwhile; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>