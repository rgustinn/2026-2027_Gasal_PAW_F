<?php
require_once 'koneksi.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update'])) {
    $id_supplier = (int)$_POST['id'];
    $nama        = mysqli_real_escape_string($conn, $_POST['nama']);
    $tlp         = mysqli_real_escape_string($conn, $_POST['tlp']);
    $alamat      = mysqli_real_escape_string($conn, $_POST['alamat']);

    $query = "UPDATE supplier SET nama='$nama', tlp='$tlp', alamat='$alamat' WHERE id=$id_supplier";
    mysqli_query($conn, $query);

    header("Location: index.php");
    exit();
}

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
if ($id <= 0) {
    header("Location: index.php");
    exit();
}

$query_edit  = "SELECT * FROM supplier WHERE id = $id";
$result_edit = mysqli_query($conn, $query_edit);
$data        = mysqli_fetch_assoc($result_edit);

if (!$data) {
    header("Location: index.php");
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Edit Data Master Supplier</title>
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

    <h2>Edit Data Master Supplier</h2>
    <form method="POST" action="edit.php">
        <input type="hidden" name="id" value="<?= $data['id'] ?>">
        <div class="form-group">
            <label>Nama</label>
            <input type="text" name="nama" value="<?= htmlspecialchars($data['nama']) ?>" required>
        </div>
        <div class="form-group">
            <label>Telp</label>
            <input type="text" name="tlp" value="<?= htmlspecialchars($data['tlp']) ?>" required>
        </div>
        <div class="form-group">
            <label>Alamat</label>
            <input type="text" name="alamat" value="<?= htmlspecialchars($data['alamat']) ?>" required>
        </div>
        <div class="form-group" style="margin-left: 84px;">
            <button type="submit" name="update" class="btn btn-simpan">Update</button>
            <a href="index.php" class="btn btn-batal">Batal</a>
        </div>
    </form>

</body>
</html>