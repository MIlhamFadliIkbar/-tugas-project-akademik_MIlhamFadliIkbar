<?php
include "koneksi.php";
$nim = $_GET['nim'];
$data = mysqli_query($koneksi, "SELECT * FROM mahasiswa WHERE nim='$nim'");
$d = mysqli_fetch_array($data);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Edit Mahasiswa</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>
<body class="container mt-5">

<h3>Edit Data Mahasiswa</h3>

<form method="POST">
    <div class="mb-3">
        <label>NIM</label>
        <input type="text" name="nim" value="<?= $d['nim'] ?>" class="form-control" readonly>
    </div>
    <div class="mb-3">
        <label>Nama Mahasiswa</label>
        <input type="text" name="nama_mhs" value="<?= $d['nama_mhs'] ?>" class="form-control">
    </div>
    <div class="mb-3">
        <label>Tanggal Lahir</label>
        <input type="date" name="tgl_lahir" value="<?= $d['tgl_lahir'] ?>" class="form-control">
    </div>
    <div class="mb-3">
        <label>Alamat</label>
        <textarea name="alamat" class="form-control"><?= $d['alamat'] ?></textarea>
    </div>
    <button type="submit" name="update" class="btn btn-primary">Update</button>
</form>

<?php
if (isset($_POST['update'])) {
    $nama_mhs  = $_POST['nama_mhs'];
    $tgl_lahir = $_POST['tgl_lahir'];
    $alamat    = $_POST['alamat'];

    mysqli_query($koneksi, "UPDATE mahasiswa 
                            SET nama_mhs='$nama_mhs', tgl_lahir='$tgl_lahir', alamat='$alamat'
                            WHERE nim='$nim'");

    echo "<script>alert('Data berhasil diupdate'); window.location='index.php';</script>";
}
?>

</body>
</html>
