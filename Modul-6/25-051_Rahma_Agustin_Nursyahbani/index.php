<?php
include 'koneksi.php';

$query = "SELECT t.*, p.nama AS nama_pelanggan 
          FROM transaksi t 
          JOIN pelanggan p ON t.pelanggan_id = p.id 
          ORDER BY t.id DESC";
$result = $conn->query($query);

$detail_id = $_GET['detail_id'] ?? null;
$transaksi_detail_data = [];
$transaksi_master_info = null;

if ($detail_id) {
    $detail_id = intval($detail_id);
    
    $q_master = $conn->prepare("SELECT t.*, p.nama AS nama_pelanggan 
                                FROM transaksi t 
                                JOIN pelanggan p ON t.pelanggan_id = p.id 
                                WHERE t.id = ?");
    $q_master->bind_param("i", $detail_id);
    $q_master->execute();
    $transaksi_master_info = $q_master->get_result()->fetch_assoc();
    $q_master->close();

    $q_detail = $conn->prepare("SELECT td.*, b.kode_barang, b.nama_barang 
                                FROM transaksi_detail td 
                                JOIN barang b ON td.barang_id = b.id 
                                WHERE td.transaksi_id = ?");
    $q_detail->bind_param("i", $detail_id);
    $q_detail->execute();
    $res_detail = $q_detail->get_result();
    while ($row = $res_detail->fetch_assoc()) {
        $transaksi_detail_data[] = $row;
    }
    $q_detail->close();
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Data Master & Detail Transaksi</title>
</head>
<body>

<h2>Daftar Master Transaksi Penjualan</h2>

<p>
    <a href="transaksi_detail.php">+ Transaksi Baru</a> | 
    <a href="data_barang.php">Master Barang</a>
</p>

<!-- TABEL MASTER TRANSAKSI -->
<table border="1" cellpadding="6" cellspacing="0">
    <thead>
        <tr>
            <th>No</th>
            <th>ID Transaksi</th>
            <th>Waktu Transaksi</th>
            <th>Nama Pelanggan</th>
            <th>Keterangan</th>
            <th>Total Transaksi</th>
            <th>Aksi</th>
        </tr>
    </thead>
    <tbody>
        <?php
        $no = 1;
        while ($row = $result->fetch_assoc()):
        ?>
        <tr>
            <td><?= $no++; ?></td>
            <td>#<?= $row['id']; ?></td>
            <td><?= $row['waktu_transaksi']; ?></td>
            <td><?= htmlspecialchars($row['nama_pelanggan']); ?></td>
            <td><?= htmlspecialchars($row['keterangan']); ?></td>
            <td>Rp <?= number_format($row['total'], 0, ',', '.'); ?></td>
            <td>
                <a href="index.php?detail_id=<?= $row['id']; ?>">Lihat Detail</a> | 
                <a href="tambah_detail.php?id=<?= $row['id']; ?>">+ Barang</a>
            </td>
        </tr>
        <?php endwhile; ?>
    </tbody>
</table>

<?php if ($transaksi_master_info): ?>
    <hr>
    <h3>Rincian Detail Transaksi #<?= $transaksi_master_info['id']; ?></h3>
    <p><strong>Pelanggan:</strong> <?= htmlspecialchars($transaksi_master_info['nama_pelanggan']); ?></p>
    <p><strong>Waktu Transaksi:</strong> <?= $transaksi_master_info['waktu_transaksi']; ?></p>
    <p><strong>Keterangan:</strong> <?= htmlspecialchars($transaksi_master_info['keterangan']); ?></p>

    <table border="1" cellpadding="6" cellspacing="0">
        <thead>
            <tr>
                <th>No</th>
                <th>Kode Barang</th>
                <th>Nama Barang</th>
                <th>Harga Satuan</th>
                <th>Qty</th>
                <th>Subtotal</th>
            </tr>
        </thead>
        <tbody>
            <?php 
            $no_detail = 1;
            $grand_total = 0;
            if (!empty($transaksi_detail_data)):
                foreach ($transaksi_detail_data as $d):
                    $subtotal = $d['harga'] * $d['qty'];
                    $grand_total += $subtotal;
            ?>
            <tr>
                <td><?= $no_detail++; ?></td>
                <td><?= htmlspecialchars($d['kode_barang']); ?></td>
                <td><?= htmlspecialchars($d['nama_barang']); ?></td>
                <td>Rp <?= number_format($d['harga'], 0, ',', '.'); ?></td>
                <td><?= $d['qty']; ?></td>
                <td>Rp <?= number_format($subtotal, 0, ',', '.'); ?></td>
            </tr>
            <?php 
                endforeach;
            else: 
            ?>
            <tr>
                <td colspan="6" align="center">Belum ada item barang pada transaksi ini.</td>
            </tr>
            <?php endif; ?>
        </tbody>
        <tfoot>
            <tr>
                <th colspan="5" align="right">Total Keseluruhan:</th>
                <th>Rp <?= number_format($grand_total, 0, ',', '.'); ?></th>
            </tr>
        </tfoot>
    </table>
    
    <p><a href="index.php">Sembunyikan Detail</a></p>
<?php endif; ?>

</body>
</html>