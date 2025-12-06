<?php include "koneksi.php"; ?>
<!DOCTYPE html>
<html>
<head>
    <title>Tambah Mahasiswa</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>
<body class="container mt-5">

<h3>Tambah Data Mahasiswa</h3>

<form action="" method="POST" class="mt-3">
    <div class="mb-3">
        <label>NIM</label>
        <input type="text" name="nim" class="form-control">
    </div>
    <div class="mb-3">
        <label>Nama Mahasiswa</label>
        <input type="text" name="nama_mhs" class="form-control">
    </div>
    <div class="mb-3">
        <label>Tanggal Lahir</label>
        <input type="date" name="tgl_lahir" class="form-control">
    </div>
    <div class="mb-3">
        <label>Alamat</label>
        <textarea name="alamat" class="form-control"></textarea>
    </div>
    <button type="submit" name="submit" class="btn btn-primary">Simpan</button>
</form>

<?php
if (isset($_POST['submit'])) {
    $nim       = $_POST['nim'];
    $nama_mhs  = $_POST['nama_mhs'];
    $tgl_lahir = $_POST['tgl_lahir'];
    $alamat    = $_POST['alamat'];

    $sql = "INSERT INTO mahasiswa VALUES ('$nim','$nama_mhs','$tgl_lahir','$alamat')";
    mysqli_query($koneksi, $sql);

    echo "<script>alert('Data berhasil disimpan'); window.location='index.php';</script>";
}
?>
</body>
</html>
