<?php include "koneksi.php"; ?>
<!DOCTYPE html>
<html>
<head>
    <title>Data Mahasiswa</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>
<body class="container mt-5">

<h3>Data Mahasiswa</h3>

<a href="tambah.php" class="btn btn-success mb-3">+ Tambah Data</a>

<table class="table table-bordered table-striped">
    <tr>
        <th>NIM</th>
        <th>Nama</th>
        <th>Tanggal Lahir</th>
        <th>Alamat</th>
        <th>Aksi</th>
    </tr>

    <?php
    $data = mysqli_query($koneksi, "SELECT * FROM mahasiswa");
    while ($d = mysqli_fetch_array($data)) {
    ?>
    <tr>
        <td><?= $d['nim'] ?></td>
        <td><?= $d['nama_mhs'] ?></td>
        <td><?= $d['tgl_lahir'] ?></td>
        <td><?= $d['alamat'] ?></td>
        <td>
            <a href="edit.php?nim=<?= $d['nim'] ?>" class="btn btn-warning btn-sm">Edit</a>
            <a href="hapus.php?nim=<?= $d['nim'] ?>" class="btn btn-danger btn-sm"
               onclick="return confirm('Hapus data ini?')">Hapus</a>
        </td>
    </tr>
    <?php } ?>
</table>

</body>
</html>
