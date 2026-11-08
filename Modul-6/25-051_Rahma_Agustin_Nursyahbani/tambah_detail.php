<?php
include 'koneksi.php';

// Ambil transaksi_id dari URL, atau otomatis ambil transaksi terakhir
$transaksi_id = $_GET['id'] ?? null;

if (!$transaksi_id) {
    $q_last = $conn->query("SELECT id FROM transaksi ORDER BY id DESC LIMIT 1");
    if ($q_last && $q_last->num_rows > 0) {
        $transaksi_id = $q_last->fetch_assoc()['id'];
    } else {
        die("Belum ada data transaksi di database. Silakan buat transaksi terlebih dahulu di transaksi_detail.php");
    }
}

// Proses simpan item detail baru & update total transaksi
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $barang_id = intval($_POST['barang_id']);
    $qty       = intval($_POST['qty']);

    $conn->begin_transaction();

    try {
        $q_barang = $conn->prepare("SELECT harga FROM barang WHERE id = ?");
        $q_barang->bind_param("i", $barang_id);
        $q_barang->execute();
        $res_barang = $q_barang->get_result();
        
        if ($res_barang->num_rows == 0) {
            throw new Exception("Barang tidak ditemukan.");
        }
        
        $data_barang = $res_barang->fetch_assoc();
        $harga       = $data_barang['harga'];
        $q_barang->close();

        // 1. Simpan detail item baru
        $stmt_detail = $conn->prepare("INSERT INTO transaksi_detail (transaksi_id, barang_id, harga, qty) VALUES (?, ?, ?, ?)");
        $stmt_detail->bind_param("iiii", $transaksi_id, $barang_id, $harga, $qty);
        $stmt_detail->execute();
        $stmt_detail->close();

        // 2. Hitung ulang total transaksi (SUM perkalian harga * qty)
        $q_hitung = $conn->prepare("SELECT SUM(harga * qty) AS total_baru FROM transaksi_detail WHERE transaksi_id = ?");
        $q_hitung->bind_param("i", $transaksi_id);
        $q_hitung->execute();
        $res_hitung = $q_hitung->get_result();
        $data_hitung = $res_hitung->fetch_assoc();
        $total_baru = $data_hitung['total_baru'] ?? 0;
        $q_hitung->close();

        // 3. Update total pada tabel transaksi
        $stmt_update = $conn->prepare("UPDATE transaksi SET total = ? WHERE id = ?");
        $stmt_update->bind_param("ii", $total_baru, $transaksi_id);
        $stmt_update->execute();
        $stmt_update->close();

        // 4. Update total pada tabel pembayaran
        $stmt_pay = $conn->prepare("UPDATE pembayaran SET total = ? WHERE transaksi_id = ?");
        $stmt_pay->bind_param("ii", $total_baru, $transaksi_id);
        $stmt_pay->execute();
        $stmt_pay->close();

        $conn->commit();
        echo "<script>alert('Detail transaksi berhasil ditambahkan dan total diperbarui!'); window.location='tambah_detail.php?id=$transaksi_id';</script>";
        exit;

    } catch (Exception $e) {
        $conn->rollback();
        echo "<script>alert('Gagal menyimpan detail: " . addslashes($e->getMessage()) . "'); window.history.back();</script>";
        exit;
    }
}

// Ambil daftar transaksi untuk pilihan dropdown
$list_transaksi = $conn->query("SELECT t.id, t.waktu_transaksi, p.nama FROM transaksi t JOIN pelanggan p ON t.pelanggan_id = p.id ORDER BY t.id DESC");

// Ambil info transaksi terpilih
$q_trans = $conn->prepare("SELECT t.*, p.nama AS nama_pelanggan FROM transaksi t JOIN pelanggan p ON t.pelanggan_id = p.id WHERE t.id = ?");
$q_trans->bind_param("i", $transaksi_id);
$q_trans->execute();
$transaksi = $q_trans->get_result()->fetch_assoc();

if (!$transaksi) {
    die("Data transaksi dengan ID #$transaksi_id tidak ditemukan!");
}

// Cek barang yang sudah ada pada transaksi ini
$q_ex = $conn->prepare("SELECT barang_id FROM transaksi_detail WHERE transaksi_id = ?");
$q_ex->bind_param("i", $transaksi_id);
$q_ex->execute();
$res_ex = $q_ex->get_result();
$existing_ids = [];
while ($row = $res_ex->fetch_assoc()) {
    $existing_ids[] = $row['barang_id'];
}
$q_ex->close();

$barang_result = $conn->query("SELECT id, nama_barang, harga, stok FROM barang");
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Tambah Detail Transaksi</title>
</head>
<body>

<h2>Tambah Detail Transaksi</h2>

<!-- Dropdown Pilihan Transaksi -->
<p>
    <label><strong>Pilih Transaksi:</strong></label><br>
    <select onchange="location = this.value;">
        <?php while($t = $list_transaksi->fetch_assoc()): ?>
            <option value="tambah_detail.php?id=<?= $t['id']; ?>" <?= ($t['id'] == $transaksi_id) ? 'selected' : ''; ?>>
                Transaksi #<?= $t['id']; ?> - <?= htmlspecialchars($t['nama']); ?> (<?= $t['waktu_transaksi']; ?>)
            </option>
        <?php endwhile; ?>
    </select>
</p>

<hr>

<p><strong>ID Transaksi:</strong> #<?= $transaksi['id']; ?></p>
<p><strong>Waktu Transaksi:</strong> <?= $transaksi['waktu_transaksi']; ?></p>
<p><strong>Pelanggan:</strong> <?= htmlspecialchars($transaksi['nama_pelanggan']); ?></p>
<p><strong>Total Transaksi Saat Ini:</strong> Rp <?= number_format($transaksi['total'], 0, ',', '.'); ?></p>

<hr>

<form method="POST" action="">
    <p>
        <label>Pilih Barang:</label><br>
        <select name="barang_id" required>
            <option value="">-- Pilih Barang --</option>
            <?php while($b = $barang_result->fetch_assoc()): 
                $is_disabled = in_array($b['id'], $existing_ids) ? 'disabled' : '';
                $label_extra = in_array($b['id'], $existing_ids) ? ' (Sudah ada di transaksi)' : '';
            ?>
                <option value="<?= $b['id']; ?>" <?= $is_disabled; ?>>
                    <?= htmlspecialchars($b['nama_barang']); ?> (Stok: <?= $b['stok']; ?> - Rp <?= number_format($b['harga'], 0, ',', '.'); ?>)<?= $label_extra; ?>
                </option>
            <?php endwhile; ?>
        </select>
    </p>

    <p>
        <label>Quantity (Qty):</label><br>
        <input type="number" name="qty" value="1" min="1" required>
    </p>

    <p>
        <button type="submit">Simpan & Hitung Ulang Total</button>
        <a href="index.php">Kembali</a>
    </p>
</form>

</body>
</html>