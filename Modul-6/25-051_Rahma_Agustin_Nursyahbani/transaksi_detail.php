<?php
include 'koneksi.php';

$pesan = "";

// Proses Simpan Transaksi Baru Master-Detail
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $waktu_transaksi = $_POST['waktu_transaksi'];
    $pelanggan_id    = $_POST['pelanggan_id'];
    $keterangan      = $_POST['keterangan'];
    
    $barang_ids      = $_POST['barang_id'] ?? [];
    $qtys            = $_POST['qty'] ?? [];

    if (empty($barang_ids)) {
        $pesan = "Pilih minimal satu barang untuk transaksi!";
    } else {
        $conn->begin_transaction();
        try {
            $total_transaksi = 0;
            
            // 1. Simpan ke tabel transaksi (master)
            $stmt = $conn->prepare("INSERT INTO transaksi (waktu_transaksi, keterangan, total, pelanggan_id) VALUES (?, ?, 0, ?)");
            $stmt->bind_param("ssi", $waktu_transaksi, $keterangan, $pelanggan_id);
            $stmt->execute();
            $transaksi_id = $stmt->insert_id;
            $stmt->close();

            // 2. Simpan ke tabel transaksi_detail
            $stmt_detail = $conn->prepare("INSERT INTO transaksi_detail (transaksi_id, barang_id, harga, qty) VALUES (?, ?, ?, ?)");
            
            for ($i = 0; $i < count($barang_ids); $i++) {
                $b_id = intval($barang_ids[$i]);
                $qty  = intval($qtys[$i]);

                $q_barang = $conn->prepare("SELECT harga FROM barang WHERE id = ?");
                $q_barang->bind_param("i", $b_id);
                $q_barang->execute();
                $d_barang = $q_barang->get_result()->fetch_assoc();
                $harga    = $d_barang['harga'];
                $q_barang->close();

                $subtotal = $harga * $qty;
                $total_transaksi += $subtotal;

                $stmt_detail->bind_param("iiii", $transaksi_id, $b_id, $harga, $qty);
                $stmt_detail->execute();
            }
            $stmt_detail->close();

            // 3. Update total akhir transaksi
            $stmt_update = $conn->prepare("UPDATE transaksi SET total = ? WHERE id = ?");
            $stmt_update->bind_param("ii", $total_transaksi, $transaksi_id);
            $stmt_update->execute();
            $stmt_update->close();

            // 4. Catat otomatis ke tabel pembayaran
            $waktu_bayar = date('Y-m-d H:i:s');
            $metode      = 'TUNAI';
            $stmt_bayar  = $conn->prepare("INSERT INTO pembayaran (waktu_bayar, total, metode, transaksi_id) VALUES (?, ?, ?, ?)");
            $stmt_bayar->bind_param("sisi", $waktu_bayar, $total_transaksi, $metode, $transaksi_id);
            $stmt_bayar->execute();
            $stmt_bayar->close();

            $conn->commit();
            $pesan = "Transaksi berhasil disimpan! ID Transaksi: #$transaksi_id (Total: Rp " . number_format($total_transaksi, 0, ',', '.') . ")";
        } catch (Exception $e) {
            $conn->rollback();
            $pesan = "Gagal menyimpan transaksi: " . htmlspecialchars($e->getMessage());
        }
    }
}

$pelanggan_result = $conn->query("SELECT id, nama FROM pelanggan");
$barang_result    = $conn->query("SELECT id, nama_barang, harga, stok FROM barang");

$list_barang = [];
while ($row = $barang_result->fetch_assoc()) {
    $list_barang[] = $row;
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Form Transaksi Penjualan</title>
    <!-- Library jQuery agar tombol tambah & proteksi opsi barang ganda dapat berjalan -->
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
</head>
<body>

<h2>Form Transaksi Penjualan (Master-Detail)</h2>

<p>
    <a href="index.php">Kembali ke Daftar Transaksi</a>
</p>

<?php if ($pesan): ?>
    <p><strong><?= $pesan; ?></strong></p>
    <hr>
<?php endif; ?>

<form method="POST" action="">
    <h3>Data Master Transaksi</h3>
    <p>
        <label>Tanggal Transaksi:</label><br>
        <input type="datetime-local" name="waktu_transaksi" value="<?= date('Y-m-d\TH:i'); ?>" required>
    </p>
    <p>
        <label>Pelanggan:</label><br>
        <select name="pelanggan_id" required>
            <option value="">-- Pilih Pelanggan --</option>
            <?php while($p = $pelanggan_result->fetch_assoc()): ?>
                <option value="<?= $p['id']; ?>"><?= $p['id'] . ' - ' . htmlspecialchars($p['nama']); ?></option>
            <?php endwhile; ?>
        </select>
    </p>
    <p>
        <label>Keterangan:</label><br>
        <input type="text" name="keterangan" placeholder="Contoh: Lunas / Tunai">
    </p>

    <hr>

    <h3>Detail Barang Transaksi</h3>
    <p>
        <button type="button" id="tambah-baris">+ Tambah Barang</button>
    </p>

    <table border="1" cellpadding="6" cellspacing="0" id="tabel-detail">
        <thead>
            <tr>
                <th>Pilih Barang</th>
                <th>Harga Satuan</th>
                <th>Quantity (Qty)</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>
                    <select name="barang_id[]" class="select-barang" required>
                        <option value="">-- Pilih Barang --</option>
                        <?php foreach($list_barang as $b): ?>
                            <option value="<?= $b['id']; ?>" data-harga="<?= $b['harga']; ?>">
                                <?= htmlspecialchars($b['nama_barang']); ?> (Stok: <?= $b['stok']; ?> - Rp <?= number_format($b['harga'], 0, ',', '.'); ?>)
                            </option>
                        <?php endforeach; ?>
                    </select>
                </td>
                <td>
                    <input type="text" class="text-harga" readonly value="0">
                </td>
                <td>
                    <input type="number" name="qty[]" value="1" min="1" required>
                </td>
                <td>
                    <button type="button" class="hapus-baris" disabled>Hapus</button>
                </td>
            </tr>
        </tbody>
    </table>

    <br>
    <button type="submit">Simpan Transaksi & Hitung Total</button>
</form>

<script>
    const dataBarang = <?= json_encode($list_barang); ?>;

    // Fungsi nonaktifkan opsi barang yang sudah dipilih pada baris lain
    function updateDropdownOptions() {
        let selectedValues = [];
        
        $('.select-barang').each(function() {
            let val = $(this).val();
            if (val) {
                selectedValues.push(val);
            }
        });

        $('.select-barang').each(function() {
            let currentSelect = $(this);
            let currentValue = currentSelect.val();

            currentSelect.find('option').each(function() {
                let optionVal = $(this).val();
                if (optionVal === "") return;

                if (selectedValues.includes(optionVal) && optionVal !== currentValue) {
                    $(this).prop('disabled', true);
                } else {
                    $(this).prop('disabled', false);
                }
            });
        });
    }

    $(document).on('change', '.select-barang', function() {
        let barangId = $(this).val();
        let row = $(this).closest('tr');
        let hargaInput = row.find('.text-harga');

        if (barangId) {
            let selected = dataBarang.find(b => b.id == barangId);
            if (selected) {
                hargaInput.val(new Intl.NumberFormat('id-ID').format(selected.harga));
            }
        } else {
            hargaInput.val(0);
        }

        updateDropdownOptions();
    });

    $('#tambah-baris').click(function() {
        let optionsHtml = '<option value="">-- Pilih Barang --</option>';
        dataBarang.forEach(b => {
            optionsHtml += `<option value="${b.id}">${b.nama_barang} (Stok: ${b.stok} - Rp ${Number(b.harga).toLocaleString('id-ID')})</option>`;
        });

        let newRow = `
            <tr>
                <td>
                    <select name="barang_id[]" class="select-barang" required>
                        ${optionsHtml}
                    </select>
                </td>
                <td>
                    <input type="text" class="text-harga" readonly value="0">
                </td>
                <td>
                    <input type="number" name="qty[]" value="1" min="1" required>
                </td>
                <td>
                    <button type="button" class="hapus-baris">Hapus</button>
                </td>
            </tr>
        `;
        $('#tabel-detail tbody').append(newRow);
        updateDropdownOptions();
    });

    $(document).on('click', '.hapus-baris', function() {
        $(this).closest('tr').remove();
        updateDropdownOptions();
    });
</script>
</body>
</html>