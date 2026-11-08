<?php
include 'koneksi.php';

// Logika Hapus Data Barang dengan Pengecekan Relasi Tabel transaksi_detail
if (isset($_GET['action']) && $_GET['action'] === 'hapus' && isset($_GET['id'])) {
    $barang_id = intval($_GET['id']);

    // 1. Cek penggunaan barang pada transaksi_detail
    $cek_sql = "SELECT COUNT(*) AS total FROM transaksi_detail WHERE barang_id = ?";
    $stmt_cek = $conn->prepare($cek_sql);
    $stmt_cek->bind_param("i", $barang_id);
    $stmt_cek->execute();
    $row = $stmt_cek->get_result()->fetch_assoc();
    $stmt_cek->close();

    // 2. Jika sudah dipakai pada transaksi, tolak penghapusan
    if ($row['total'] > 0) {
        $pesan = urlencode("Data barang tidak dapat dihapus karena masih digunakan pada data transaksi!");
        header("Location: data_barang.php?status=gagal&pesan=" . $pesan);
        exit;
    }

    // 3. Jika belum dipakai, hapus barang
    $hapus_sql = "DELETE FROM barang WHERE id = ?";
    $stmt_hapus = $conn->prepare($hapus_sql);
    $stmt_hapus->bind_param("i", $barang_id);
    
    if ($stmt_hapus->execute()) {
        $pesan = urlencode("Data barang berhasil dihapus.");
        header("Location: data_barang.php?status=sukses&pesan=" . $pesan);
    } else {
        $pesan = urlencode("Gagal menghapus data barang: " . $conn->error);
        header("Location: data_barang.php?status=gagal&pesan=" . $pesan);
    }
    
    $stmt_hapus->close();
    exit;
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Data Master Barang</title>
</head>
<body>

<h2>Data Master Barang</h2>

<p>
    <a href="index.php">Lihat Transaksi</a> | 
    <a href="transaksi_detail.php">+ Transaksi Baru</a>
</p>

<?php if (isset($_GET['status']) && isset($_GET['pesan'])): ?>
    <p><strong><?= htmlspecialchars($_GET['pesan']); ?></strong></p>
    <hr>
<?php endif; ?>

<table border="1" cellpadding="6" cellspacing="0">
    <thead>
        <tr>
            <th>No</th>
            <th>Kode Barang</th>
            <th>Nama Barang</th>
            <th>Harga</th>
            <th>Stok</th>
            <th>Aksi</th>
        </tr>
    </thead>
    <tbody>
        <?php
        $no = 1;
        $query = $conn->query("SELECT * FROM barang");
        while ($row = $query->fetch_assoc()):
        ?>
        <tr>
            <td><?= $no++; ?></td>
            <td><?= htmlspecialchars($row['kode_barang']); ?></td>
            <td><?= htmlspecialchars($row['nama_barang']); ?></td>
            <td>Rp <?= number_format($row['harga'], 0, ',', '.'); ?></td>
            <td><?= $row['stok']; ?></td>
            <td>
                <a href="data_barang.php?action=hapus&id=<?= $row['id']; ?>" 
                   onclick="return confirm('Apakah Anda yakin ingin menghapus barang ini?');">
                    Hapus
                </a>
            </td>
        </tr>
        <?php endwhile; ?>
    </tbody>
</table>

</body>
</html>