<?php
require_once 'koneksi.php';

$query_supplier  = "SELECT * FROM supplier ORDER BY id ASC";
$result_supplier = mysqli_query($conn, $query_supplier);
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Data Master Supplier</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 30px; color: #333; }
        h2 { font-weight: normal; color: #444; }
        .btn { padding: 6px 14px; text-decoration: none; border-radius: 4px; color: white; display: inline-block; font-size: 14px; border: none; cursor: pointer; }
        .btn-tambah { background-color: #5cb85c; float: right; }
        .btn-edit { background-color: #f0ad4e; padding: 4px 10px; font-size: 13px; }
        .btn-hapus { background-color: #d9534f; padding: 4px 10px; font-size: 13px; }
        table { width: 100%; border-collapse: collapse; margin-top: 15px; }
        th, td { border: 1px solid #e2e2e2; padding: 8px 12px; text-align: left; }
        th { background-color: #e8f4f8; }
    </style>
</head>
<body>

    <div style="overflow: hidden;">
        <a href="tambah.php" class="btn btn-tambah">Tambah Data</a>
        <h2 style="margin: 0;">Data Master Supplier</h2>
    </div>

    <table>
        <tr>
            <th style="width: 50px;">No</th>
            <th>Nama</th>
            <th style="width: 150px;">Telp</th>
            <th>Alamat</th>
            <th style="width: 140px;">Tindakan</th>
        </tr>
        <?php if (mysqli_num_rows($result_supplier) > 0): ?>
            <?php $no = 1; ?>
            <?php while ($row = mysqli_fetch_assoc($result_supplier)): ?>
                <tr>
                    <td><?= $no++ ?></td>
                    <td><?= htmlspecialchars($row["nama"]) ?></td>
                    <td><?= htmlspecialchars($row["tlp"]) ?></td>
                    <td><?= htmlspecialchars($row["alamat"]) ?></td>
                    <td>
                        <a href="edit.php?id=<?= $row['id'] ?>" class="btn btn-edit">Edit</a>
                        <a href="hapus.php?id=<?= $row['id'] ?>" class="btn btn-hapus" onclick="return confirm('Anda yakin akan menghapus supplier ini?');">Hapus</a>
                    </td>
                </tr>
            <?php endwhile; ?>
        <?php else: ?>
            <tr>
                <td colspan="5" style="text-align: center;">Tidak ada data.</td>
            </tr>
        <?php endif; ?>
    </table>

</body>
</html>