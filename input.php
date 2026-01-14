<?php
session_start();
include 'koneksi.php';

if (!isset($_SESSION['login'])) {
    header("Location: login.php");
    exit;
}

/* =====================
   PROSES SIMPAN DATA
   ===================== */
if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $jenis = $_POST['jenis'];
    $mode  = $_POST['mode'];

    /* ===== PROGRAM STUDI ===== */
    if ($jenis == 'prodi') {
        $nama_prodi = $_POST['nama_prodi'];
        $jenjang = $_POST['jenjang'];
        $akreditasi = $_POST['akreditasi'];
        $keterangan = $_POST['keterangan'];

        if ($mode == 'tambah') {
            mysqli_query($conn, "INSERT INTO program_studi
                (nama_prodi, jenjang, akreditasi, keterangan)
                VALUES
                ('$nama_prodi','$jenjang','$akreditasi','$keterangan')");
        }

        if ($mode == 'edit') {
            $id = $_POST['id'];
            mysqli_query($conn, "UPDATE program_studi SET
                nama_prodi='$nama_prodi',
                jenjang='$jenjang',
                akreditasi='$akreditasi',
                keterangan='$keterangan'
                WHERE id=$id");
        }

        header("Location: index.php");
        exit;
    }

    /* ===== MAHASISWA ===== */
    if ($jenis == 'mahasiswa') {
        $nim = $_POST['nim'];
        $nama_mhs = $_POST['nama_mhs'];
        $tgl_lahir = $_POST['tgl_lahir'];
        $alamat = $_POST['alamat'];
        $program_studi_id = $_POST['program_studi_id'];

        if ($mode == 'tambah') {
            mysqli_query($conn, "INSERT INTO mahasiswa
                (nim, nama_mhs, tgl_lahir, alamat, program_studi_id)
                VALUES
                ('$nim','$nama_mhs','$tgl_lahir','$alamat','$program_studi_id')");
        }

        if ($mode == 'edit') {
            $nim_lama = $_POST['nim_lama'];
            mysqli_query($conn, "UPDATE mahasiswa SET
                nama_mhs='$nama_mhs',
                tgl_lahir='$tgl_lahir',
                alamat='$alamat',
                program_studi_id='$program_studi_id'
                WHERE nim='$nim_lama'");
        }

        header("Location: index.php");
        exit;
    }
}

/* =====================
   MODE FORM
   ===================== */
$jenis = $_GET['jenis'] ?? '';
$mode = 'tambah';

if ($jenis == 'prodi' && isset($_GET['id'])) {
    $mode = 'edit';
    $id = $_GET['id'];
    $data_prodi = mysqli_fetch_assoc(
        mysqli_query($conn, "SELECT * FROM program_studi WHERE id=$id")
    );
}

if ($jenis == 'mahasiswa' && isset($_GET['nim'])) {
    $mode = 'edit';
    $nim = $_GET['nim'];
    $data_mhs = mysqli_fetch_assoc(
        mysqli_query($conn, "SELECT * FROM mahasiswa WHERE nim='$nim'")
    );
}

$list_prodi = mysqli_query($conn, "SELECT * FROM program_studi ORDER BY nama_prodi ASC");
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title><?= ucfirst($mode) ?> Data</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

<div class="container mt-4">
<div class="card">
<div class="card-header">
<h4><?= ucfirst($mode) ?> <?= $jenis == 'prodi' ? 'Program Studi' : 'Mahasiswa' ?></h4>
</div>
<div class="card-body">

<?php if ($jenis == 'prodi'): ?>
<form method="POST">
<input type="hidden" name="jenis" value="prodi">
<input type="hidden" name="mode" value="<?= $mode ?>">
<?php if ($mode == 'edit'): ?>
<input type="hidden" name="id" value="<?= $data_prodi['id'] ?>">
<?php endif; ?>

<div class="mb-3">
<label>Nama Prodi</label>
<input type="text" name="nama_prodi" class="form-control"
value="<?= $mode=='edit'?$data_prodi['nama_prodi']:'' ?>" required>
</div>

<div class="mb-3">
<label>Jenjang</label>
<select name="jenjang" class="form-select" required>
<option value="">Pilih</option>
<?php foreach(['D2','D3','D4','S2'] as $j): ?>
<option value="<?= $j ?>" <?= ($mode=='edit' && $data_prodi['jenjang']==$j)?'selected':'' ?>>
<?= $j ?>
</option>
<?php endforeach; ?>
</select>
</div>

<div class="mb-3">
<label>Akreditasi</label>
<input type="text" name="akreditasi" class="form-control"
value="<?= $mode=='edit'?$data_prodi['akreditasi']:'' ?>" required>
</div>

<div class="mb-3">
<label>Keterangan</label>
<textarea name="keterangan" class="form-control"><?= $mode=='edit'?$data_prodi['keterangan']:'' ?></textarea>
</div>

<button class="btn btn-primary">Simpan</button>
<a href="index.php" class="btn btn-secondary">Kembali</a>
</form>

<?php else: ?>
<form method="POST">
<input type="hidden" name="jenis" value="mahasiswa">
<input type="hidden" name="mode" value="<?= $mode ?>">
<?php if ($mode=='edit'): ?>
<input type="hidden" name="nim_lama" value="<?= $data_mhs['nim'] ?>">
<?php endif; ?>

<div class="mb-3">
<label>NIM</label>
<input type="text" name="nim" class="form-control"
value="<?= $mode=='edit'?$data_mhs['nim']:'' ?>"
<?= $mode=='edit'?'readonly':'' ?> required>
</div>

<div class="mb-3">
<label>Nama Mahasiswa</label>
<input type="text" name="nama_mhs" class="form-control"
value="<?= $mode=='edit'?$data_mhs['nama_mhs']:'' ?>" required>
</div>

<div class="mb-3">
<label>Tanggal Lahir</label>
<input type="date" name="tgl_lahir" class="form-control"
value="<?= $mode=='edit'?$data_mhs['tgl_lahir']:'' ?>" required>
</div>

<div class="mb-3">
<label>Alamat</label>
<textarea name="alamat" class="form-control" required><?= $mode=='edit'?$data_mhs['alamat']:'' ?></textarea>
</div>

<div class="mb-3">
<label>Program Studi</label>
<select name="program_studi_id" class="form-select" required>
<option value="">Pilih</option>
<?php while ($p = mysqli_fetch_assoc($list_prodi)): ?>
<option value="<?= $p['id'] ?>"
<?= ($mode=='edit' && $data_mhs['program_studi_id']==$p['id'])?'selected':'' ?>>
<?= $p['nama_prodi'] ?> (<?= $p['jenjang'] ?>)
</option>
<?php endwhile; ?>
</select>
</div>

<button class="btn btn-primary">Simpan</button>
<a href="index.php" class="btn btn-secondary">Kembali</a>
</form>
<?php endif; ?>

</div>
</div>
</div>

</body>
</html>
