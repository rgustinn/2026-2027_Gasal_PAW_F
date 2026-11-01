<?php
require_once 'koneksi.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['simpan'])) {
    $nama   = mysqli_real_escape_string($conn, $_POST['nama']);
    $tlp    = mysqli_real_escape_string($conn, $_POST['tlp']);
    $alamat = mysqli_real_escape_string($conn, $_POST['alamat']);

    $query = "INSERT INTO supplier (nama, tlp, alamat) VALUES ('$nama', '$tlp', '$alamat')";
    mysqli_query($conn, $query);

    header("Location: index.php");
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Tambah Data Master Supplier Baru</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 30px; color: #333; }
        h2 { font-weight: normal; color: #444; }
        .btn { padding: 6px 14px; text-decoration: none; border-radius: 4px; color: white; display: inline-block; font-size: 14px; border: none; cursor: pointer; }
        .btn-simpan { background-color: #5cb85c; }
        .btn-batal { background-color: #d9534f; }
        .form-group { margin-bottom: 15px; }
        .form-group label { display: inline-block; width: 80px; font-weight: bold; }
        .form-group input { padding: 8px; width: 320px; border: 1px solid #ccc; border-radius: 4px; }
    </style>
</head>
<body>

    <h2>Tambah Data Master Supplier Baru</h2>
    <form method="POST" action="tambah.php">
        <div class="form-group">
            <label>Nama</label>
            <input type="text" name="nama" placeholder="Nama" required>
        </div>
        <div class="form-group">
            <label>Telp</label>
            <input type="text" name="tlp" placeholder="Telp" required>
        </div>
        <div class="form-group">
            <label>Alamat</label>
            <input type="text" name="alamat" placeholder="Alamat" required>
        </div>
        <div class="form-group" style="margin-left: 84px;">
            <button type="submit" name="simpan" class="btn btn-simpan">Simpan</button>
            <a href="index.php" class="btn btn-batal">Batal</a>
        </div>
    </form>

</body>
</html>